<?php

use App\Models\Article;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('requires an admin session to autosave an article draft', function () {
    $this->postJson(route('admin.articles.autosave'), [
        'autosave_token' => '10000000-0000-4000-8000-000000000001',
    ])->assertRedirect(route('login'));
});

it('requires complete article fields before creating an automatic draft', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)->postJson(route('admin.articles.autosave'), [
        'autosave_token' => '10000000-0000-4000-8000-000000000004',
    ])->assertUnprocessable()
        ->assertJsonValidationErrors(['title', 'category', 'excerpt', 'content']);

    expect(Article::query()->exists())->toBeFalse();
});

it('autosaves completed article fields into one reusable draft', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $autosaveToken = '10000000-0000-4000-8000-000000000002';

    $this->actingAs($admin)->postJson(route('admin.articles.autosave'), [
        'autosave_token' => $autosaveToken,
        'title' => 'Draft Otomatis Artikel',
        'author' => 'Tim Media JCP',
        'category' => 'Digital Marketing',
        'excerpt' => 'Ringkasan awal yang tersimpan otomatis.',
        'content' => '<p>Isi awal artikel.</p>',
        'image_url' => 'https://example.com/draft-article.jpg',
        'image_url_changed' => true,
    ])->assertOk()
        ->assertJsonStructure(['article_id', 'image_preview_url', 'saved_at']);

    $article = Article::query()->where('autosave_token', $autosaveToken)->firstOrFail();

    expect($article->status)->toBe('Draft')
        ->and($article->published_at)->toBeNull()
        ->and($article->image_url)->toBe('https://example.com/draft-article.jpg');

    $this->actingAs($admin)->postJson(route('admin.articles.autosave'), [
        'autosave_token' => $autosaveToken,
        'title' => 'Draft Otomatis Diperbarui',
        'author' => 'Tim Media JCP',
        'category' => 'Digital Marketing',
        'excerpt' => 'Ringkasan terbaru yang tersimpan otomatis.',
        'content' => '<p>Isi terbaru artikel.</p>',
    ])->assertOk();

    expect(Article::query()->count())->toBe(1)
        ->and($article->refresh()->title)->toBe('Draft Otomatis Diperbarui')
        ->and($article->excerpt)->toBe('Ringkasan terbaru yang tersimpan otomatis.');

    $this->get(route('artikel.show', ['article' => $article->slug]))->assertNotFound();
});

it('keeps a local publication schedule when an article is autosaved', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)->postJson(route('admin.articles.autosave'), [
        'autosave_token' => '10000000-0000-4000-8000-000000000005',
        'title' => 'Draft Terjadwal Otomatis',
        'category' => 'Umum',
        'excerpt' => 'Ringkasan draft terjadwal otomatis.',
        'content' => '<p>Isi draft terjadwal otomatis.</p>',
        'published_at' => '2026-11-05T08:30',
        'published_at_timezone' => 'Asia/Makassar',
    ])->assertOk();

    $article = Article::query()->where('title', 'Draft Terjadwal Otomatis')->firstOrFail();

    expect($article->status)->toBe('Draft')
        ->and($article->published_at?->format('Y-m-d H:i'))->toBe('2026-11-05 07:30');
});

it('publishes an autosaved draft without creating a duplicate article', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_admin' => true]);
    $autosaveToken = '10000000-0000-4000-8000-000000000003';

    $this->actingAs($admin)->postJson(route('admin.articles.autosave'), [
        'autosave_token' => $autosaveToken,
        'title' => 'Artikel dengan Foto Autosave',
        'category' => 'Teknologi',
        'excerpt' => 'Ringkasan artikel dengan foto autosave.',
        'content' => '<p>Isi artikel dengan foto autosave.</p>',
        'image' => UploadedFile::fake()->image('autosave-article.jpg'),
    ])->assertOk();

    $draft = Article::query()->where('autosave_token', $autosaveToken)->firstOrFail();
    Storage::disk('public')->assertExists($draft->image_path);

    $response = $this->actingAs($admin)->post(route('admin.articles.store'), [
        'autosave_token' => $autosaveToken,
        'title' => 'Artikel dengan Foto Autosave',
        'category' => 'Teknologi',
        'excerpt' => 'Ringkasan artikel dengan foto autosave.',
        'content' => '<p>Isi artikel dengan foto autosave.</p>',
        'published_at' => now()->format('Y-m-d\TH:i'),
        'published_at_timezone' => config('app.timezone'),
        'status' => 'Publish',
    ]);

    $response->assertRedirect(route('admin.dashboard', ['tab' => 'artikel']));
    expect(Article::query()->count())->toBe(1)
        ->and($draft->refresh()->status)->toBe('Publish')
        ->and($draft->autosave_token)->toBeNull();
    Storage::disk('public')->assertExists($draft->image_path);
});

it('creates a published article that appears on the user article page', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)->post(route('admin.articles.store'), [
        'title' => 'Panduan Konten Digital',
        'category' => 'Digital Marketing',
        'excerpt' => 'Ringkasan panduan konten digital.',
        'content' => 'Isi lengkap panduan konten digital.',
        'published_at' => now()->format('Y-m-d\TH:i'),
        'published_at_timezone' => config('app.timezone'),
        'status' => 'Publish',
    ]);

    $article = Article::query()->where('title', 'Panduan Konten Digital')->firstOrFail();

    $response->assertRedirect(route('admin.dashboard', ['tab' => 'artikel']));
    $this->get(route('artikel'))
        ->assertOk()
        ->assertSee('Panduan Konten Digital')
        ->assertSee('Ringkasan panduan konten digital.');
    expect($article->slug)->toBe('panduan-konten-digital');
});

it('supports rich text, scheduled publication, and image URLs', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $scheduledDate = now()->addDay()->format('Y-m-d\TH:i');

    $response = $this->actingAs($admin)->post(route('admin.articles.store'), [
        'title' => 'Artikel Terjadwal',
        'author' => 'Tim Media JCP',
        'category' => 'Berita',
        'excerpt' => 'Ringkasan artikel terjadwal.',
        'content' => '<p>Isi <strong>tebal</strong> dan <em>miring</em>.</p><script>alert("xss")</script>',
        'image_url' => 'https://example.com/article.jpg',
        'published_at' => $scheduledDate,
        'published_at_timezone' => config('app.timezone'),
        'status' => 'Draft',
    ]);

    $article = Article::query()->where('title', 'Artikel Terjadwal')->firstOrFail();

    $response->assertRedirect(route('admin.dashboard', ['tab' => 'artikel']));
    expect($article->author)->toBe('Tim Media JCP')
        ->and($article->image_url)->toBe('https://example.com/article.jpg')
        ->and($article->content)->toContain('<strong>tebal</strong>')
        ->and($article->content)->not->toContain('<script');

    $this->get(route('artikel.show', ['article' => $article->slug]))->assertNotFound();
});

it('allows only one article to be scheduled per day', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $scheduledDate = now()->addDays(2)->format('Y-m-d\TH:i');
    Article::create([
        'title' => 'Artikel Pertama',
        'slug' => 'artikel-pertama',
        'category' => 'Umum',
        'excerpt' => 'Ringkasan pertama.',
        'content' => 'Konten pertama.',
        'published_at' => $scheduledDate,
        'status' => 'Draft',
    ]);

    $response = $this->actingAs($admin)->post(route('admin.articles.store'), [
        'title' => 'Artikel Kedua',
        'author' => 'Tim Media JCP',
        'category' => 'Umum',
        'excerpt' => 'Ringkasan kedua.',
        'content' => 'Konten kedua.',
        'published_at' => $scheduledDate,
        'published_at_timezone' => config('app.timezone'),
        'status' => 'Draft',
    ]);

    $response->assertSessionHasErrors('published_at');
    expect(Article::query()->where('title', 'Artikel Kedua')->exists())->toBeFalse();
});

it('publishes scheduled articles when the scheduler command runs', function () {
    $article = Article::create([
        'title' => 'Artikel Siap Tayang',
        'slug' => 'artikel-siap-tayang',
        'category' => 'Umum',
        'excerpt' => 'Ringkasan siap tayang.',
        'content' => 'Konten siap tayang.',
        'published_at' => today()->subDay(),
        'status' => 'Draft',
    ]);

    $this->artisan('articles:publish-scheduled')->assertSuccessful();

    expect($article->refresh()->status)->toBe('Publish');
});

it('uses the computer timezone and waits until the scheduled minute to publish', function () {
    $this->travelTo(Carbon::create(2026, 11, 4, 10, 0, 0, 'Asia/Jakarta'));
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)->post(route('admin.articles.store'), [
        'title' => 'Artikel Waktu Lokal',
        'category' => 'Umum',
        'excerpt' => 'Ringkasan artikel waktu lokal.',
        'content' => 'Konten artikel waktu lokal.',
        'published_at' => '2026-11-04T12:00',
        'published_at_timezone' => 'Asia/Makassar',
        'status' => 'Terjadwalkan',
    ])->assertRedirect(route('admin.dashboard', ['tab' => 'artikel']));

    $article = Article::query()->where('title', 'Artikel Waktu Lokal')->firstOrFail();

    expect($article->published_at?->format('Y-m-d H:i'))->toBe('2026-11-04 11:00')
        ->and($article->status)->toBe('Draft');

    $this->artisan('articles:publish-scheduled')->assertSuccessful();
    expect($article->refresh()->status)->toBe('Draft');

    $this->travelTo(Carbon::create(2026, 11, 4, 11, 0, 0, 'Asia/Jakarta'));
    $this->artisan('articles:publish-scheduled')->assertSuccessful();
    expect($article->refresh()->status)->toBe('Publish');
});

it('updates an article from the admin dashboard', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $article = Article::create([
        'title' => 'Judul Lama',
        'slug' => 'judul-lama',
        'category' => 'Umum',
        'excerpt' => 'Ringkasan lama.',
        'content' => 'Konten lama.',
        'published_at' => today(),
        'status' => 'Publish',
    ]);

    $response = $this->actingAs($admin)->put(route('admin.articles.update', $article), [
        'title' => 'Judul Baru',
        'category' => 'Artificial Intelligence',
        'excerpt' => 'Ringkasan baru.',
        'content' => 'Konten baru.',
        'published_at' => now()->format('Y-m-d\TH:i'),
        'published_at_timezone' => config('app.timezone'),
        'status' => 'Draft',
    ]);

    $response->assertRedirect(route('admin.dashboard', ['tab' => 'artikel']));
    expect($article->refresh()->title)->toBe('Judul Baru')
        ->and($article->slug)->toBe('judul-baru')
        ->and($article->status)->toBe('Draft');
});

it('allows changing an article publication schedule while editing', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $article = Article::create([
        'title' => 'Artikel yang Bisa Dijadwalkan',
        'slug' => 'artikel-yang-bisa-dijadwalkan',
        'category' => 'Umum',
        'excerpt' => 'Ringkasan artikel.',
        'content' => 'Konten artikel.',
        'published_at' => today(),
        'status' => 'Publish',
    ]);
    $scheduledDate = now()->addDays(3)->format('Y-m-d\TH:i');

    $this->actingAs($admin)->put(route('admin.articles.update', $article), [
        'title' => $article->title,
        'category' => $article->category,
        'excerpt' => $article->excerpt,
        'content' => $article->content,
        'published_at' => $scheduledDate,
        'published_at_timezone' => config('app.timezone'),
        'status' => 'Terjadwalkan',
    ])->assertRedirect(route('admin.dashboard', ['tab' => 'artikel']));

    expect($article->refresh()->status)->toBe('Draft')
        ->and($article->published_at?->format('Y-m-d\TH:i'))->toBe($scheduledDate);

    $this->actingAs($admin)->put(route('admin.articles.update', $article), [
        'title' => $article->title,
        'category' => $article->category,
        'excerpt' => $article->excerpt,
        'content' => $article->content,
        'published_at' => null,
        'status' => 'Draft',
    ])->assertRedirect(route('admin.dashboard', ['tab' => 'artikel']));

    expect($article->refresh()->status)->toBe('Draft')
        ->and($article->published_at)->toBeNull();
});

it('opens a read-only article preview inside its dashboard card', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $article = Article::create([
        'title' => 'Artikel Editor',
        'slug' => 'artikel-editor',
        'category' => 'Teknologi',
        'excerpt' => 'Ringkasan artikel editor.',
        'content' => 'Isi artikel editor.',
        'published_at' => today(),
        'status' => 'Publish',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard', [
        'tab' => 'artikel',
        'article_id' => $article->id,
    ]));

    $response->assertOk()
        ->assertSee('Kalender jadwal posting')
        ->assertSee('data-rich-editor', false)
        ->assertSee('onsubmit="return validateRichEditor(this)"', false)
        ->assertSee('data-article-calendar-date=', false)
        ->assertSee('Preview artikel')
        ->assertSee('Artikel Editor')
        ->assertSee('Ringkasan artikel editor.')
        ->assertSee('Isi artikel editor.')
        ->assertSee('data-start-article-edit', false)
        ->assertSee('data-delete-type="Artikel"', false)
        ->assertSee('data-delete-name="Artikel Editor"', false)
        ->assertSee('data-editor-url=', false)
        ->assertSee('data-close-editor', false)
        ->assertDontSee('inline_article_title_', false);
});

it('loads the selected article into the top editor when edit is requested', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $article = Article::create([
        'title' => 'Artikel Siap Diedit',
        'author' => 'Tim Editorial',
        'slug' => 'artikel-siap-diedit',
        'category' => 'Teknologi',
        'excerpt' => 'Ringkasan yang dimuat ke editor.',
        'content' => '<p>Isi yang dimuat ke editor.</p>',
        'published_at' => today(),
        'status' => 'Publish',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard', [
        'tab' => 'artikel',
        'article_id' => $article->id,
        'edit_article' => 1,
    ]));

    $response->assertOk()
        ->assertSee('data-article-editor-mode="edit"', false)
        ->assertSee('Mode edit aktif.')
        ->assertSee('Perbarui Artikel Siap Diedit')
        ->assertSee('action="'.route('admin.articles.update', $article).'"', false)
        ->assertSee('value="Artikel Siap Diedit"', false)
        ->assertSee('value="Tim Editorial"', false)
        ->assertSee('Ringkasan yang dimuat ke editor.')
        ->assertSee('Isi yang dimuat ke editor.')
        ->assertSee('Simpan perubahan')
        ->assertDontSee('data-article-autosave-url=', false);
});

it('filters the article list by status and title', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    Article::create([
        'title' => 'Artikel Draft Saja',
        'slug' => 'artikel-draft-saja',
        'category' => 'Umum',
        'excerpt' => 'Ringkasan draft.',
        'content' => 'Konten draft.',
        'status' => 'Draft',
    ]);
    Article::create([
        'title' => 'Artikel Terjadwalkan Saja',
        'slug' => 'artikel-terjadwalkan-saja',
        'category' => 'Umum',
        'excerpt' => 'Ringkasan jadwal.',
        'content' => 'Konten jadwal.',
        'published_at' => today()->addDay(),
        'status' => 'Draft',
    ]);
    Article::create([
        'title' => 'Artikel Publish Saja',
        'slug' => 'artikel-publish-saja',
        'category' => 'Umum',
        'excerpt' => 'Ringkasan publish.',
        'content' => 'Konten publish.',
        'status' => 'Publish',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.dashboard', ['tab' => 'artikel', 'article_status' => 'scheduled']))
        ->assertOk()
        ->assertSee('Artikel Terjadwalkan Saja')
        ->assertSee('Artikel Draft Saja')
        ->assertSee('Artikel Publish Saja')
        ->assertSee('data-article-status-filter="scheduled"', false)
        ->assertSee('data-article-filter-status="scheduled"', false);

    $this->actingAs($admin)
        ->get(route('admin.dashboard', ['tab' => 'artikel', 'article_search' => 'Draft Saja']))
        ->assertOk()
        ->assertSee('Artikel Draft Saja')
        ->assertSee('Artikel Terjadwalkan Saja')
        ->assertSee('Artikel Publish Saja')
        ->assertSee('value="Draft Saja"', false);
});

it('returns only the requested calendar month fragment', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)
        ->get(route('admin.dashboard', [
            'tab' => 'artikel',
            'article_month' => today()->format('Y-m'),
            'calendar_only' => 1,
        ]))
        ->assertOk()
        ->assertSee('data-article-calendar', false)
        ->assertSee('data-calendar-navigation="today"', false)
        ->assertDontSee('JCP ADMIN');
});

it('stores an article photo and opens the published article detail page', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)->post(route('admin.articles.store'), [
        'title' => 'Drone untuk Dokumentasi Event',
        'category' => 'Teknologi',
        'excerpt' => 'Drone membantu dokumentasi berbagai kegiatan.',
        'content' => "Pembahasan lengkap tentang penggunaan drone.\n\nContoh penerapannya ada di sini.",
        'image' => UploadedFile::fake()->image('drone-event.jpg'),
        'published_at' => now()->format('Y-m-d\TH:i'),
        'published_at_timezone' => config('app.timezone'),
        'status' => 'Publish',
    ]);

    $article = Article::query()->where('title', 'Drone untuk Dokumentasi Event')->firstOrFail();

    $response->assertRedirect(route('admin.dashboard', ['tab' => 'artikel']));
    Storage::disk('public')->assertExists($article->image_path);
    $this->get(route('artikel.show', ['article' => $article->slug]))
        ->assertOk()
        ->assertSee('Drone untuk Dokumentasi Event')
        ->assertSee('Pembahasan lengkap tentang penggunaan drone.')
        ->assertSee(Storage::disk('public')->url($article->image_path), false)
        ->assertDontSee('menit baca');
    $this->actingAs($admin)
        ->get(route('admin.dashboard', ['tab' => 'artikel']))
        ->assertOk()
        ->assertDontSee('Waktu baca')
        ->assertDontSee('menit baca');
});

it('does not expose draft articles through the public detail route', function () {
    $article = Article::create([
        'title' => 'Artikel Draft',
        'slug' => 'artikel-draft',
        'category' => 'Umum',
        'excerpt' => 'Ringkasan draft.',
        'content' => 'Isi draft.',
        'status' => 'Draft',
    ]);

    $this->get(route('artikel.show', ['article' => $article->slug]))->assertNotFound();
});

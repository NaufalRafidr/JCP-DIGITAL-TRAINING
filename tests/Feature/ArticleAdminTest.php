<?php

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('creates a published article that appears on the user article page', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)->post(route('admin.articles.store'), [
        'title' => 'Panduan Konten Digital',
        'category' => 'Digital Marketing',
        'excerpt' => 'Ringkasan panduan konten digital.',
        'content' => 'Isi lengkap panduan konten digital.',
        'published_at' => today()->toDateString(),
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
    $scheduledDate = today()->addDay()->toDateString();

    $response = $this->actingAs($admin)->post(route('admin.articles.store'), [
        'title' => 'Artikel Terjadwal',
        'author' => 'Tim Media JCP',
        'category' => 'Berita',
        'excerpt' => 'Ringkasan artikel terjadwal.',
        'content' => '<p>Isi <strong>tebal</strong> dan <em>miring</em>.</p><script>alert("xss")</script>',
        'image_url' => 'https://example.com/article.jpg',
        'published_at' => $scheduledDate,
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
    $scheduledDate = today()->addDays(2)->toDateString();
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
        'published_at' => today()->toDateString(),
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
    $scheduledDate = today()->addDays(3)->toDateString();

    $this->actingAs($admin)->put(route('admin.articles.update', $article), [
        'title' => $article->title,
        'category' => $article->category,
        'excerpt' => $article->excerpt,
        'content' => $article->content,
        'published_at' => $scheduledDate,
        'status' => 'Terjadwalkan',
    ])->assertRedirect(route('admin.dashboard', ['tab' => 'artikel']));

    expect($article->refresh()->status)->toBe('Draft')
        ->and($article->published_at?->toDateString())->toBe($scheduledDate);

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

it('opens the selected article editor inside its dashboard card', function () {
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
        ->assertSee('Pengaturan artikel')
        ->assertSee('Artikel Editor')
        ->assertSee('Ringkasan artikel editor.')
        ->assertSee('Isi artikel editor.')
        ->assertSee('publishArticleNow', false)
        ->assertSee('data-delete-type="Artikel"', false)
        ->assertSee('data-delete-name="Artikel Editor"', false)
        ->assertSee('data-editor-url=', false)
        ->assertSee('data-close-editor', false);
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
        'published_at' => today()->toDateString(),
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

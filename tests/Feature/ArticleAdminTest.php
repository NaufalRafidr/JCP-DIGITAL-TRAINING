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
        ->assertSee('Pengaturan artikel')
        ->assertSee('Artikel Editor')
        ->assertSee('Ringkasan artikel editor.')
        ->assertSee('Isi artikel editor.')
        ->assertSee('data-delete-type="Artikel"', false)
        ->assertSee('data-delete-name="Artikel Editor"', false)
        ->assertSee('data-editor-url=', false)
        ->assertSee('data-close-editor', false);
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

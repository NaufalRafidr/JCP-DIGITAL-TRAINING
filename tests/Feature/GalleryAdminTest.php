<?php

use App\Models\Category;
use App\Models\GalleryFolder;
use App\Models\GalleryPhoto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('creates a shared training field from the field management page', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)->post(route('admin.categories.store'), [
        'name' => 'Dokumentasi Event',
        'short_name' => 'Event',
        'description' => 'Dokumentasi kegiatan dan event JCP.',
    ]);

    $category = Category::query()->where('slug', 'dokumentasi-event')->firstOrFail();

    $response->assertRedirect(route('admin.dashboard', ['tab' => 'bidang']));
    expect($category->is_gallery)->toBeTrue()
        ->and($category->short_name)->toBe('Event');

    $this->actingAs($admin)
        ->get(route('admin.dashboard', ['tab' => 'bidang']))
        ->assertOk()
        ->assertSee('Dokumentasi Event')
        ->assertSee('Bidang Pelatihan')
        ->assertSee('Tambah bidang');
});

it('creates a gallery folder under the selected category', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $category = Category::create([
        'name' => 'Artificial Intelligence',
        'slug' => 'artificial-intelligence',
        'short_name' => 'AI',
    ]);

    $response = $this->actingAs($admin)->post(route('admin.gallery.folders.store'), [
        'name' => 'Workshop AI Oktober',
        'description' => 'Dokumentasi workshop AI.',
        'category_id' => $category->id,
    ]);

    $folder = GalleryFolder::query()->where('name', 'Workshop AI Oktober')->firstOrFail();

    $response->assertRedirect(route('admin.dashboard', ['tab' => 'galeri', 'folder_id' => $folder->id]));
    expect($folder->category_id)->toBe($category->id)
        ->and($folder->status)->toBe('Draft');
});

it('shows folder deletion details beside the album count in the admin gallery', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $category = Category::create([
        'name' => 'Drone Technology',
        'slug' => 'drone-delete-details',
        'short_name' => 'Drone',
    ]);
    $folder = GalleryFolder::create([
        'name' => 'Dokumentasi Drone',
        'slug' => 'dokumentasi-drone-delete-details',
        'category_id' => $category->id,
    ]);
    $folder->photos()->create(['path' => 'gallery/'.$folder->id.'/one.jpg']);
    $folder->photos()->create(['path' => 'gallery/'.$folder->id.'/two.jpg']);

    $response = $this->actingAs($admin)->get(route('admin.dashboard', ['tab' => 'galeri']));

    $response->assertOk()
        ->assertSee('data-gallery-folder-delete', false)
        ->assertSee('data-folder-name="Dokumentasi Drone"', false)
        ->assertSee('data-folder-category="Drone"', false)
        ->assertSee('data-photo-count="2"', false)
        ->assertSee('Semua foto di dalam folder ini juga akan dihapus.');
});

it('requires a category when creating a gallery folder', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)->post(route('admin.gallery.folders.store'), [
        'name' => 'Folder Tanpa Bidang',
    ]);

    $response->assertSessionHasErrors('category_id');
    expect(GalleryFolder::query()->where('name', 'Folder Tanpa Bidang')->exists())->toBeFalse();
});

it('updates the category of an existing gallery folder', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $oldCategory = Category::create([
        'name' => 'Artificial Intelligence',
        'slug' => 'artificial-intelligence',
        'short_name' => 'AI',
    ]);
    $newCategory = Category::create([
        'name' => 'Drone Technology',
        'slug' => 'drone-technology',
        'short_name' => 'Drone',
    ]);
    $folder = GalleryFolder::create([
        'name' => 'Album Lama',
        'slug' => 'album-lama',
        'category_id' => $oldCategory->id,
    ]);

    $response = $this->actingAs($admin)->put(route('admin.gallery.folders.update', $folder), [
        'name' => 'Album Drone Terbaru',
        'description' => 'Dokumentasi drone terbaru.',
        'category_id' => $newCategory->id,
        'status' => 'Publish',
    ]);

    $response->assertRedirect(route('admin.dashboard', ['tab' => 'galeri']));
    expect($folder->refresh()->category_id)->toBe($newCategory->id)
        ->and($folder->name)->toBe('Album Drone Terbaru')
        ->and($folder->slug)->toBe('album-drone-terbaru')
        ->and($folder->description)->toBe('Dokumentasi drone terbaru.');
});

it('saves a folder as a draft and hides it from the public gallery', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $category = Category::create([
        'name' => 'Digital Marketing',
        'slug' => 'digital-marketing',
        'short_name' => 'Digital',
    ]);
    $folder = GalleryFolder::create([
        'name' => 'Draft Campaign',
        'slug' => 'draft-campaign',
        'category_id' => $category->id,
    ]);

    $response = $this->actingAs($admin)->put(route('admin.gallery.folders.update', $folder), [
        'name' => 'Draft Campaign',
        'description' => null,
        'category_id' => $category->id,
        'status' => 'Draft',
    ]);

    $response->assertRedirect(route('admin.dashboard', ['tab' => 'galeri']));
    expect($folder->refresh()->status)->toBe('Draft');
    $this->get(route('gallery.index'))->assertDontSee('Draft Campaign');
    $this->get(route('gallery.show', $folder))->assertNotFound();
});

it('deletes a gallery folder and its stored photos', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_admin' => true]);
    $folder = GalleryFolder::create(['name' => 'Folder Dihapus', 'slug' => 'folder-dihapus']);
    $photoPath = 'gallery/'.$folder->id.'/photo.jpg';
    Storage::disk('public')->put($photoPath, 'photo');
    $folder->photos()->create(['path' => $photoPath]);

    $response = $this->actingAs($admin)->delete(route('admin.gallery.folders.destroy', $folder));

    $response->assertRedirect(route('admin.dashboard', ['tab' => 'galeri']));
    expect(GalleryFolder::find($folder->id))->toBeNull();
    Storage::disk('public')->assertMissing($photoPath);
});

it('groups gallery folders by category on the public gallery page', function () {
    $category = Category::create([
        'name' => 'Drone Technology',
        'slug' => 'drone-technology',
        'short_name' => 'Drone',
    ]);
    GalleryFolder::create([
        'name' => 'Pelatihan Drone Lapangan',
        'slug' => 'pelatihan-drone-lapangan',
        'category_id' => $category->id,
    ]);

    $response = $this->get(route('gallery.index'));

    $response->assertOk();
    $response->assertSee('Drone');
    $response->assertSee('Pelatihan Drone Lapangan');
});

it('filters gallery folders by the selected field', function () {
    $droneCategory = Category::create([
        'name' => 'Drone Technology',
        'slug' => 'drone-technology',
        'short_name' => 'Drone',
    ]);
    $aiCategory = Category::create([
        'name' => 'Artificial Intelligence',
        'slug' => 'artificial-intelligence',
        'short_name' => 'AI',
    ]);
    GalleryFolder::create([
        'name' => 'Album Drone',
        'slug' => 'album-drone',
        'category_id' => $droneCategory->id,
    ]);
    GalleryFolder::create([
        'name' => 'Album AI',
        'slug' => 'album-ai',
        'category_id' => $aiCategory->id,
    ]);

    $response = $this->get(route('gallery.index', ['bidang' => 'drone-technology']));

    $response->assertOk()
        ->assertSee('Album Drone')
        ->assertDontSee('Album AI')
        ->assertSee('Semua Bidang');
});

it('creates a lightweight thumbnail when uploading a gallery photo', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_admin' => true]);
    $folder = GalleryFolder::create(['name' => 'Album Ringan', 'slug' => 'album-ringan']);

    $response = $this->actingAs($admin)->post(route('admin.gallery.photos.store'), [
        'gallery_folder_id' => $folder->id,
        'photos' => [UploadedFile::fake()->image('foto.jpg', 1200, 800)],
    ]);

    $photo = $folder->photos()->firstOrFail();

    $response->assertRedirect(route('admin.dashboard', ['tab' => 'galeri', 'folder_id' => $folder->id]));
    expect($photo->thumbnail_path)->not->toBeNull();
    expect($folder->refresh()->status)->toBe('Draft');
    Storage::disk('public')->assertExists($photo->path);
    Storage::disk('public')->assertExists($photo->thumbnail_path);
    $this->get(route('gallery.index'))->assertDontSee('Album Ringan');
});

it('creates a gallery photo from an image URL', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $folder = GalleryFolder::create(['name' => 'Album URL', 'slug' => 'album-url']);

    $response = $this->actingAs($admin)->post(route('admin.gallery.photos.store'), [
        'gallery_folder_id' => $folder->id,
        'photo_urls' => 'https://example.com/gallery-photo.jpg',
    ]);

    $photo = $folder->photos()->firstOrFail();

    $response->assertRedirect(route('admin.dashboard', ['tab' => 'galeri', 'folder_id' => $folder->id]));
    expect($photo->path)->toBeNull()
        ->and($photo->image_url)->toBe('https://example.com/gallery-photo.jpg');
});

it('paginates photos on the public gallery folder page', function () {
    $category = Category::create([
        'name' => 'Photography and Videography',
        'slug' => 'photo-video',
        'short_name' => 'Foto / Video',
    ]);
    $folder = GalleryFolder::create([
        'name' => 'Album Banyak Foto',
        'slug' => 'album-banyak-foto',
        'category_id' => $category->id,
    ]);

    foreach (range(1, 25) as $photoNumber) {
        $folder->photos()->create([
            'path' => 'gallery/'.$folder->id.'/photo-'.$photoNumber.'.jpg',
        ]);
    }

    $response = $this->get(route('gallery.show', $folder));

    $response->assertOk()
        ->assertSee('photo-25.jpg', false)
        ->assertDontSee('/photo-1.jpg', false)
        ->assertSee('page=2', false)
        ->assertSee('galleryLightbox', false)
        ->assertSee('galleryLightboxPrevious', false)
        ->assertSee('galleryLightboxNext', false);
});

it('opens a folder and lists its photos in the admin dashboard', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $folder = GalleryFolder::create(['name' => 'Kegiatan Lapangan', 'slug' => 'kegiatan-lapangan']);
    $photo = $folder->photos()->create(['path' => 'gallery/'.$folder->id.'/photo.jpg', 'caption' => 'Foto pelatihan']);

    $response = $this->actingAs($admin)->get(route('admin.dashboard', [
        'tab' => 'galeri',
        'folder_id' => $folder->id,
    ]));

    $response->assertOk();
    $response->assertSee('Kegiatan Lapangan');
    $response->assertSee('Foto pelatihan');
    $response->assertSee('gallery-photo-select');
    $response->assertSee('value="'.$photo->id.'"', false);
    $response->assertSee('Simpan sebagai draft');
    $response->assertSee('Save &amp; Upload', false);
    $response->assertSee('>Draft<', false);
    $response->assertSee('data-folder-id="'.$folder->id.'"', false);
    $response->assertSee('Hapus foto?');
    $response->assertSee('galleryFolderExitModal');
});

it('replaces a photo image without requiring a caption', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_admin' => true]);
    $folder = GalleryFolder::create(['name' => 'Kegiatan', 'slug' => 'kegiatan']);
    Storage::disk('public')->put('gallery/'.$folder->id.'/old.jpg', 'old image');
    $photo = $folder->photos()->create(['path' => 'gallery/'.$folder->id.'/old.jpg']);

    $response = $this->actingAs($admin)->put(route('admin.gallery.photos.update', $photo), [
        'photo' => UploadedFile::fake()->image('new-photo.jpg'),
    ]);

    $photo->refresh();
    $response->assertRedirect(route('admin.dashboard', ['tab' => 'galeri', 'folder_id' => $folder->id]));
    expect($photo->path)->not->toBe('gallery/'.$folder->id.'/old.jpg');
    expect($folder->refresh()->status)->toBe('Draft');
    Storage::disk('public')->assertMissing('gallery/'.$folder->id.'/old.jpg');
    Storage::disk('public')->assertExists($photo->path);
});

it('updates captions for the selected photos in one folder', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $folder = GalleryFolder::create(['name' => 'Kegiatan', 'slug' => 'kegiatan']);
    $firstPhoto = $folder->photos()->create(['path' => 'gallery/'.$folder->id.'/first.jpg', 'caption' => 'Pertama']);
    $secondPhoto = $folder->photos()->create(['path' => 'gallery/'.$folder->id.'/second.jpg', 'caption' => 'Kedua']);

    $response = $this->actingAs($admin)->put(route('admin.gallery.photos.bulk-update'), [
        'folder_id' => $folder->id,
        'photo_ids' => [$firstPhoto->id, $secondPhoto->id],
        'caption' => 'Keterangan bersama',
    ]);

    $response->assertRedirect(route('admin.dashboard', ['tab' => 'galeri', 'folder_id' => $folder->id]));
    expect($firstPhoto->refresh()->caption)->toBe('Keterangan bersama');
    expect($secondPhoto->refresh()->caption)->toBe('Keterangan bersama');
    expect($folder->refresh()->status)->toBe('Draft');
});

it('deletes only the selected photos from the selected folder', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_admin' => true]);
    $folder = GalleryFolder::create(['name' => 'Kegiatan', 'slug' => 'kegiatan']);
    $selectedPhoto = $folder->photos()->create(['path' => 'gallery/'.$folder->id.'/selected.jpg']);
    $remainingPhoto = $folder->photos()->create(['path' => 'gallery/'.$folder->id.'/remaining.jpg']);
    Storage::disk('public')->put($selectedPhoto->path, 'selected');
    Storage::disk('public')->put($remainingPhoto->path, 'remaining');

    $response = $this->actingAs($admin)->delete(route('admin.gallery.photos.destroy'), [
        'folder_id' => $folder->id,
        'photo_ids' => [$selectedPhoto->id],
    ]);

    $response->assertRedirect(route('admin.dashboard', ['tab' => 'galeri', 'folder_id' => $folder->id]));
    expect(GalleryPhoto::find($selectedPhoto->id))->toBeNull();
    expect(GalleryPhoto::find($remainingPhoto->id))->not->toBeNull();
    expect($folder->refresh()->status)->toBe('Draft');
    Storage::disk('public')->assertMissing($selectedPhoto->path);
    Storage::disk('public')->assertExists($remainingPhoto->path);
});

it('rejects bulk edits that include a photo from another folder', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $folder = GalleryFolder::create(['name' => 'Kegiatan', 'slug' => 'kegiatan']);
    $otherFolder = GalleryFolder::create(['name' => 'Seminar', 'slug' => 'seminar']);
    $otherPhoto = $otherFolder->photos()->create(['path' => 'gallery/'.$otherFolder->id.'/photo.jpg']);

    $response = $this->actingAs($admin)->put(route('admin.gallery.photos.bulk-update'), [
        'folder_id' => $folder->id,
        'photo_ids' => [$otherPhoto->id],
        'caption' => 'Tidak boleh dipindahkan',
    ]);

    $response->assertNotFound();
    expect($otherPhoto->refresh()->caption)->toBeNull();
});

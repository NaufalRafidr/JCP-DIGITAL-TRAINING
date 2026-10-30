<?php

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('creates a program with detail content and a background image', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_admin' => true]);
    $category = Category::create([
        'name' => 'Fotografi',
        'slug' => 'fotografi-course-admin',
    ]);

    $response = $this->actingAs($admin)->post(route('admin.courses.store'), [
        'title' => 'Smartphone Photography',
        'category_id' => $category->id,
        'level' => 'Basic',
        'format' => 'Offline / Studio',
        'facilities' => 'Modul, Alat Praktik, Sertifikat',
        'description' => 'Pelatihan foto dan video menggunakan smartphone.',
        'syllabus' => "Komposisi visual\nTeknik pencahayaan",
        'outcome' => 'Peserta mampu membuat konten visual yang berkualitas.',
        'image' => UploadedFile::fake()->image('smartphone-photography.jpg'),
        'image_position' => 'center bottom',
        'status' => 'Publish',
    ]);

    $course = Course::query()->where('title', 'Smartphone Photography')->firstOrFail();

    $response->assertRedirect(route('admin.dashboard', ['tab' => 'program']));
    Storage::disk('public')->assertExists($course->image);
    expect($course->syllabus)->toBe(['Komposisi visual', 'Teknik pencahayaan'])
        ->and($course->outcome)->toBe('Peserta mampu membuat konten visual yang berkualitas.')
        ->and($course->facilities)->toBe('Modul, Alat Praktik, Sertifikat')
        ->and($course->image_position)->toBe('center bottom');

    $this->get(route('courses.show', $course->slug))
        ->assertOk()
        ->assertSee(Storage::disk('public')->url($course->image), false)
        ->assertSee('Komposisi visual')
        ->assertSee('Peserta mampu membuat konten visual yang berkualitas.')
        ->assertSee('Modul, Alat Praktik, Sertifikat');
});

it('replaces the existing program background image when editing', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_admin' => true]);
    $category = Category::create([
        'name' => 'Teknologi',
        'slug' => 'teknologi-course-admin',
    ]);
    Storage::disk('public')->put('courses/old-background.jpg', 'old image');
    $course = Course::create([
        'category_id' => $category->id,
        'title' => 'Program Lama',
        'slug' => 'program-lama-course-admin',
        'description' => 'Deskripsi lama.',
        'duration' => 'Belum ditentukan',
        'level' => 'Basic',
        'format' => 'Offline',
        'image' => 'courses/old-background.jpg',
        'status' => 'Publish',
    ]);

    $response = $this->actingAs($admin)->put(route('admin.courses.update', $course), [
        'title' => 'Program Baru',
        'category_id' => $category->id,
        'level' => 'Intermediate',
        'format' => 'Hybrid',
        'facilities' => 'Modul dan sertifikat.',
        'description' => 'Deskripsi baru.',
        'syllabus' => 'Materi baru',
        'outcome' => 'Output baru.',
        'image' => UploadedFile::fake()->image('new-background.jpg'),
        'image_position' => 'right center',
        'status' => 'Publish',
    ]);

    $updatedCourse = $course->refresh();

    $response->assertRedirect(route('admin.dashboard', ['tab' => 'program']));
    Storage::disk('public')->assertMissing('courses/old-background.jpg');
    Storage::disk('public')->assertExists($updatedCourse->image);
    expect($updatedCourse->title)->toBe('Program Baru')
        ->and($updatedCourse->syllabus)->toBe(['Materi baru'])
        ->and($updatedCourse->outcome)->toBe('Output baru.')
        ->and($updatedCourse->facilities)->toBe('Modul dan sertifikat.')
        ->and($updatedCourse->image_position)->toBe('right center');
});

it('updates the existing program image position without replacing the image', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_admin' => true]);
    $category = Category::create([
        'name' => 'Drone',
        'slug' => 'drone-course-position',
    ]);
    Storage::disk('public')->put('courses/drone.jpg', 'image');
    $course = Course::create([
        'category_id' => $category->id,
        'title' => 'Program Drone',
        'slug' => 'program-drone-position',
        'description' => 'Deskripsi program drone.',
        'duration' => '2 hari',
        'level' => 'Basic',
        'format' => 'Offline',
        'image' => 'courses/drone.jpg',
        'image_position' => 'center top',
        'status' => 'Publish',
    ]);

    $response = $this->actingAs($admin)->put(route('admin.courses.update', $course), [
        'title' => $course->title,
        'category_id' => $category->id,
        'level' => $course->level,
        'format' => $course->format,
        'description' => $course->description,
        'image_position' => 'left center',
        'status' => $course->status,
    ]);

    $response->assertRedirect(route('admin.dashboard', ['tab' => 'program']));
    expect($course->refresh()->image)->toBe('courses/drone.jpg')
        ->and($course->image_position)->toBe('left center');
    Storage::disk('public')->assertExists('courses/drone.jpg');
});

it('removes the existing program background image when requested', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_admin' => true]);
    $category = Category::create([
        'name' => 'Digital',
        'slug' => 'digital-course-remove-image',
    ]);
    Storage::disk('public')->put('courses/digital.jpg', 'image');
    $course = Course::create([
        'category_id' => $category->id,
        'title' => 'Program Digital',
        'slug' => 'program-digital-remove-image',
        'description' => 'Deskripsi program digital.',
        'duration' => '2 hari',
        'level' => 'Basic',
        'format' => 'Offline',
        'image' => 'courses/digital.jpg',
        'image_position' => '75% 25%',
        'status' => 'Publish',
    ]);

    $response = $this->actingAs($admin)->put(route('admin.courses.update', $course), [
        'title' => $course->title,
        'category_id' => $category->id,
        'level' => $course->level,
        'format' => $course->format,
        'description' => $course->description,
        'remove_image' => '1',
        'status' => $course->status,
    ]);

    $response->assertRedirect(route('admin.dashboard', ['tab' => 'program']));
    expect($course->refresh()->image)->toBeNull()
        ->and($course->image_position)->toBe('50% 50%');
    Storage::disk('public')->assertMissing('courses/digital.jpg');
});

it('deletes a program image immediately from the image endpoint', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_admin' => true]);
    $category = Category::create([
        'name' => 'AI',
        'slug' => 'ai-course-delete-image',
    ]);
    Storage::disk('public')->put('courses/ai.jpg', 'image');
    $course = Course::create([
        'category_id' => $category->id,
        'title' => 'Program AI',
        'slug' => 'program-ai-delete-image',
        'description' => 'Deskripsi program AI.',
        'duration' => '2 hari',
        'level' => 'Basic',
        'format' => 'Offline',
        'image' => 'courses/ai.jpg',
        'image_position' => '25% 75%',
        'status' => 'Publish',
    ]);

    $response = $this->actingAs($admin)
        ->deleteJson(route('admin.courses.image.destroy', $course));

    $response->assertOk()->assertJson(['message' => 'Gambar program berhasil dihapus.']);
    expect($course->refresh()->image)->toBeNull()
        ->and($course->image_position)->toBe('50% 50%');
    Storage::disk('public')->assertMissing('courses/ai.jpg');
});

it('opens the selected program editor inside its dashboard card', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $category = Category::create([
        'name' => 'Fotografi',
        'slug' => 'fotografi-course-editor',
    ]);
    $course = Course::create([
        'category_id' => $category->id,
        'title' => 'Program Editor',
        'slug' => 'program-editor-course-admin',
        'description' => 'Deskripsi program editor.',
        'duration' => '2 hari',
        'level' => 'Basic',
        'format' => 'Offline',
        'facilities' => 'Modul dan sertifikat.',
        'image' => 'courses/program-editor.jpg',
        'image_position' => 'right center',
        'status' => 'Publish',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard', [
        'tab' => 'program',
        'course_id' => $course->id,
    ]));

    $response->assertOk()
        ->assertSee('Pengaturan program')
        ->assertSee('Program Editor')
        ->assertSee('Deskripsi program editor.')
        ->assertSee('Modul dan sertifikat.')
        ->assertSee(Storage::disk('public')->url($course->image), false)
        ->assertSee('value="100% 50%"', false)
        ->assertSee('data-delete-type="Program"', false)
        ->assertSee('data-delete-name="Program Editor"', false)
        ->assertSee('data-editor-url=', false)
        ->assertSee('data-close-editor', false);
});

it('creates a shared training field that is available on the homepage and gallery', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)->post(route('admin.categories.store'), [
        'name' => 'Data Science',
        'short_name' => 'Data',
        'description' => 'Pelatihan pengolahan dan analisis data.',
        '_form_context' => 'program_category',
    ]);

    $category = Category::query()->where('slug', 'data-science')->firstOrFail();

    $response->assertRedirect(route('admin.dashboard', ['tab' => 'program']))
        ->assertSessionHas('success', 'Bidang pelatihan berhasil ditambahkan dan tersedia di Beranda serta Galeri.');
    expect($category->is_gallery)->toBeTrue();

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Data');

    $this->get(route('gallery.index'))
        ->assertOk()
        ->assertSee('Data');
});

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

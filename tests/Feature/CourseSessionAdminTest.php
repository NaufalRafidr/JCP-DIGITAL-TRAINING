<?php

use App\Models\Category;
use App\Models\Course;
use App\Models\CourseSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('opens the selected batch editor inside its dashboard card', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $category = Category::create([
        'name' => 'Teknologi',
        'slug' => 'teknologi-session-editor',
    ]);
    $course = Course::create([
        'category_id' => $category->id,
        'title' => 'Website Development',
        'slug' => 'website-development-session-editor',
        'description' => 'Pelatihan pengembangan website.',
        'duration' => '3 hari',
        'level' => 'Basic',
        'format' => 'Hybrid',
        'status' => 'Publish',
    ]);
    $session = CourseSession::create([
        'course_id' => $course->id,
        'batch_name' => 'Batch Editor 01',
        'start_date' => '2030-03-10',
        'end_date' => '2030-03-12',
        'location' => 'JCP Tech Hub',
        'quota' => 15,
        'price' => 2250000,
        'status' => 'Buka',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard', [
        'tab' => 'jadwal',
        'session_id' => $session->id,
    ]));

    $response->assertOk()
        ->assertSee('Pengaturan jadwal / batch')
        ->assertSee('Batch Editor 01')
        ->assertSee('JCP Tech Hub')
        ->assertSee('2250000')
        ->assertSee('data-delete-type="Jadwal / Batch"', false)
        ->assertSee('data-delete-name="Batch Editor 01"', false)
        ->assertSee('data-editor-url=', false)
        ->assertSee('data-close-editor', false);
});

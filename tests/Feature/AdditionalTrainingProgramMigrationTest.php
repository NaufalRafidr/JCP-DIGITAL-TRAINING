<?php

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('syncs the production catalog and admin dashboard from the same course records', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    createProductionTrainingProgramBaseline();
    $migration = require database_path('migrations/2026_11_16_164007_sync_additional_training_programs_to_courses_table.php');
    $migration->up();

    $response = $this->actingAs($admin)->get(route('admin.dashboard', ['tab' => 'program']));

    $response->assertOk()
        ->assertViewHas('totalCourses', 50)
        ->assertViewHas('categories', fn ($categories): bool => $categories->every(
            fn (Category $category): bool => $category->courses_count === 10,
        ))
        ->assertSee('50 program')
        ->assertSee('Digital Marketing Analytics');
    expect(Course::query()->count())->toBe(50);
});

function createProductionTrainingProgramBaseline(): void
{
    $programCounts = [
        'photo-video' => 3,
        'digital-marketing' => 3,
        'artificial-intelligence' => 4,
        'drone-technology' => 3,
        'information-technology' => 2,
    ];

    foreach ($programCounts as $categorySlug => $programCount) {
        $category = Category::query()->create([
            'name' => str($categorySlug)->replace('-', ' ')->title()->toString(),
            'slug' => $categorySlug,
            'short_name' => str($categorySlug)->before('-')->title()->toString(),
        ]);

        foreach (range(1, $programCount) as $programNumber) {
            Course::query()->create([
                'category_id' => $category->id,
                'title' => "Program Produksi {$categorySlug} {$programNumber}",
                'slug' => "program-produksi-{$categorySlug}-{$programNumber}",
                'description' => 'Program awal yang sudah tersedia di server.',
                'duration' => '2 hari',
                'level' => 'Basic',
                'format' => 'Offline',
                'status' => 'Publish',
            ]);
        }
    }
}

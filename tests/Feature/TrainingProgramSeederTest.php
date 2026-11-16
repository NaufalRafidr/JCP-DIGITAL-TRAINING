<?php

use App\Models\Category;
use App\Models\Course;
use Database\Seeders\TrainingProgramSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('brings every training field to ten programs', function () {
    createBaselineTrainingPrograms();

    $this->seed(TrainingProgramSeeder::class);

    $categories = Category::query()->withCount('courses')->get();

    expect($categories)->toHaveCount(5)
        ->and(Course::query()->count())->toBe(50);

    foreach ($categories as $category) {
        expect($category->courses_count)->toBe(10);
    }
});

it('does not duplicate programs when run more than once', function () {
    createBaselineTrainingPrograms();

    $this->seed(TrainingProgramSeeder::class);
    $this->seed(TrainingProgramSeeder::class);

    expect(Course::query()->count())->toBe(50);
});

function createBaselineTrainingPrograms(): void
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
                'title' => "Program Awal {$categorySlug} {$programNumber}",
                'slug' => "program-awal-{$categorySlug}-{$programNumber}",
                'description' => 'Program awal untuk pengujian seeder.',
                'duration' => '2 hari',
            ]);
        }
    }
}

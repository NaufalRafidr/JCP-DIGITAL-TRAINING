<?php

use App\Models\Category;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('paginates training programs in groups of ten', function () {
    $category = createCourseCatalogForPagination();

    $response = $this->get(route('courses.index', ['kategori' => $category->slug]));

    $response->assertOk()
        ->assertSee('11 program')
        ->assertSee('Program Pelatihan 01')
        ->assertSee('Program Pelatihan 10')
        ->assertDontSee('Program Pelatihan 11')
        ->assertSee(route('courses.index', ['kategori' => $category->slug, 'page' => 2]));
});

it('shows the remaining programs with continued numbering on the next page', function () {
    $category = createCourseCatalogForPagination();

    $response = $this->get(route('courses.index', ['kategori' => $category->slug, 'page' => 2]));

    $response->assertOk()
        ->assertSee('Program Pelatihan 11')
        ->assertDontSee('Program Pelatihan 10')
        ->assertSee('data-program-number="11"', false)
        ->assertSee('Menampilkan 11–11 dari 11 program');
});

function createCourseCatalogForPagination(): Category
{
    $category = Category::query()->create([
        'name' => 'Audit & Manajemen',
        'slug' => 'audit-manajemen-pagination',
        'short_name' => 'Audit',
    ]);

    foreach (range(1, 11) as $programNumber) {
        Course::query()->create([
            'category_id' => $category->id,
            'title' => sprintf('Program Pelatihan %02d', $programNumber),
            'slug' => sprintf('program-pelatihan-%02d', $programNumber),
            'description' => 'Program untuk menguji pagination katalog.',
            'duration' => '2 hari',
            'level' => 'Basic',
            'format' => 'Offline',
            'status' => 'Publish',
        ]);
    }

    return $category;
}

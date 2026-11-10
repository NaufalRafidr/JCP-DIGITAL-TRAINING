<?php

use App\Models\Category;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows only courses from an exactly matched training category', function (string $searchQuery) {
    $artificialIntelligence = Category::create([
        'name' => 'Artificial Intelligence',
        'slug' => 'artificial-intelligence',
        'short_name' => 'AI',
    ]);
    $informationTechnology = Category::create([
        'name' => 'Information Technology',
        'slug' => 'information-technology',
        'short_name' => 'IT',
    ]);
    Course::create([
        'category_id' => $artificialIntelligence->id,
        'title' => 'AI untuk Produktivitas Kerja',
        'slug' => 'ai-untuk-produktivitas-kerja',
        'description' => 'Pelatihan produktivitas dengan AI.',
        'duration' => '2 hari',
    ]);
    Course::create([
        'category_id' => $informationTechnology->id,
        'title' => 'AI Automation untuk Tim IT',
        'slug' => 'ai-automation-untuk-tim-it',
        'description' => 'Topik AI dalam bidang teknologi informasi.',
        'duration' => '2 hari',
    ]);

    $response = $this->get(route('courses.index', ['q' => $searchQuery]));

    $response->assertOk()
        ->assertSee('AI untuk Produktivitas Kerja')
        ->assertDontSee('AI Automation untuk Tim IT');
})->with([
    'short name in lowercase' => 'ai',
    'full category name' => 'Artificial Intelligence',
    'category slug' => 'artificial-intelligence',
]);

it('recognizes individual words from a training category label', function (string $searchQuery) {
    $photoVideo = Category::create([
        'name' => 'Photography & Videography',
        'slug' => 'photo-video',
        'short_name' => 'Foto / Video',
    ]);
    $drone = Category::create([
        'name' => 'Drone Technology',
        'slug' => 'drone-technology',
        'short_name' => 'Drone',
    ]);
    Course::create([
        'category_id' => $photoVideo->id,
        'title' => 'Produksi Konten Kreatif',
        'slug' => 'produksi-konten-kreatif',
        'description' => 'Pelatihan produksi visual.',
        'duration' => '2 hari',
    ]);
    Course::create([
        'category_id' => $drone->id,
        'title' => 'Drone Photography & Videography',
        'slug' => 'drone-photography-videography',
        'description' => 'Teknik pengambilan foto dan video aerial.',
        'duration' => '2 hari',
    ]);

    $response = $this->get(route('courses.index', ['q' => $searchQuery]));

    $response->assertOk()
        ->assertSee('Produksi Konten Kreatif')
        ->assertDontSee('Drone Photography & Videography');
})->with([
    'photo category word' => 'foto',
    'video category word' => 'video',
]);

it('searches course titles but not descriptions when the query is not a category', function () {
    $category = Category::create([
        'name' => 'Information Technology',
        'slug' => 'information-technology',
        'short_name' => 'IT',
    ]);
    Course::create([
        'category_id' => $category->id,
        'title' => 'Workflow Automation',
        'slug' => 'workflow-automation',
        'description' => 'Otomatisasi proses kerja modern.',
        'duration' => '2 hari',
    ]);
    Course::create([
        'category_id' => $category->id,
        'title' => 'Pengolahan Data',
        'slug' => 'pengolahan-data',
        'description' => 'Menggunakan automation untuk pengolahan data.',
        'duration' => '2 hari',
    ]);

    $response = $this->get(route('courses.index', ['q' => 'automation']));

    $response->assertOk()
        ->assertSee('Workflow Automation')
        ->assertDontSee('Pengolahan Data');
});

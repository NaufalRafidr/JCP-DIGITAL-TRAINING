<?php

use App\Models\JcpProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('updates the JCP profile and renders the changes on the about page', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $profile = JcpProfile::query()->firstOrFail();
    $profileStatistics = $profile->only([
        'programs_stat',
        'programs_label',
        'expertise_stat',
        'expertise_label',
        'practice_stat',
        'practice_label',
    ]);
    $payload = [
        'organization_name' => 'JCP Digital Academy',
        'tagline' => 'Belajar keterampilan digital yang relevan untuk industri.',
        'vision_title' => 'Belajar, Berkarya, Bertumbuh',
        'vision_content' => 'Kami menyiapkan peserta melalui praktik yang relevan dan terukur.',
    ];

    $response = $this->actingAs($admin)->put(route('admin.profile.update'), $payload);

    $response->assertRedirect(route('admin.dashboard', ['tab' => 'profil']));
    expect($profile->refresh()->only(array_keys($payload)))->toMatchArray($payload);
    expect($profile->refresh()->only(array_keys($profileStatistics)))->toMatchArray($profileStatistics);
    $this->get(route('tentang'))
        ->assertOk()
        ->assertSee('JCP Digital Academy')
        ->assertSee('Belajar, Berkarya, Bertumbuh')
        ->assertDontSee($profileStatistics['programs_label'])
        ->assertDontSee($profileStatistics['practice_label']);
});

it('does not show profile statistics fields in the admin profile form', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)
        ->get(route('admin.dashboard', ['tab' => 'profil']))
        ->assertOk()
        ->assertDontSee('Statistik profil')
        ->assertDontSee('name="programs_stat"', false)
        ->assertSee('name="travel_package_title"', false)
        ->assertSee('name="travel_package_background_images[]"', false)
        ->assertSee('multiple', false);
});

it('stores and replaces the homepage hero image from the admin profile form', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_admin' => true]);
    $profile = JcpProfile::query()->firstOrFail();
    $profilePayload = [
        'organization_name' => $profile->organization_name,
        'tagline' => $profile->tagline,
        'vision_title' => $profile->vision_title,
        'vision_content' => $profile->vision_content,
    ];

    $createResponse = $this->actingAs($admin)->put(route('admin.profile.update'), [
        ...$profilePayload,
        'hero_image' => UploadedFile::fake()->image('homepage-hero.jpg'),
    ]);

    $firstImagePath = $profile->refresh()->hero_image;

    $createResponse->assertRedirect(route('admin.dashboard', ['tab' => 'profil']));
    Storage::disk('public')->assertExists($firstImagePath);

    $updateResponse = $this->actingAs($admin)->put(route('admin.profile.update'), [
        ...$profilePayload,
        'hero_image' => UploadedFile::fake()->image('homepage-hero-new.jpg'),
    ]);

    $updatedProfile = $profile->refresh();

    $updateResponse->assertRedirect(route('admin.dashboard', ['tab' => 'profil']));
    Storage::disk('public')->assertMissing($firstImagePath);
    Storage::disk('public')->assertExists($updatedProfile->hero_image);
    $this->get(route('home'))
        ->assertOk()
        ->assertSee(Storage::disk('public')->url($updatedProfile->hero_image), false);
});

it('updates travel package content and shows multiple rotating backgrounds on the homepage', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_admin' => true]);
    $profile = JcpProfile::query()->firstOrFail();

    $response = $this->actingAs($admin)->put(route('admin.profile.update'), [
        'organization_name' => $profile->organization_name,
        'tagline' => $profile->tagline,
        'vision_title' => $profile->vision_title,
        'vision_content' => $profile->vision_content,
        'travel_package_badge' => 'Belajar Sambil Berwisata',
        'travel_package_title' => 'Paket outing kreatif untuk tim Anda',
        'travel_package_description' => 'Pelatihan praktis yang dipadukan dengan perjalanan berkesan.',
        'travel_package_features' => "Workshop pilihan\nItinerary fleksibel\nPendamping profesional",
        'travel_package_button_text' => 'Hubungi tim kami',
        'travel_package_button_url' => 'https://example.com/paket-wisata',
        'travel_package_background_images' => [
            UploadedFile::fake()->image('wisata-satu.jpg'),
            UploadedFile::fake()->image('wisata-dua.jpg'),
        ],
    ]);

    $updatedProfile = $profile->refresh();
    $backgroundImages = $updatedProfile->travel_package_background_images;

    $response->assertRedirect(route('admin.dashboard', ['tab' => 'profil']));
    expect($updatedProfile->travel_package_features)->toBe([
        'Workshop pilihan',
        'Itinerary fleksibel',
        'Pendamping profesional',
    ])->and($backgroundImages)->toHaveCount(2);
    Storage::disk('public')->assertExists($backgroundImages);

    $homeResponse = $this->get(route('home'));

    $homeResponse->assertOk()
        ->assertSee('Belajar Sambil Berwisata')
        ->assertSee('Paket outing kreatif untuk tim Anda')
        ->assertSee('Hubungi tim kami')
        ->assertSee('https://example.com/paket-wisata', false)
        ->assertSee(Storage::disk('public')->url($backgroundImages[0]), false)
        ->assertSee(Storage::disk('public')->url($backgroundImages[1]), false)
        ->assertSee('data-travel-package-carousel', false);
    expect(substr_count($homeResponse->getContent(), 'data-travel-package-slide'))->toBe(2);
});

it('removes only selected travel package backgrounds', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_admin' => true]);
    $profile = JcpProfile::query()->firstOrFail();
    $firstBackground = 'jcp/travel-package/first.jpg';
    $secondBackground = 'jcp/travel-package/second.jpg';
    Storage::disk('public')->put($firstBackground, 'first');
    Storage::disk('public')->put($secondBackground, 'second');
    $profile->update([
        'travel_package_background_images' => [$firstBackground, $secondBackground],
    ]);

    $response = $this->actingAs($admin)->put(route('admin.profile.update'), [
        'organization_name' => $profile->organization_name,
        'tagline' => $profile->tagline,
        'vision_title' => $profile->vision_title,
        'vision_content' => $profile->vision_content,
        'remove_travel_package_background_images' => [$firstBackground],
    ]);

    $response->assertRedirect(route('admin.dashboard', ['tab' => 'profil']));
    expect($profile->refresh()->travel_package_background_images)->toBe([$secondBackground]);
    Storage::disk('public')->assertMissing($firstBackground);
    Storage::disk('public')->assertExists($secondBackground);
});

it('forbids non-admin users from updating the JCP profile', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $response = $this->actingAs($user)->put(route('admin.profile.update'), []);

    $response->assertForbidden();
});

it('escapes user-provided JCP profile content on public pages', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $dangerousText = '<script>alert("xss")</script>';

    $this->actingAs($admin)->put(route('admin.profile.update'), [
        'organization_name' => $dangerousText,
        'tagline' => 'Deskripsi aman.',
        'travel_package_title' => $dangerousText,
        'vision_title' => 'Judul aman.',
        'vision_content' => 'Isi aman.',
    ]);

    $this->get(route('tentang'))
        ->assertSee('&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;', false)
        ->assertDontSee($dangerousText, false);
    $this->get(route('home'))
        ->assertSee('&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;', false)
        ->assertDontSee($dangerousText, false);
});

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

it('separates homepage content from the JCP profile form', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    JcpProfile::query()->firstOrFail()->update([
        'travel_package_background_images' => ['https://example.com/background.jpg'],
    ]);

    $this->actingAs($admin)
        ->get(route('admin.dashboard', ['tab' => 'profil']))
        ->assertOk()
        ->assertDontSee('Statistik profil')
        ->assertDontSee('name="programs_stat"', false)
        ->assertDontSee('name="hero_image"', false)
        ->assertDontSee('name="travel_package_title"', false);

    $this->actingAs($admin)
        ->get(route('admin.dashboard', ['tab' => 'beranda']))
        ->assertOk()
        ->assertSeeInOrder(['Beranda', 'Artikel'])
        ->assertSee('name="hero_image"', false)
        ->assertSee('name="travel_package_title"', false)
        ->assertSee('name="travel_package_background_images[]"', false)
        ->assertSee('name="travel_package_background_folder[]"', false)
        ->assertSee('name="travel_package_background_urls"', false)
        ->assertSee('data-background-delete-selected', false)
        ->assertSee('data-background-editor-canvas', false)
        ->assertDontSee('name="travel_package_features"', false)
        ->assertDontSee('name="travel_package_button_url"', false)
        ->assertSee('multiple', false);
});

it('stores and replaces the homepage hero image from the homepage form', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_admin' => true]);
    $profile = JcpProfile::query()->firstOrFail();
    $homepagePayload = [
        'travel_package_badge' => 'Paket pelatihan + wisata',
        'travel_package_title' => 'Pelatihan bersama JCP',
        'travel_package_description' => 'Pelatihan dan perjalanan yang berkesan.',
        'travel_package_button_text' => 'Konsultasikan paket',
    ];

    $createResponse = $this->actingAs($admin)->put(route('admin.homepage.update'), [
        ...$homepagePayload,
        'hero_image' => UploadedFile::fake()->image('homepage-hero.jpg'),
    ]);

    $firstImagePath = $profile->refresh()->hero_image;

    $createResponse->assertRedirect(route('admin.dashboard', ['tab' => 'beranda']));
    Storage::disk('public')->assertExists($firstImagePath);

    $updateResponse = $this->actingAs($admin)->put(route('admin.homepage.update'), [
        ...$homepagePayload,
        'hero_image' => UploadedFile::fake()->image('homepage-hero-new.jpg'),
    ]);

    $updatedProfile = $profile->refresh();

    $updateResponse->assertRedirect(route('admin.dashboard', ['tab' => 'beranda']));
    Storage::disk('public')->assertMissing($firstImagePath);
    Storage::disk('public')->assertExists($updatedProfile->hero_image);
    $this->get(route('home'))
        ->assertOk()
        ->assertSee(Storage::disk('public')->url($updatedProfile->hero_image), false);
});

it('updates homepage content from files folders and URLs without showing the default background', function () {
    Storage::fake('public');
    config(['services.whatsapp.admin_number' => '628111222333']);
    $admin = User::factory()->create(['is_admin' => true]);
    $profile = JcpProfile::query()->firstOrFail();

    $response = $this->actingAs($admin)->put(route('admin.homepage.update'), [
        'travel_package_badge' => 'Belajar Sambil Berwisata',
        'travel_package_title' => 'Paket outing kreatif untuk tim Anda',
        'travel_package_description' => 'Pelatihan praktis yang dipadukan dengan perjalanan berkesan.',
        'travel_package_button_text' => 'Hubungi tim kami',
        'travel_package_background_images' => [
            UploadedFile::fake()->image('wisata-satu.jpg'),
        ],
        'travel_package_background_folder' => [
            UploadedFile::fake()->image('wisata-dua.jpg'),
        ],
        'travel_package_background_urls' => 'https://example.com/wisata-tiga.jpg',
    ]);

    $updatedProfile = $profile->refresh();
    $backgroundImages = $updatedProfile->travel_package_background_images;

    $response->assertRedirect(route('admin.dashboard', ['tab' => 'beranda']));
    expect($updatedProfile->travel_package_features)->toBe([])
        ->and($updatedProfile->travel_package_button_url)->toBeNull()
        ->and($backgroundImages)->toHaveCount(3)
        ->and($backgroundImages[2])->toBe('https://example.com/wisata-tiga.jpg');
    Storage::disk('public')->assertExists(array_slice($backgroundImages, 0, 2));

    $homeResponse = $this->get(route('home'));

    $homeResponse->assertOk()
        ->assertSee('Belajar Sambil Berwisata')
        ->assertSee('Paket outing kreatif untuk tim Anda')
        ->assertSee('Hubungi tim kami')
        ->assertSee('https://wa.me/628111222333', false)
        ->assertSee(Storage::disk('public')->url($backgroundImages[0]), false)
        ->assertSee(Storage::disk('public')->url($backgroundImages[1]), false)
        ->assertSee('https://example.com/wisata-tiga.jpg', false)
        ->assertDontSee('images/training-travel-package-banner.png', false)
        ->assertSee('data-travel-package-carousel', false);
    expect(substr_count($homeResponse->getContent(), 'data-travel-package-slide'))->toBe(3);
});

it('rejects more than eight combined homepage backgrounds without storing new files', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_admin' => true]);
    $profile = JcpProfile::query()->firstOrFail();
    $existingBackgrounds = collect(range(1, 7))
        ->map(fn (int $number): string => 'jcp/travel-package/existing-'.$number.'.jpg')
        ->all();
    $profile->update(['travel_package_background_images' => $existingBackgrounds]);

    $response = $this->actingAs($admin)->from(route('admin.dashboard', ['tab' => 'beranda']))
        ->put(route('admin.homepage.update'), [
            'travel_package_badge' => 'Paket pelatihan + wisata',
            'travel_package_title' => 'Pelatihan bersama JCP',
            'travel_package_description' => 'Pelatihan dan perjalanan yang berkesan.',
            'travel_package_button_text' => 'Konsultasikan paket',
            'travel_package_background_images' => [
                UploadedFile::fake()->image('tambahan-satu.jpg'),
                UploadedFile::fake()->image('tambahan-dua.jpg'),
            ],
        ]);

    $response->assertRedirect(route('admin.dashboard', ['tab' => 'beranda']))
        ->assertSessionHasErrors([
            'travel_package_background_images' => 'Maksimal delapan gambar background dapat disimpan.',
        ]);
    expect($profile->refresh()->travel_package_background_images)->toBe($existingBackgrounds);
    expect(Storage::disk('public')->allFiles('jcp/travel-package'))->toBe([]);
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

    $response = $this->actingAs($admin)->put(route('admin.homepage.update'), [
        'travel_package_badge' => 'Paket pelatihan + wisata',
        'travel_package_title' => 'Pelatihan bersama JCP',
        'travel_package_description' => 'Pelatihan dan perjalanan yang berkesan.',
        'travel_package_button_text' => 'Konsultasikan paket',
        'remove_travel_package_background_images' => [$firstBackground],
    ]);

    $response->assertRedirect(route('admin.dashboard', ['tab' => 'beranda']));
    expect($profile->refresh()->travel_package_background_images)->toBe([$secondBackground]);
    Storage::disk('public')->assertMissing($firstBackground);
    Storage::disk('public')->assertExists($secondBackground);
});

it('deletes selected homepage backgrounds immediately and removes their settings', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_admin' => true]);
    $profile = JcpProfile::query()->firstOrFail();
    $firstBackground = 'jcp/travel-package/first.jpg';
    $secondBackground = 'jcp/travel-package/second.jpg';
    $remoteBackground = 'https://example.com/remote.jpg';
    Storage::disk('public')->put($firstBackground, 'first');
    Storage::disk('public')->put($secondBackground, 'second');
    $profile->update([
        'travel_package_background_images' => [$firstBackground, $secondBackground, $remoteBackground],
        'travel_package_background_settings' => [
            JcpProfile::travelPackageBackgroundSettingsKey($firstBackground) => [
                'position_x' => 20,
                'position_y' => 30,
                'scale' => 120,
            ],
            JcpProfile::travelPackageBackgroundSettingsKey($secondBackground) => [
                'position_x' => 60,
                'position_y' => 70,
                'scale' => 140,
            ],
            JcpProfile::travelPackageBackgroundSettingsKey($remoteBackground) => [
                'position_x' => 40,
                'position_y' => 50,
                'scale' => 110,
            ],
        ],
    ]);

    $response = $this->actingAs($admin)->deleteJson(route('admin.homepage.backgrounds.destroy'), [
        'backgrounds' => [$firstBackground, $remoteBackground],
    ]);

    $response->assertOk()
        ->assertJson([
            'deleted' => [$firstBackground, $remoteBackground],
            'remaining_count' => 1,
        ]);
    $updatedProfile = $profile->refresh();
    expect($updatedProfile->travel_package_background_images)->toBe([$secondBackground])
        ->and($updatedProfile->travel_package_background_settings)->toHaveKey(
            JcpProfile::travelPackageBackgroundSettingsKey($secondBackground),
        )
        ->and($updatedProfile->travel_package_background_settings)->not->toHaveKey(
            JcpProfile::travelPackageBackgroundSettingsKey($firstBackground),
        );
    Storage::disk('public')->assertMissing($firstBackground);
    Storage::disk('public')->assertExists($secondBackground);
});

it('updates homepage background position and scale and renders them on the banner', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $profile = JcpProfile::query()->firstOrFail();
    $background = 'https://example.com/banner.jpg';
    $profile->update(['travel_package_background_images' => [$background]]);

    $response = $this->actingAs($admin)->putJson(route('admin.homepage.backgrounds.update'), [
        'background' => $background,
        'position_x' => 20,
        'position_y' => 70,
        'scale' => 135,
    ]);

    $response->assertOk()
        ->assertJsonPath('settings.position_x', 20)
        ->assertJsonPath('settings.position_y', 70)
        ->assertJsonPath('settings.scale', 135);
    expect($profile->refresh()->travelPackageBackgroundSettings($background))->toBe([
        'position_x' => 20,
        'position_y' => 70,
        'scale' => 135,
    ]);
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('object-position: 20% 70%; transform: scale(1.35);', false);
});

it('rejects homepage background settings for an unknown image', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)->putJson(route('admin.homepage.backgrounds.update'), [
        'background' => 'jcp/travel-package/not-owned.jpg',
        'position_x' => 50,
        'position_y' => 50,
        'scale' => 100,
    ])->assertNotFound();
});

it('forbids non-admin users from updating the JCP profile', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $response = $this->actingAs($user)->put(route('admin.profile.update'), []);
    $homepageResponse = $this->actingAs($user)->put(route('admin.homepage.update'), []);
    $homepageBackgroundUpdateResponse = $this->actingAs($user)->putJson(route('admin.homepage.backgrounds.update'), []);
    $homepageBackgroundDeleteResponse = $this->actingAs($user)->deleteJson(route('admin.homepage.backgrounds.destroy'), []);

    $response->assertForbidden();
    $homepageResponse->assertForbidden();
    $homepageBackgroundUpdateResponse->assertForbidden();
    $homepageBackgroundDeleteResponse->assertForbidden();
});

it('escapes user-provided JCP profile content on public pages', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $dangerousText = '<script>alert("xss")</script>';

    $this->actingAs($admin)->put(route('admin.profile.update'), [
        'organization_name' => $dangerousText,
        'tagline' => 'Deskripsi aman.',
        'vision_title' => 'Judul aman.',
        'vision_content' => 'Isi aman.',
    ]);

    $this->actingAs($admin)->put(route('admin.homepage.update'), [
        'travel_package_badge' => 'Paket pelatihan + wisata',
        'travel_package_title' => $dangerousText,
        'travel_package_description' => 'Deskripsi aman.',
        'travel_package_button_text' => 'Konsultasikan paket',
    ]);

    $this->get(route('tentang'))
        ->assertSee('&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;', false)
        ->assertDontSee($dangerousText, false);
    $this->get(route('home'))
        ->assertSee('&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;', false)
        ->assertDontSee($dangerousText, false);
});

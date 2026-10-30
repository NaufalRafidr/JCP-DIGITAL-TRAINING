<?php

use App\Models\JcpProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('updates the JCP profile and renders the changes on the about page', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $profile = JcpProfile::query()->firstOrFail();
    $payload = [
        'organization_name' => 'JCP Digital Academy',
        'tagline' => 'Belajar keterampilan digital yang relevan untuk industri.',
        'vision_title' => 'Belajar, Berkarya, Bertumbuh',
        'vision_content' => 'Kami menyiapkan peserta melalui praktik yang relevan dan terukur.',
        'programs_stat' => '20+',
        'programs_label' => 'Program Aktif',
        'expertise_stat' => '6',
        'expertise_label' => 'Bidang Keahlian',
        'practice_stat' => '95%',
        'practice_label' => 'Praktik Berbasis Proyek',
    ];

    $response = $this->actingAs($admin)->put(route('admin.profile.update'), $payload);

    $response->assertRedirect(route('admin.dashboard', ['tab' => 'profil']));
    expect($profile->refresh()->only(array_keys($payload)))->toMatchArray($payload);
    $this->get(route('tentang'))
        ->assertOk()
        ->assertSee('JCP Digital Academy')
        ->assertSee('Belajar, Berkarya, Bertumbuh')
        ->assertSee('20+')
        ->assertSee('Praktik Berbasis Proyek');
});

it('forbids non-admin users from updating the JCP profile', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $response = $this->actingAs($user)->put(route('admin.profile.update'), []);

    $response->assertForbidden();
});

it('escapes user-provided JCP profile content on the about page', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $dangerousText = '<script>alert("xss")</script>';

    $this->actingAs($admin)->put(route('admin.profile.update'), [
        'organization_name' => $dangerousText,
        'tagline' => 'Deskripsi aman.',
        'vision_title' => 'Judul aman.',
        'vision_content' => 'Isi aman.',
        'programs_stat' => '15+',
        'programs_label' => 'Program Terstandar',
        'expertise_stat' => '5',
        'expertise_label' => 'Bidang Keahlian',
        'practice_stat' => '100%',
        'practice_label' => 'Praktik Lapangan & Studi Kasus',
    ]);

    $this->get(route('tentang'))
        ->assertSee('&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;', false)
        ->assertDontSee($dangerousText, false);
});

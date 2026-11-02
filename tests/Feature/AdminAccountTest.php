<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('renders password reset settings without a username field', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)
        ->get(route('admin.dashboard', ['tab' => 'pengaturan']))
        ->assertOk()
        ->assertSee('Reset password untuk akun admin yang sedang digunakan.')
        ->assertDontSee('name="username"', false)
        ->assertSee('name="password"', false)
        ->assertSee('name="password_confirmation"', false)
        ->assertSee('name="current_password"', false);
});

it('resets the admin password without changing the username', function () {
    $admin = User::factory()->create([
        'is_admin' => true,
        'username' => 'admin-lama',
        'password' => 'password-lama',
    ]);

    $response = $this->actingAs($admin)->put(route('admin.account.update'), [
        'current_password' => 'password-lama',
        'password' => 'password-baru',
        'password_confirmation' => 'password-baru',
    ]);

    $response->assertRedirect(route('admin.dashboard', ['tab' => 'pengaturan']))
        ->assertSessionHas('success', 'Password admin berhasil direset.');
    expect($admin->refresh()->username)->toBe('admin-lama')
        ->and(Hash::check('password-baru', $admin->password))->toBeTrue();
});

it('rejects a password reset when the current password is incorrect', function () {
    $admin = User::factory()->create([
        'is_admin' => true,
        'password' => 'password-lama',
    ]);

    $response = $this->actingAs($admin)->put(route('admin.account.update'), [
        'current_password' => 'password-salah',
        'password' => 'password-baru',
        'password_confirmation' => 'password-baru',
    ]);

    $response->assertSessionHasErrors('current_password');
    expect(Hash::check('password-lama', $admin->refresh()->password))->toBeTrue();
});

<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders a password-only admin login form', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertSee('name="password"', false)
        ->assertDontSee('name="username"', false)
        ->assertSee('Masukkan password untuk mengakses dashboard admin.');
});

it('authenticates an admin with only the password', function () {
    $admin = User::factory()->create([
        'is_admin' => true,
        'password' => 'password',
    ]);

    $response = $this->post(route('login.store'), [
        'password' => 'password',
    ]);

    $response->assertRedirect(route('admin.dashboard'));
    $this->assertAuthenticatedAs($admin);
});

it('rejects an incorrect admin password', function () {
    User::factory()->create([
        'is_admin' => true,
        'password' => 'password',
    ]);

    $response = $this->from(route('login'))->post(route('login.store'), [
        'password' => 'wrong-password',
    ]);

    $response->assertRedirect(route('login'))
        ->assertSessionHasErrors('password');
    $this->assertGuest();
});

it('does not authenticate a non-admin with the same password', function () {
    User::factory()->create([
        'is_admin' => false,
        'password' => 'password',
    ]);

    $response = $this->from(route('login'))->post(route('login.store'), [
        'password' => 'password',
    ]);

    $response->assertRedirect(route('login'))
        ->assertSessionHasErrors('password');
    $this->assertGuest();
});

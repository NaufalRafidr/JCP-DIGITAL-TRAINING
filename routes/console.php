<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('admin:create {name} {username}', function (string $name, string $username) {
    $username = Str::lower(trim($username));
    if (! preg_match('/^[a-z0-9_-]{3,40}$/', $username)) {
        $this->error('Username harus 3-40 karakter, hanya huruf, angka, garis bawah, atau tanda hubung.');

        return 1;
    }

    $password = $this->secret('Kata sandi admin');
    $confirmation = $this->secret('Ulangi kata sandi');

    if (strlen($password) < 5 || $password !== $confirmation) {
        $this->error('Kata sandi harus sama dan minimal 5 karakter.');

        return 1;
    }

    $email = $username.'@admin.local';
    $existingUser = User::where('username', $username)->first();
    $emailOwner = User::where('email', $email)->first();
    if ($emailOwner && $emailOwner->id !== $existingUser?->id) {
        $this->error('Username ini berbenturan dengan email akun yang sudah ada. Pilih username lain.');

        return 1;
    }

    User::updateOrCreate(
        ['username' => $username],
        ['name' => $name, 'email' => $email, 'password' => Hash::make($password), 'is_admin' => true],
    );

    $this->info("Akun admin dengan username {$username} siap digunakan.");

    return 0;
})->purpose('Buat atau naikkan akun menjadi admin');

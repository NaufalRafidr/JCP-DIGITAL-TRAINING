<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $username = strtolower(trim(config('admin.default_username')));

        User::firstOrCreate(
            ['username' => $username],
            [
                'name' => 'Administrator',
                'email' => $username.'@admin.local',
                'password' => config('admin.default_password'),
                'is_admin' => true,
            ],
        );
    }
}

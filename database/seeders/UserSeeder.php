<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin Account - credentials from .env for production security
        // Admin is auto-verified since they're seeded directly
        User::create([
            'name' => env('ADMIN_NAME', 'Administrator'),
            'email' => env('ADMIN_EMAIL', 'admin@jurnal.com'),
            'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
            'role' => 'admin',
            'niy' => env('ADMIN_NIY', '1234567890'),
            'phone' => env('ADMIN_PHONE', '08123456789'),
            'email_verified_at' => now(),
        ]);

        // Guru Demo Accounts - auto-verified for development
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'guru@jurnal.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'niy' => '1987654321',
            'phone' => '08198765432',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@jurnal.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'niy' => '1122334455',
            'phone' => '08112233445',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Ahmad Dahlan',
            'email' => 'ahmad@jurnal.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'niy' => '5544332211',
            'phone' => '08155443322',
            'email_verified_at' => now(),
        ]);
    }
}
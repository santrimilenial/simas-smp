<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class BendaharaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'bendahara@simas.com'],
            [
                'name' => 'Bendahara Sekolah',
                'email' => 'bendahara@simas.com',
                'password' => Hash::make('password'),
                'role' => 'bendahara',
                'niy' => 'BDH001',
            ]
        );

        $this->command->info('Bendahara user created!');
        $this->command->info('Email: bendahara@simas.com');
        $this->command->info('Password: password');
    }
}

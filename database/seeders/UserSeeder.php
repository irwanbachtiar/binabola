<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin account
        User::create([
            'name' => 'Admin Binabola',
            'email' => 'admin@binabola.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role' => 'admin',
        ]);

        // Create demo pelatih account (admin role)
        User::create([
            'name' => 'Pelatih Demo',
            'email' => 'pelatih@binabola.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role' => 'admin',
        ]);

        // Create orangtua accounts
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role' => 'orangtua',
        ]);

        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role' => 'orangtua',
        ]);
    }
}

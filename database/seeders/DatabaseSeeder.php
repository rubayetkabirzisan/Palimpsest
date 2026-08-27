<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@palimpsest.dev',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Compliance user
        User::create([
            'name' => 'Compliance Officer',
            'email' => 'compliance@palimpsest.dev',
            'password' => bcrypt('password'),
            'role' => 'compliance',
            'email_verified_at' => now(),
        ]);

        // Regular user
        User::create([
            'name' => 'Regular User',
            'email' => 'user@palimpsest.dev',
            'password' => bcrypt('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);
    }
}

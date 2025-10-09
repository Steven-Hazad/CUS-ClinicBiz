<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@medsync.com',
            'password' => bcrypt('admin'), // Change this in production!
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
    }
}
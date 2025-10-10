<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class ReceptionistUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Receptionist User',
            'email' => 'receptionist@medsync.com',
            'password' => bcrypt('password123'),
            'role' => 'receptionist',
            'email_verified_at' => now(),
        ]);
    }
}

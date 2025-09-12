<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        
        for ($i = 1; $i <= 15; $i++) {
            User::factory()->create([
                'name' => 'Teacher ' . $i,
                'email' => 'teacher' . $i . '@example.com',
                'role' => 'teacher',
            ]);
        }

        
        for ($i = 1; $i <= 100; $i++) {
            User::factory()->create([
                'name' => 'Student ' . $i,
                'email' => 'student' . $i . '@example.com',
                'role' => 'student',
            ]);
        }
    }
}
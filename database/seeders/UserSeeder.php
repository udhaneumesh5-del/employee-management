<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
                'role' => 'Admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'hr@example.com'],
            [
                'name' => 'HR User',
                'password' => Hash::make('password123'),
                'role' => 'HR',
            ]
        );
    }
}
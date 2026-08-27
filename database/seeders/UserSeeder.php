<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'Admin',
            'status' => 'Active',
            'employee_id' => null,
        ]);

        // HR
        User::create([
            'name' => 'HR User',
            'email' => 'hr@example.com',
            'password' => Hash::make('password123'),
            'role' => 'HR',
            'status' => 'Active',
            'employee_id' => null,
        ]);

        // Manager
        User::create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => Hash::make('password123'),
            'role' => 'Manager',
            'status' => 'Active',
            'employee_id' => null,
        ]);

        // Employee (if employee record exists)
        $employee = \App\Models\Employee::first();
        User::create([
            'name' => 'Employee User',
            'email' => 'employee@example.com',
            'password' => Hash::make('password123'),
            'role' => 'Employee',
            'status' => 'Active',
            'employee_id' => $employee ? $employee->id : null,
        ]);
    }
}
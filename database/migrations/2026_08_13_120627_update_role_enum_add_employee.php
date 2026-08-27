<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add 'Employee' to enum
        DB::statement("
            ALTER TABLE users
            MODIFY COLUMN role ENUM('Admin', 'HR', 'Manager', 'Employee')
            NOT NULL DEFAULT 'Employee'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE users
            MODIFY COLUMN role ENUM('Admin', 'HR', 'Manager')
            NOT NULL DEFAULT 'HR'
        ");
    }
};
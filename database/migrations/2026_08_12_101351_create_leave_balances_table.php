<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('leave_type_id')->constrained('leave_types');
            $table->year('year');
            $table->integer('allocated_days')->default(0);
            $table->integer('used_days')->default(0);
            $table->integer('remaining_days')->default(0);
            $table->timestamps();
            
            // Unique constraint
            $table->unique(['employee_id', 'leave_type_id', 'year']);
            
            // Indexes
            $table->index('employee_id');
            $table->index('year');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_balances');
    }
};
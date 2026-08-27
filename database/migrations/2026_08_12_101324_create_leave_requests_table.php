<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('leave_type_id')->constrained('leave_types');
            $table->date('from_date');
            $table->date('to_date');
            $table->integer('total_days');
            $table->text('reason');
            $table->string('document')->nullable();
            $table->enum('status', [
                'pending_manager',
                'manager_approved',
                'manager_rejected',
                'pending_hr',
                'hr_rejected',
                'approved',
                'cancelled'
            ])->default('pending_manager');
            $table->text('manager_comment')->nullable();
            $table->text('hr_comment')->nullable();
            $table->timestamp('manager_approved_at')->nullable();
            $table->timestamp('hr_approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for performance
            $table->index('employee_id');
            $table->index('status');
            $table->index('from_date');
            $table->index('to_date');
            $table->index(['employee_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
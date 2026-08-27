<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            
            // Check if column exists before adding
            if (!Schema::hasColumn('leave_requests', 'manager_id')) {
                $table->foreignId('manager_id')->nullable()->after('employee_id')->constrained('employees')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('leave_requests', 'manager_status')) {
                $table->enum('manager_status', ['pending', 'approved', 'rejected'])->default('pending')->after('manager_id');
            }
            
            if (!Schema::hasColumn('leave_requests', 'manager_approved_at')) {
                $table->timestamp('manager_approved_at')->nullable()->after('manager_status');
            }
            
            if (!Schema::hasColumn('leave_requests', 'manager_rejected_at')) {
                $table->timestamp('manager_rejected_at')->nullable()->after('manager_approved_at');
            }
            
            if (!Schema::hasColumn('leave_requests', 'manager_remarks')) {
                $table->text('manager_remarks')->nullable()->after('manager_rejected_at');
            }
            
            if (!Schema::hasColumn('leave_requests', 'hr_status')) {
                $table->enum('hr_status', ['pending', 'approved', 'rejected'])->default('pending')->after('manager_remarks');
            }
            
            if (!Schema::hasColumn('leave_requests', 'hr_approved_at')) {
                $table->timestamp('hr_approved_at')->nullable()->after('hr_status');
            }
            
            if (!Schema::hasColumn('leave_requests', 'hr_rejected_at')) {
                $table->timestamp('hr_rejected_at')->nullable()->after('hr_approved_at');
            }
            
            if (!Schema::hasColumn('leave_requests', 'hr_remarks')) {
                $table->text('hr_remarks')->nullable()->after('hr_rejected_at');
            }
            
            if (!Schema::hasColumn('leave_requests', 'admin_status')) {
                $table->enum('admin_status', ['pending', 'approved', 'rejected'])->default('pending')->after('hr_remarks');
            }
            
            if (!Schema::hasColumn('leave_requests', 'admin_approved_at')) {
                $table->timestamp('admin_approved_at')->nullable()->after('admin_status');
            }
            
            if (!Schema::hasColumn('leave_requests', 'admin_rejected_at')) {
                $table->timestamp('admin_rejected_at')->nullable()->after('admin_approved_at');
            }
            
            if (!Schema::hasColumn('leave_requests', 'admin_remarks')) {
                $table->text('admin_remarks')->nullable()->after('admin_rejected_at');
            }
            
            if (!Schema::hasColumn('leave_requests', 'final_status')) {
                $table->enum('final_status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending')->after('admin_remarks');
            }
        });
    }

    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
            $table->dropColumn([
                'manager_id',
                'manager_status',
                'manager_approved_at',
                'manager_rejected_at',
                'manager_remarks',
                'hr_status',
                'hr_approved_at',
                'hr_rejected_at',
                'hr_remarks',
                'admin_status',
                'admin_approved_at',
                'admin_rejected_at',
                'admin_remarks',
                'final_status'
            ]);
        });
    }
};
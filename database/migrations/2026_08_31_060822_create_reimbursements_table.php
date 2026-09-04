<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reimbursements', function (Blueprint $table) {
            $table->id();
            
            // Requester Info
            $table->foreignId('requester_id')->constrained('employees');
            $table->enum('requester_role', ['Employee', 'Manager', 'HR']);
            
            // Expense Info
            $table->foreignId('expense_type_id')->constrained('expense_types');
            $table->date('expense_date');
            $table->decimal('amount', 10, 2);
            $table->text('description');
            $table->string('receipt')->nullable();
            
            // Policy Info at submission
            $table->foreignId('policy_id')->nullable()->constrained('reimbursement_policies');
            $table->decimal('policy_limit_at_submission', 10, 2)->nullable();
            $table->string('policy_name_at_submission')->nullable();
            $table->boolean('receipt_required_at_submission')->default(true);
            
            // Status & Flow
            $table->enum('status', [
                'draft',
                'pending_manager',
                'pending_hr',
                'pending_admin',
                'manager_approved',
                'manager_rejected',
                'hr_approved',
                'hr_rejected',
                'admin_approved',
                'admin_rejected',
                'approved_final',
                'paid',
                'cancelled'
            ])->default('draft');
            
            $table->string('current_approval_level')->nullable();
            
            // Manager Info
            $table->foreignId('manager_id')->nullable()->constrained('employees');
            $table->timestamp('manager_approved_at')->nullable();
            $table->text('manager_remarks')->nullable();
            
            // HR Info
            $table->foreignId('hr_id')->nullable()->constrained('employees');
            $table->timestamp('hr_approved_at')->nullable();
            $table->text('hr_remarks')->nullable();
            
            // Admin Info
            $table->foreignId('admin_id')->nullable()->constrained('employees');
            $table->timestamp('admin_approved_at')->nullable();
            $table->text('admin_remarks')->nullable();
            
            // Final Approval
            $table->foreignId('final_approver_id')->nullable()->constrained('employees');
            $table->enum('final_approver_role', ['HR', 'Admin'])->nullable();
            $table->timestamp('final_approved_at')->nullable();
            
            // Payment
            $table->decimal('paid_amount', 10, 2)->nullable();
            $table->date('payment_date')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->foreignId('paid_by')->nullable()->constrained('users');
            $table->text('payment_remarks')->nullable();
            
            // Exception
            $table->boolean('is_exception')->default(false);
            $table->text('exception_reason')->nullable();
            $table->foreignId('exception_approved_by')->nullable()->constrained('users');
            
            // General
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('status');
            $table->index('requester_role');
            $table->index(['requester_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reimbursements');
    }
};
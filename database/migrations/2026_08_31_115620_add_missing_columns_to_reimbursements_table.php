<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reimbursements', function (Blueprint $table) {
            
            // ==========================================
            // POLICY COLUMNS
            // ==========================================
            
            // 1. policy_limit_type_at_submission
            if (!Schema::hasColumn('reimbursements', 'policy_limit_type_at_submission')) {
                $table->enum('policy_limit_type_at_submission', [
                    'per_claim', 
                    'per_day', 
                    'per_month', 
                    'per_year'
                ])->nullable()->after('policy_limit_at_submission');
            }
            
            // 2. approval_flow
            if (!Schema::hasColumn('reimbursements', 'approval_flow')) {
                $table->enum('approval_flow', [
                    'employee_to_manager_to_hr',
                    'manager_to_hr',
                    'hr_to_admin'
                ])->nullable()->after('status');
            }
            
            // 3. exception_amount
            if (!Schema::hasColumn('reimbursements', 'exception_amount')) {
                $table->decimal('exception_amount', 10, 2)->nullable()->after('is_exception');
            }
            
            // 4. exception_approved_at
            if (!Schema::hasColumn('reimbursements', 'exception_approved_at')) {
                $table->timestamp('exception_approved_at')->nullable()->after('exception_approved_by');
            }
            
            // ==========================================
            // ADDITIONAL MISSING COLUMNS (Just in case)
            // ==========================================
            
            // current_approval_level
            if (!Schema::hasColumn('reimbursements', 'current_approval_level')) {
                $table->string('current_approval_level')->nullable()->after('approval_flow');
            }
            
            // policy_id
            if (!Schema::hasColumn('reimbursements', 'policy_id')) {
                $table->foreignId('policy_id')->nullable()->constrained('reimbursement_policies')->onDelete('set null')->after('expense_type_id');
            }
            
            // policy_name_at_submission
            if (!Schema::hasColumn('reimbursements', 'policy_name_at_submission')) {
                $table->string('policy_name_at_submission')->nullable()->after('policy_id');
            }
            
            // policy_limit_at_submission
            if (!Schema::hasColumn('reimbursements', 'policy_limit_at_submission')) {
                $table->decimal('policy_limit_at_submission', 10, 2)->nullable()->after('policy_name_at_submission');
            }
            
            // receipt_required_at_submission
            if (!Schema::hasColumn('reimbursements', 'receipt_required_at_submission')) {
                $table->boolean('receipt_required_at_submission')->default(true)->after('policy_limit_type_at_submission');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reimbursements', function (Blueprint $table) {
            $table->dropColumn([
                'policy_limit_type_at_submission',
                'approval_flow',
                'exception_amount',
                'exception_approved_at',
                'current_approval_level',
                'policy_id',
                'policy_name_at_submission',
                'policy_limit_at_submission',
                'receipt_required_at_submission'
            ]);
        });
    }
};
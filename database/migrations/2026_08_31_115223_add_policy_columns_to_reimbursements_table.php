<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reimbursements', function (Blueprint $table) {
            // ✅ Policy related columns
            if (!Schema::hasColumn('reimbursements', 'policy_id')) {
                $table->foreignId('policy_id')->nullable()->constrained('reimbursement_policies')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('reimbursements', 'policy_name_at_submission')) {
                $table->string('policy_name_at_submission')->nullable();
            }
            
            if (!Schema::hasColumn('reimbursements', 'policy_limit_at_submission')) {
                $table->decimal('policy_limit_at_submission', 10, 2)->nullable();
            }
            
            if (!Schema::hasColumn('reimbursements', 'policy_limit_type_at_submission')) {
                $table->enum('policy_limit_type_at_submission', ['per_claim', 'per_day', 'per_month', 'per_year'])->nullable();
            }
            
            if (!Schema::hasColumn('reimbursements', 'receipt_required_at_submission')) {
                $table->boolean('receipt_required_at_submission')->default(true);
            }
        });
    }

    public function down(): void
    {
        Schema::table('reimbursements', function (Blueprint $table) {
            $table->dropColumn([
                'policy_id',
                'policy_name_at_submission',
                'policy_limit_at_submission',
                'policy_limit_type_at_submission',
                'receipt_required_at_submission'
            ]);
        });
    }
};
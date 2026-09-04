<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reimbursement_policies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('expense_type_id')->constrained('expense_types');
            $table->decimal('maximum_amount', 10, 2);
            $table->enum('limit_type', ['per_claim', 'per_day', 'per_month', 'per_year'])->default('per_claim');
            $table->integer('max_claims')->nullable();
            $table->boolean('receipt_required')->default(true);
            $table->boolean('exception_allowed')->default(false);
            $table->date('effective_from')->nullable();
            $table->date('effective_to')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reimbursement_policies');
    }
};
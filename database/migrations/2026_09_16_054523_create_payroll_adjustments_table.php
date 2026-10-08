<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('payroll_id')->nullable()->constrained('payrolls')->onDelete('cascade');
            $table->enum('adjustment_type', ['Earning', 'Deduction']);
            $table->enum('category', ['Bonus', 'Incentive', 'Penalty', 'Loan', 'Advance', 'Other']);
            $table->decimal('amount', 10, 2);
            $table->text('description')->nullable();
            $table->foreignId('added_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_adjustments');
    }
};
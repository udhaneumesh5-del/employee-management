<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reimbursement_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reimbursement_id')->constrained('reimbursements');
            $table->foreignId('user_id')->constrained('users');
            $table->enum('role', ['Employee', 'Manager', 'HR', 'Admin']);
            $table->string('approval_level');
            $table->enum('action', ['submitted', 'approved', 'rejected', 'cancelled', 'paid']);
            $table->string('previous_status')->nullable();
            $table->string('new_status');
            $table->text('remarks')->nullable();
            $table->boolean('is_final_approval')->default(false);
            $table->boolean('is_exception')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reimbursement_approvals');
    }
};
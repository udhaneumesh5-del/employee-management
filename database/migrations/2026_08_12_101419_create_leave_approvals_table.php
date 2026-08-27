<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leave_request_id')->constrained('leave_requests')->onDelete('cascade');
            $table->foreignId('approver_id')->constrained('users');
            $table->enum('approver_role', ['Manager', 'HR', 'Admin']);
            $table->enum('action', ['Approve', 'Reject']);
            $table->text('comment')->nullable();
            $table->timestamp('approved_at')->useCurrent();
            $table->timestamps();
            
            $table->index('leave_request_id');
            $table->index('approver_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_approvals');
    }
};
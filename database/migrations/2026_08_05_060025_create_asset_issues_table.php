<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_issues', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code');
            $table->string('employee_name');
            $table->string('department');
            
            // Only asset_id - No duplicate fields
            $table->foreignId('asset_id')->constrained('assets_master')->onDelete('cascade');
            
            $table->date('issue_date');
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            // Index for performance
            $table->index('employee_code');
            $table->index('asset_id');
            $table->index('issue_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_issues');
    }
};
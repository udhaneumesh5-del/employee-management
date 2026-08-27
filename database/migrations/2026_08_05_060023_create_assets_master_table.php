<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets_master', function (Blueprint $table) {
            $table->id();
            $table->string('asset_code')->unique();
            $table->string('asset_type');
            $table->string('company_name');
            $table->string('model');
            $table->enum('condition', ['Good', 'Damaged'])->default('Good');
            $table->enum('status', ['Available', 'Issued'])->default('Available');
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            // Indexes for better query performance
            $table->index('status');
            $table->index('asset_code');
            $table->index('asset_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets_master');
    }
};
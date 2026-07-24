<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code');
            $table->string('employee_name');
            $table->string('department');
            $table->string('asset_type'); // Laptop, Mouse, Keyboard, Charger, Bag
            $table->date('issue_date');
            $table->date('return_date')->nullable();
            $table->enum('status', ['Issued', 'Returned'])->default('Issued');
            $table->enum('condition', ['Good', 'Damaged'])->default('Good');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
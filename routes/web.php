<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// All routes require authentication
Route::middleware(['auth'])->group(function () {

    // Dashboard - Both Admin and HR can access
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Department routes - Only Admin can access
    Route::middleware(['role:Admin'])->group(function () {
        Route::resource('departments', DepartmentController::class);
    });

    // Employee routes - Both Admin and HR can access
    Route::middleware(['role:Admin,HR'])->group(function () {
        Route::resource('employees', EmployeeController::class);
    });
});

// Auth routes
require __DIR__.'/auth.php';
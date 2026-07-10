<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Activity Log - Only Admin
    Route::middleware(['role:Admin'])->group(function () {
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    });

    // Department routes - Only Admin
    Route::middleware(['role:Admin'])->group(function () {
        Route::get('/departments/trash', [DepartmentController::class, 'trash'])->name('departments.trash');
        Route::post('/departments/{id}/restore', [DepartmentController::class, 'restore'])->name('departments.restore');
        Route::delete('/departments/{id}/force-delete', [DepartmentController::class, 'forceDelete'])->name('departments.force-delete');
        Route::resource('departments', DepartmentController::class)->except(['show']);
    });

    // Employee routes - Admin and HR
    Route::middleware(['role:Admin,HR'])->group(function () {
        Route::get('/employees/trash', [EmployeeController::class, 'trash'])->name('employees.trash');
        Route::post('/employees/{id}/restore', [EmployeeController::class, 'restore'])->name('employees.restore');
        Route::delete('/employees/{id}/force-delete', [EmployeeController::class, 'forceDelete'])->name('employees.force-delete');
        Route::resource('employees', EmployeeController::class)->except(['show']);
    });
});

require __DIR__.'/auth.php';
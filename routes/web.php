<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AssetController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Activity Logs - Only Admin
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
        Route::get('/employees/export/csv', [EmployeeController::class, 'exportCSV'])->name('employees.export.csv');
        Route::resource('employees', EmployeeController::class);
    });

    // Attendance routes - Admin and HR
    Route::middleware(['role:Admin,HR'])->group(function () {
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
        Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
        Route::get('/attendance/{attendance}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
        Route::put('/attendance/{attendance}', [AttendanceController::class, 'update'])->name('attendance.update');
        Route::delete('/attendance/{attendance}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
        Route::post('/attendance/mark-today', [AttendanceController::class, 'markToday'])->name('attendance.mark-today');
    });

    // Asset routes - Admin and HR
    Route::middleware(['role:Admin,HR'])->group(function () {
        Route::get('/assets', [AssetController::class, 'index'])->name('assets.index');
        Route::get('/assets/create', [AssetController::class, 'create'])->name('assets.create');
        Route::post('/assets', [AssetController::class, 'store'])->name('assets.store');
        Route::get('/assets/{asset}/edit', [AssetController::class, 'edit'])->name('assets.edit');
        Route::put('/assets/{asset}', [AssetController::class, 'update'])->name('assets.update');
        Route::delete('/assets/{asset}', [AssetController::class, 'destroy'])->name('assets.destroy');

        // AJAX Route
        Route::get('/assets/get-employee', [AssetController::class, 'getEmployeeDetails'])->name('assets.get-employee');

        // Asset Reports
        Route::get('/assets/reports', [AssetController::class, 'reports'])->name('assets.reports');
        Route::get('/assets/report/all', [AssetController::class, 'allAssetsReport'])->name('assets.report.all');
        Route::get('/assets/report/issued', [AssetController::class, 'issuedAssetsReport'])->name('assets.report.issued');
        Route::get('/assets/report/returned', [AssetController::class, 'returnedAssetsReport'])->name('assets.report.returned');
        Route::get('/assets/report/pending', [AssetController::class, 'pendingReturnsReport'])->name('assets.report.pending');

        // Employee-wise Asset History
        Route::get('/assets/history', [AssetController::class, 'employeeHistory'])->name('assets.history');
    });
});

require __DIR__.'/auth.php';
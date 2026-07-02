<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('employees.index');
});

// Employee Routes
Route::resource('employees', EmployeeController::class);

// Department Routes
Route::resource('departments', DepartmentController::class);
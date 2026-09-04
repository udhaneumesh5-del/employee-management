<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AssetMasterController;
use App\Http\Controllers\AssetIssueController;
use App\Http\Controllers\AssetReturnController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ReimbursementController;
use App\Http\Controllers\ReimbursementPolicyController;
use App\Http\Controllers\ExpenseTypeController;
use App\Http\Controllers\ReimbursementReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // PROFILE ROUTES
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    
    // USER MANAGEMENT ROUTES
    Route::middleware(['role:Admin,HR'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::get('/users/get-employee/{id}', [UserController::class, 'getEmployeeDetails'])->name('users.get-employee');
    });
    
    // ACTIVITY LOGS ROUTES
    Route::middleware(['role:Admin,HR'])->group(function () {
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    });

    // DEPARTMENT ROUTES
    // List (All roles)
    Route::middleware(['role:Admin,HR,Manager,Employee'])->group(function () {
        Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    });

    // Create, Edit, Delete (Admin & HR) - SPECIFIC ROUTES FIRST
    Route::middleware(['role:Admin,HR'])->group(function () {
        Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
        Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
        Route::get('/departments/{id}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
        Route::put('/departments/{id}', [DepartmentController::class, 'update'])->name('departments.update');
        Route::delete('/departments/{id}', [DepartmentController::class, 'destroy'])->name('departments.destroy');
        Route::get('/departments/trash', [DepartmentController::class, 'trash'])->name('departments.trash');
        Route::post('/departments/{id}/restore', [DepartmentController::class, 'restore'])->name('departments.restore');
        Route::delete('/departments/{id}/force-delete', [DepartmentController::class, 'forceDelete'])->name('departments.force-delete');
    });

    // Show (Wildcard) - MUST be LAST
    Route::middleware(['role:Admin,HR,Manager,Employee'])->group(function () {
        Route::get('/departments/{id}', [DepartmentController::class, 'show'])->name('departments.show');
    });

    // EMPLOYEE ROUTES
    Route::middleware(['role:Admin,HR,Manager,Employee'])->group(function () {
        Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
        Route::get('/employees/export/csv', [EmployeeController::class, 'exportCSV'])->name('employees.export.csv');
        Route::get('/employees/trash', [EmployeeController::class, 'trash'])->name('employees.trash');
    });

    Route::middleware(['role:Admin,HR'])->group(function () {
        Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
        Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
        Route::get('/employees/{id}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
        Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('employees.update');
        Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
        Route::post('/employees/{id}/restore', [EmployeeController::class, 'restore'])->name('employees.restore');
        Route::delete('/employees/{id}/force-delete', [EmployeeController::class, 'forceDelete'])->name('employees.force-delete');
    });

    Route::middleware(['role:Admin,HR,Manager,Employee'])->group(function () {
        Route::get('/employees/{id}', [EmployeeController::class, 'show'])->name('employees.show');
    });
    
    // ATTENDANCE ROUTES
    Route::middleware(['role:Admin,HR,Manager,Employee'])->group(function () {
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
        Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
        Route::get('/attendance/{attendance}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
        Route::put('/attendance/{attendance}', [AttendanceController::class, 'update'])->name('attendance.update');
        Route::delete('/attendance/{attendance}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
        Route::post('/attendance/mark-today', [AttendanceController::class, 'markToday'])->name('attendance.mark-today');
    });

    // ASSET MANAGEMENT ROUTES
    Route::middleware(['role:Admin,HR,Manager,Employee'])->group(function () {
        // Asset Master List
        Route::get('/asset-master', [AssetMasterController::class, 'index'])
            ->name('asset-master.index');
        // IMPORTANT: Create route must come BEFORE {id}
        Route::get('/asset-master/create', [AssetMasterController::class, 'create'])
            ->name('asset-master.create');
        // View Asset
        Route::get('/asset-master/{id}', [AssetMasterController::class, 'show'])
            ->name('asset-master.show');
    });
    Route::middleware(['role:Admin,HR'])->group(function () {
        // Store New Asset
        Route::post('/asset-master', [AssetMasterController::class, 'store'])
            ->name('asset-master.store');

        // Edit Asset
        Route::get('/asset-master/{id}/edit', [AssetMasterController::class, 'edit'])
            ->name('asset-master.edit');

        // Update Asset
        Route::put('/asset-master/{id}', [AssetMasterController::class, 'update'])
            ->name('asset-master.update');

        // Delete Asset
        Route::delete('/asset-master/{id}', [AssetMasterController::class, 'destroy'])
            ->name('asset-master.destroy');

        // ASSET ISSUE
        Route::get('/asset-issue', [AssetIssueController::class, 'index'])
            ->name('asset-issue.index');

        Route::get('/asset-issue/create', [AssetIssueController::class, 'create'])
            ->name('asset-issue.create');

        Route::post('/asset-issue', [AssetIssueController::class, 'store'])
            ->name('asset-issue.store');

        Route::get('/asset-issue/get-asset/{id}', [AssetIssueController::class, 'getAssetDetails'])
            ->name('asset-issue.get-asset');

        Route::get('/asset-issue/get-employee', [AssetIssueController::class, 'getEmployeeDetails'])
            ->name('asset-issue.get-employee');

        Route::get('/asset-issue/report', [AssetIssueController::class, 'issuedReport'])
            ->name('asset-issue.report');

        Route::get('/asset-issue/export-csv', [AssetIssueController::class, 'exportCSV'])
            ->name('asset-issue.export-csv');

        // ASSET RETURN
        Route::get('/asset-return', [AssetReturnController::class, 'index'])
            ->name('asset-return.index');

        Route::get('/asset-return/create', [AssetReturnController::class, 'create'])
            ->name('asset-return.create');

        Route::post('/asset-return', [AssetReturnController::class, 'store'])
            ->name('asset-return.store');

        Route::get('/asset-return/get-issue/{id}', [AssetReturnController::class, 'getAssetIssueDetails'])
            ->name('asset-return.get-issue');

        Route::get('/asset-return/report', [AssetReturnController::class, 'returnedReport'])
            ->name('asset-return.report');

        Route::get('/asset-return/export-csv', [AssetReturnController::class, 'exportCSV'])
            ->name('asset-return.export-csv');
    });

    // LEAVE MANAGEMENT ROUTES
    // Employee Routes (All authenticated users)
    Route::get('/leave/apply', [LeaveController::class, 'applyForm'])->name('leave.apply-form');
    Route::post('/leave/apply', [LeaveController::class, 'apply'])->name('leave.apply');
    Route::get('/leave/my-leaves', [LeaveController::class, 'myLeaves'])->name('leave.my-leaves');
    Route::get('/leave/view/{id}', [LeaveController::class, 'viewLeave'])->name('leave.view');
    Route::post('/leave/cancel/{id}', [LeaveController::class, 'cancel'])->name('leave.cancel');
    Route::get('/leave/balance', [LeaveController::class, 'leaveBalance'])->name('leave.balance');

    // Manager Routes
    Route::middleware(['role:Manager,Admin'])->group(function () {
        Route::get('/leave/manager/dashboard', [LeaveController::class, 'managerDashboard'])->name('leave.manager.dashboard');
        Route::get('/leave/manager/pending', [LeaveController::class, 'managerPending'])->name('leave.manager.pending');
        Route::post('/leave/manager/approve/{id}', [LeaveController::class, 'managerApprove'])->name('leave.manager.approve');
        Route::post('/leave/manager/reject/{id}', [LeaveController::class, 'managerReject'])->name('leave.manager.reject');
    });

    // HR Routes
    Route::middleware(['role:HR,Admin'])->group(function () {
        Route::get('/leave/hr/dashboard', [LeaveController::class, 'hrDashboard'])->name('leave.hr.dashboard');
        Route::get('/leave/hr/pending', [LeaveController::class, 'hrPending'])->name('leave.hr.pending');
        Route::post('/leave/hr/approve/{id}', [LeaveController::class, 'hrApprove'])->name('leave.hr.approve');
        Route::post('/leave/hr/reject/{id}', [LeaveController::class, 'hrReject'])->name('leave.hr.reject');
        Route::get('/leave/hr/all', [LeaveController::class, 'hrAllLeaves'])->name('leave.hr.all');
        Route::get('/leave/hr/balances', [LeaveController::class, 'hrLeaveBalances'])->name('leave.hr.balances');
    });

    // Admin Leave Management Routes
    Route::middleware(['role:Admin'])->group(function () {
        Route::get('/leave/admin/dashboard', [LeaveController::class, 'adminDashboard'])->name('leave.admin.dashboard');
        Route::get('/leave/admin/pending', [LeaveController::class, 'adminPending'])->name('leave.admin.pending');
        Route::post('/leave/admin/approve/{id}', [LeaveController::class, 'adminApprove'])->name('leave.admin.approve');
        Route::post('/leave/admin/reject/{id}', [LeaveController::class, 'adminReject'])->name('leave.admin.reject');
        Route::get('/leave/admin/balances', [LeaveController::class, 'adminBalances'])->name('leave.admin.balances');
        Route::post('/leave/admin/balances/assign', [LeaveController::class, 'adminBalancesAssign'])->name('leave.admin.balances.assign');
    });

    // LEAVE TYPES - ADMIN & HR
    Route::middleware(['role:Admin,HR'])->group(function () {  
        Route::get('/leave-types', [LeaveTypeController::class, 'index'])->name('leave-types.index');
        Route::get('/leave-types/create', [LeaveTypeController::class, 'create'])->name('leave-types.create');
        Route::post('/leave-types', [LeaveTypeController::class, 'store'])->name('leave-types.store');
        Route::get('/leave-types/{id}/edit', [LeaveTypeController::class, 'edit'])->name('leave-types.edit');
        Route::put('/leave-types/{id}', [LeaveTypeController::class, 'update'])->name('leave-types.update');
        Route::delete('/leave-types/{id}', [LeaveTypeController::class, 'destroy'])->name('leave-types.destroy');
        Route::post('/leave-types/toggle/{id}', [LeaveTypeController::class, 'toggleStatus'])->name('leave-types.toggle');
    });

// ==========================================
// REIMBURSEMENT MANAGEMENT ROUTES
// ==========================================

Route::middleware(['auth'])->group(function () {

    // 1 ALL SPECIFIC ROUTES FIRST
    
    // Dashboard
    Route::get('/reimbursements/dashboard', [ReimbursementController::class, 'dashboard'])
        ->name('reimbursements.dashboard');

    // Create
    Route::get('/reimbursements/create', [ReimbursementController::class, 'create'])
        ->name('reimbursements.create');
    Route::post('/reimbursements', [ReimbursementController::class, 'store'])
        ->name('reimbursements.store');

    // My Requests
    Route::get('/reimbursements/my-requests', [ReimbursementController::class, 'myRequests'])
        ->name('reimbursements.my-requests');

    // All Requests (Admin)
    Route::get('/reimbursements/all-requests', [ReimbursementController::class, 'allRequests'])
        ->name('reimbursements.all-requests');

    // Manager Routes
    Route::get('/reimbursements/manager/pending', [ReimbursementController::class, 'managerPending'])
        ->name('reimbursements.manager.pending');
    Route::post('/reimbursements/manager/approve/{id}', [ReimbursementController::class, 'managerApprove'])
        ->name('reimbursements.manager.approve');
    Route::post('/reimbursements/manager/reject/{id}', [ReimbursementController::class, 'managerReject'])
        ->name('reimbursements.manager.reject');

    // HR Routes
    Route::get('/reimbursements/hr/pending', [ReimbursementController::class, 'hrPending'])
        ->name('reimbursements.hr.pending');
    Route::post('/reimbursements/hr/approve/{id}', [ReimbursementController::class, 'hrApprove'])
        ->name('reimbursements.hr.approve');
    Route::post('/reimbursements/hr/reject/{id}', [ReimbursementController::class, 'hrReject'])
        ->name('reimbursements.hr.reject');

    // Admin Routes
    Route::get('/reimbursements/admin/pending', [ReimbursementController::class, 'adminPending'])
        ->name('reimbursements.admin.pending');
    Route::post('/reimbursements/admin/approve/{id}', [ReimbursementController::class, 'adminApprove'])
        ->name('reimbursements.admin.approve');
    Route::post('/reimbursements/admin/reject/{id}', [ReimbursementController::class, 'adminReject'])
        ->name('reimbursements.admin.reject');

    // Payments
    Route::get('/reimbursements/payments', [ReimbursementController::class, 'payments'])
        ->name('reimbursements.payments.index');
    Route::post('/reimbursements/payments/process/{id}', [ReimbursementController::class, 'processPayment'])
        ->name('reimbursements.payments.process');

    // 2️ PATTERN ROUTES (Has ID but specific)
    
    // Edit
    Route::get('/reimbursements/{id}/edit', [ReimbursementController::class, 'edit'])
        ->name('reimbursements.edit');
    Route::put('/reimbursements/{id}', [ReimbursementController::class, 'update'])
        ->name('reimbursements.update');
    Route::delete('/reimbursements/{id}', [ReimbursementController::class, 'cancel'])
        ->name('reimbursements.cancel');

  
    // 3️ AJAX ROUTES
    
    Route::get('/reimbursements/policy/{expenseTypeId}', function ($expenseTypeId) {
        $policy = App\Models\ReimbursementPolicy::where('expense_type_id', $expenseTypeId)
            ->where('status', 'Active')
            ->where('effective_from', '<=', now())
            ->where(function($q) {
                $q->where('effective_to', '>=', now())
                  ->orWhereNull('effective_to');
            })
            ->first();
        return response()->json(['policy' => $policy]);
    })->name('reimbursements.policy.info');

    // 4️ ADMIN/HR MANAGEMENT ROUTES
    
    // Policies
    Route::middleware(['role:Admin,HR'])->group(function () {
        Route::get('/reimbursements/policies', [ReimbursementPolicyController::class, 'index'])
            ->name('reimbursements.policies.index');
        Route::get('/reimbursements/policies/create', [ReimbursementPolicyController::class, 'create'])
            ->name('reimbursements.policies.create');
        Route::post('/reimbursements/policies', [ReimbursementPolicyController::class, 'store'])
            ->name('reimbursements.policies.store');
        Route::get('/reimbursements/policies/{id}/edit', [ReimbursementPolicyController::class, 'edit'])
            ->name('reimbursements.policies.edit');
        Route::put('/reimbursements/policies/{id}', [ReimbursementPolicyController::class, 'update'])
            ->name('reimbursements.policies.update');
        Route::delete('/reimbursements/policies/{id}', [ReimbursementPolicyController::class, 'destroy'])
            ->name('reimbursements.policies.destroy');
        Route::post('/reimbursements/policies/toggle/{id}', [ReimbursementPolicyController::class, 'toggleStatus'])
            ->name('reimbursements.policies.toggle');
    });

    // Expense Types
    Route::middleware(['role:Admin,HR'])->group(function () {
        Route::get('/reimbursements/expense-types', [ExpenseTypeController::class, 'index'])
            ->name('reimbursements.expense-types.index');
        Route::get('/reimbursements/expense-types/create', [ExpenseTypeController::class, 'create'])
            ->name('reimbursements.expense-types.create');
        Route::post('/reimbursements/expense-types', [ExpenseTypeController::class, 'store'])
            ->name('reimbursements.expense-types.store');
        Route::get('/reimbursements/expense-types/{id}/edit', [ExpenseTypeController::class, 'edit'])
            ->name('reimbursements.expense-types.edit');
        Route::put('/reimbursements/expense-types/{id}', [ExpenseTypeController::class, 'update'])
            ->name('reimbursements.expense-types.update');
        Route::delete('/reimbursements/expense-types/{id}', [ExpenseTypeController::class, 'destroy'])
            ->name('reimbursements.expense-types.destroy');
        Route::post('/reimbursements/expense-types/toggle/{id}', [ExpenseTypeController::class, 'toggleStatus'])
            ->name('reimbursements.expense-types.toggle');
    });

    // Reports
    Route::middleware(['role:Admin,HR'])->group(function () {
        Route::get('/reimbursements/reports', [ReimbursementReportController::class, 'index'])
            ->name('reimbursements.reports.index');
        Route::get('/reimbursements/reports/export-csv', [ReimbursementReportController::class, 'exportCSV'])
            ->name('reimbursements.reports.export-csv');
        Route::get('/reimbursements/reports/export-pdf', [ReimbursementReportController::class, 'exportPDF'])
            ->name('reimbursements.reports.export-pdf');
    });

    // 5️ WILDCARD ROUTE - ABSOLUTELY LAST
    // Show (Wildcard) - MUST BE LAST
    Route::get('/reimbursements/{id}', [ReimbursementController::class, 'show'])
        ->name('reimbursements.show');
});

    // REPORT ROUTES
    Route::middleware(['role:Admin,HR,Manager,Employee'])->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/employee', [ReportController::class, 'employeeReport'])->name('reports.employee');
        Route::get('/reports/attendance', [ReportController::class, 'attendanceReport'])->name('reports.attendance');
        Route::get('/reports/asset', [ReportController::class, 'assetReport'])->name('reports.asset');
        Route::get('/reports/leave', [ReportController::class, 'leaveReport'])->name('reports.leave');
    });

    // SETTINGS ROUTES
    Route::middleware(['role:Admin,HR'])->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::get('/settings/general', [SettingController::class, 'general'])->name('settings.general');
        Route::get('/settings/company', [SettingController::class, 'company'])->name('settings.company');
        Route::post('/settings/general/update', [SettingController::class, 'updateGeneral'])->name('settings.update-general');
        Route::post('/settings/company/update', [SettingController::class, 'updateCompany'])->name('settings.update-company');
    });
});

// ASSET REDIRECT
Route::get('/assets', function () {
    return redirect()->route('asset-master.index');
})->name('assets.index');

// AUTH ROUTES
require __DIR__.'/auth.php';

// FALLBACK ROUTE
Route::fallback(function () {
    return redirect()->route('dashboard');
});
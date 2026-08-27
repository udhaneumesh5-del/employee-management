<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userRole = auth()->user()->role;

        // Employee Stats
       
        $totalEmployees = DB::table('employees')->count();
        $totalDepartments = DB::table('departments')->count();
        $activeEmployees = DB::table('employees')->where('status', 'Active')->count();
        $inactiveEmployees = DB::table('employees')->where('status', 'Inactive')->count();

        // Attendance Stats (Today)

        $today = now()->toDateString();
        $todayPresent = DB::table('attendances')->whereDate('date', $today)->where('status', 'Present')->count();
        $todayAbsent = DB::table('attendances')->whereDate('date', $today)->where('status', 'Absent')->count();
        $todayLeave = DB::table('attendances')->whereDate('date', $today)->where('status', 'Leave')->count();

        // Attendance Summary (This Month)

        $monthStart = now()->startOfMonth()->toDateString();
        $monthEnd = now()->endOfMonth()->toDateString();
        $monthPresent = DB::table('attendances')
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->where('status', 'Present')
            ->count();
        $monthAbsent = DB::table('attendances')
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->where('status', 'Absent')
            ->count();
        $monthLeave = DB::table('attendances')
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->where('status', 'Leave')
            ->count();

        // Leave Stats
       
        $pendingManager = DB::table('leave_requests')
            ->where('status', 'pending_manager')
            ->count();
        $pendingHR = DB::table('leave_requests')
            ->where('status', 'pending_hr')
            ->count();
        $approvedThisMonth = DB::table('leave_requests')
            ->where('status', 'approved')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $rejectedThisMonth = DB::table('leave_requests')
            ->whereIn('status', ['manager_rejected', 'hr_rejected'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Department-wise Employees
        $departmentWise = DB::table('employees')
            ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
            ->select('departments.department_name', DB::raw('count(*) as total'))
            ->whereNull('employees.deleted_at')
            ->groupBy('departments.department_name')
            ->get();

        // Asset Stats
        $totalAssets = DB::table('assets_master')->count();
        $availableAssets = DB::table('assets_master')->where('status', 'Available')->count();
        $issuedAssets = DB::table('assets_master')->where('status', 'Issued')->count();
        $returnedAssets = DB::table('asset_returns')->count();

        // Recent Activity 
        $recentActivity = DB::table('activity_logs')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

      
        // Recent Employees
    
        $recentEmployees = DB::table('employees')
            ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
            ->select('employees.*', 'departments.department_name')
            ->orderBy('employees.created_at', 'desc')
            ->limit(5)
            ->get();

        // Employee Status Counts (For Progress Bars)//
        $onLeaveToday = $todayLeave;

        return view('dashboard', compact(
            'userRole',
            'totalEmployees',
            'totalDepartments',
            'activeEmployees',
            'inactiveEmployees',
            'todayPresent',
            'todayAbsent',
            'todayLeave',
            'monthPresent',
            'monthAbsent',
            'monthLeave',
            'pendingManager',
            'pendingHR',
            'approvedThisMonth',
            'rejectedThisMonth',
            'departmentWise',
            'totalAssets',
            'availableAssets',
            'issuedAssets',
            'returnedAssets',
            'recentActivity',
            'recentEmployees',
            'onLeaveToday'
        ));
    }
}
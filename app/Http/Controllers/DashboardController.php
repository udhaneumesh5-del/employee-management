<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Attendance;
use App\Models\Asset;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Employee Stats
        $totalEmployees = Employee::count();
        $totalDepartments = Department::count();
        $activeEmployees = Employee::where('status', 'Active')->count();
        $inactiveEmployees = Employee::where('status', 'Inactive')->count();

        // Attendance Stats
        $today = now()->toDateString();
        $todayPresent = Attendance::whereDate('date', $today)->where('status', 'Present')->count();
        $todayAbsent = Attendance::whereDate('date', $today)->where('status', 'Absent')->count();
        $todayLeave = Attendance::whereDate('date', $today)->where('status', 'Leave')->count();
        $todayTotal = Attendance::whereDate('date', $today)->count();

        //  Asset Stats
        $totalAssets = Asset::count();
        $issuedAssets = Asset::where('status', 'Issued')->count();
        $returnedAssets = Asset::where('status', 'Returned')->count();
        $pendingReturns = Asset::where('status', 'Issued')
                              ->where('return_date', '<', now())
                              ->count();

        // Recent Employees
        $recentEmployees = Employee::with('department')
                                  ->latest()
                                  ->take(5)
                                  ->get();

        // Recent Attendance
        $recentAttendance = Attendance::with('employee')
                                     ->latest()
                                     ->take(5)
                                     ->get();

        //  Recent Assets
        $recentAssets = Asset::latest()->take(5)->get();

        return view('dashboard', compact(
            'totalEmployees',
            'totalDepartments',
            'activeEmployees',
            'inactiveEmployees',
            'todayPresent',
            'todayAbsent',
            'todayLeave',
            'todayTotal',
            'totalAssets',
            'issuedAssets',
            'returnedAssets',
            'pendingReturns',
            'recentEmployees',
            'recentAttendance',
            'recentAssets'
        ));
    }
}
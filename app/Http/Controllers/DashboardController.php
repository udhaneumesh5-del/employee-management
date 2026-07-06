<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Card data
        $totalEmployees = Employee::count();
        $totalDepartments = Department::count();
        $activeEmployees = Employee::where('status', 'Active')->count();
        $inactiveEmployees = Employee::where('status', 'Inactive')->count();
        
        // Recent employees (last 10)
        $recentEmployees = Employee::with('department')
                                  ->latest()
                                  ->take(10)
                                  ->get();
        
        return view('dashboard', compact(
            'totalEmployees',
            'totalDepartments',
            'activeEmployees',
            'inactiveEmployees',
            'recentEmployees'
        ));
    }
}
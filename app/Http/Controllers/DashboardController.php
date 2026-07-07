<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmployees = Employee::count();
        $totalDepartments = Department::count();
        $activeEmployees = Employee::where('status', 'Active')->count();
        $inactiveEmployees = Employee::where('status', 'Inactive')->count();
        
        $recentEmployees = Employee::with('department')
                                  ->latest()
                                  ->take(5)
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
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Role-based data
        $data = [];

        if ($user->isAdmin() || $user->isHR()) {
            // Full reports for Admin & HR
            $data['totalEmployees'] = DB::table('employees')->count();
            $data['totalDepartments'] = DB::table('departments')->count();
            $data['totalAttendances'] = DB::table('attendances')->count();
            $data['totalAssets'] = DB::table('assets_master')->count();
            $data['totalLeaves'] = DB::table('leave_requests')->count();
        } elseif ($user->isManager()) {
            // Team reports for Manager
            $manager = \App\Models\Employee::where('email', $user->email)->first();
            if ($manager) {
                $teamIds = \App\Models\Employee::where('manager_id', $manager->id)->pluck('id');
                $data['teamSize'] = $teamIds->count();
                $data['teamAttendance'] = DB::table('attendances')
                    ->whereIn('employee_id', $teamIds)
                    ->count();
                $data['teamLeaves'] = DB::table('leave_requests')
                    ->whereIn('employee_id', $teamIds)
                    ->count();
            }
        } elseif ($user->isEmployee()) {
            // Own reports for Employee
            $employee = \App\Models\Employee::where('email', $user->email)->first();
            if ($employee) {
                $data['myAttendance'] = DB::table('attendances')
                    ->where('employee_id', $employee->id)
                    ->count();
                $data['myLeaves'] = DB::table('leave_requests')
                    ->where('employee_id', $employee->id)
                    ->count();
                $data['myAssets'] = DB::table('asset_issues')
                    ->where('employee_code', $employee->employee_code)
                    ->count();
            }
        }

        return view('reports.index', compact('data'));
    }
 
    // Employee Report
   
    public function employeeReport(Request $request)
    {
        $query = DB::table('employees')
            ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
            ->select('employees.*', 'departments.department_name');

        $user = auth()->user();

        if ($user->isManager()) {
            $manager = \App\Models\Employee::where('email', $user->email)->first();
            if ($manager) {
                $teamIds = \App\Models\Employee::where('manager_id', $manager->id)->pluck('id');
                $query->whereIn('employees.id', $teamIds);
            }
        } elseif ($user->isEmployee()) {
            $employee = \App\Models\Employee::where('email', $user->email)->first();
            if ($employee) {
                $query->where('employees.id', $employee->id);
            }
        }

        $employees = $query->get();
        return view('reports.employee', compact('employees'));
    }

    // Attendance Report
   
    public function attendanceReport(Request $request)
    {
        $query = DB::table('attendances')
            ->join('employees', 'attendances.employee_id', '=', 'employees.id')
            ->select('attendances.*', 'employees.first_name', 'employees.last_name');

        $user = auth()->user();

        if ($user->isManager()) {
            $manager = \App\Models\Employee::where('email', $user->email)->first();
            if ($manager) {
                $teamIds = \App\Models\Employee::where('manager_id', $manager->id)->pluck('id');
                $query->whereIn('attendances.employee_id', $teamIds);
            }
        } elseif ($user->isEmployee()) {
            $employee = \App\Models\Employee::where('email', $user->email)->first();
            if ($employee) {
                $query->where('attendances.employee_id', $employee->id);
            }
        }

        if ($request->filled('from_date')) {
            $query->whereDate('attendances.date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('attendances.date', '<=', $request->to_date);
        }

        $attendances = $query->get();
        return view('reports.attendance', compact('attendances'));
    }
    // Asset Report
   
    public function assetReport(Request $request)
    {
        $query = DB::table('assets_master');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $assets = $query->get();
        return view('reports.asset', compact('assets'));
    }
  
    // Leave Report
   
    public function leaveReport(Request $request)
    {
        $query = DB::table('leave_requests')
            ->join('employees', 'leave_requests.employee_id', '=', 'employees.id')
            ->join('leave_types', 'leave_requests.leave_type_id', '=', 'leave_types.id')
            ->select('leave_requests.*', 'employees.first_name', 'employees.last_name', 'leave_types.name as leave_type_name');

        $user = auth()->user();

        if ($user->isManager()) {
            $manager = \App\Models\Employee::where('email', $user->email)->first();
            if ($manager) {
                $teamIds = \App\Models\Employee::where('manager_id', $manager->id)->pluck('id');
                $query->whereIn('leave_requests.employee_id', $teamIds);
            }
        } elseif ($user->isEmployee()) {
            $employee = \App\Models\Employee::where('email', $user->email)->first();
            if ($employee) {
                $query->where('leave_requests.employee_id', $employee->id);
            }
        }

        if ($request->filled('status')) {
            $query->where('leave_requests.status', $request->status);
        }

        $leaves = $query->get();
        return view('reports.leave', compact('leaves'));
    }
}
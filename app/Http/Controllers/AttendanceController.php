<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    // Show attendance form
    public function index(Request $request)
    {
        $query = Attendance::with('employee');

        // Date filter
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        } else {
            $query->whereDate('date', now()->toDateString());
        }

        // Employee filter
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->paginate(10);
        $employees = Employee::where('status', 'Active')->get();
        
        return view('attendance.index', compact('attendances', 'employees'));
    }

    // Show create form
    public function create()
    {
        $employees = Employee::where('status', 'Active')->get();
        return view('attendance.create', compact('employees'));
    }

    // Store attendance
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'status' => 'required|in:Present,Absent,Leave',
            'check_in' => 'nullable',
            'check_out' => 'nullable',
            'remarks' => 'nullable|string'
        ]);

        // Check if attendance already exists
        $exists = Attendance::where('employee_id', $request->employee_id)
                           ->whereDate('date', $request->date)
                           ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Attendance already marked for this employee on this date!')
                ->withInput();
        }

        Attendance::create($request->all());

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance marked successfully!');
    }

    // Show edit form
    public function edit(Attendance $attendance)
    {
        $employees = Employee::where('status', 'Active')->get();
        return view('attendance.edit', compact('attendance', 'employees'));
    }

    // Update attendance
    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'status' => 'required|in:Present,Absent,Leave',
            'check_in' => 'nullable',
            'check_out' => 'nullable',
            'remarks' => 'nullable|string'
        ]);

        $attendance->update($request->all());

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance updated successfully!');
    }

    // Delete attendance
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance deleted successfully!');
    }

    // Mark attendance for today (Quick action)
    public function markToday(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'status' => 'required|in:Present,Absent,Leave'
        ]);

        $today = now()->toDateString();

        $exists = Attendance::where('employee_id', $request->employee_id)
                           ->whereDate('date', $today)
                           ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Attendance already marked for today!');
        }

        Attendance::create([
            'employee_id' => $request->employee_id,
            'date' => $today,
            'status' => $request->status,
            'check_in' => $request->status == 'Present' ? now()->format('H:i:s') : null,
        ]);

        return redirect()->back()
            ->with('success', 'Today\'s attendance marked successfully!');
    }
}
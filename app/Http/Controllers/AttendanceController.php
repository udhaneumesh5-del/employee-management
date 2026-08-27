<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Get logged-in user's employee record
        $employee = Employee::where('email', $user->email)->first();
        
        $query = Attendance::with('employee');

        // If logged-in user has employee record, filter by that employee only
        if ($employee) {
            $query->where('employee_id', $employee->id);
        } else {
            // If no employee record found, show empty
            $query->where('employee_id', 0);
        }

        // Date filter
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        } else {
            $query->whereDate('date', now()->toDateString());
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->paginate(10);
        
        // Only pass logged-in employee for dropdown
        $employees = $employee ? collect([$employee]) : collect();
        
        return view('attendance.index', compact('attendances', 'employees'));
    }

    public function create()
    {
        // Get logged-in user's employee record only
        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();
        
        //  Only pass logged-in employee (if exists)
        $employees = $employee ? collect([$employee]) : collect();
        
        return view('attendance.create', compact('employees'));
    }

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

        //  Ensure employee_id belongs to logged-in user
        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();
        
        if (!$employee || $request->employee_id != $employee->id) {
            return redirect()->back()
                ->with('error', 'You can only mark attendance for yourself.')
                ->withInput();
        }

        // Check if attendance already exists
        $exists = Attendance::where('employee_id', $request->employee_id)
                           ->whereDate('date', $request->date)
                           ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Attendance already marked for this date!')
                ->withInput();
        }

        Attendance::create($request->all());

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance marked successfully!');
    }

    public function edit(Attendance $attendance)
    {
        //  Ensure attendance belongs to logged-in user
        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();
        
        if (!$employee || $attendance->employee_id != $employee->id) {
            return redirect()->route('attendance.index')
                ->with('error', 'You can only edit your own attendance.');
        }
        
        $employees = collect([$employee]);
        return view('attendance.edit', compact('attendance', 'employees'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        //  Ensure attendance belongs to logged-in user
        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();
        
        if (!$employee || $attendance->employee_id != $employee->id) {
            return redirect()->back()
                ->with('error', 'You can only update your own attendance.');
        }

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

    public function destroy(Attendance $attendance)
    {
        //  Ensure attendance belongs to logged-in user
        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();
        
        if (!$employee || $attendance->employee_id != $employee->id) {
            return redirect()->back()
                ->with('error', 'You can only delete your own attendance.');
        }

        $attendance->delete();

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance deleted successfully!');
    }

    public function markToday(Request $request)
    {
        $request->validate([
            'status' => 'required|in:Present,Absent,Leave'
        ]);

        //  Get logged-in user's employee
        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();

        if (!$employee) {
            return redirect()->back()
                ->with('error', 'Employee record not found.');
        }

        $today = now()->toDateString();

        $exists = Attendance::where('employee_id', $employee->id)
                           ->whereDate('date', $today)
                           ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Attendance already marked for today!');
        }

        Attendance::create([
            'employee_id' => $employee->id,
            'date' => $today,
            'status' => $request->status,
            'check_in' => $request->status == 'Present' ? now()->format('H:i:s') : null,
        ]);

        return redirect()->back()
            ->with('success', 'Today\'s attendance marked successfully!');
    }
}
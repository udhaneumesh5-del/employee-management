<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use App\Models\LeaveApproval;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveController extends Controller
{
    /**
     * Show Apply Leave Form - Auto select manager from employee record (if Employee)
     */
    public function applyForm()
    {
        $leaveTypes = LeaveType::where('is_active', true)->get();
        
        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();
        
        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'Employee record not found.');
        }
        
        // Manager manager assigned 
        if ($user->isEmployee() && !$employee->manager_id) {
            return redirect()->route('dashboard')->with('error', 'No manager assigned to you. Please contact HR.');
        }
        
        // Employee manager details
        $manager = null;
        if ($user->isEmployee() && $employee->manager_id) {
            $manager = Employee::find($employee->manager_id);
        }
        
        return view('leave.employee.apply', compact('leaveTypes', 'employee', 'manager'));
    }

    /**
     * Apply Leave - Role based status assignment
     */
    public function apply(Request $request)
    {
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'from_date' => 'required|date|after_or_equal:today',
            'to_date' => 'required|date|after_or_equal:from_date',
            'reason' => 'required|string|min:10',
            'document' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048'
        ]);

        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee record not found.');
        }

        // Employee to manager 
        if ($user->isEmployee() && !$employee->manager_id) {
            return redirect()->back()->with('error', 'No manager assigned to you. Please contact HR.');
        }

        $leaveType = LeaveType::findOrFail($request->leave_type_id);
        $totalDays = $this->calculateDays($request->from_date, $request->to_date);

        // Check duplicate leave requests
        $duplicate = LeaveRequest::where('employee_id', $employee->id)
            ->where(function ($q) use ($request) {
                $q->whereBetween('from_date', [$request->from_date, $request->to_date])
                  ->orWhereBetween('to_date', [$request->from_date, $request->to_date]);
            })
            ->whereIn('status', ['pending_manager', 'pending_hr', 'pending_admin'])
            ->exists();

        if ($duplicate) {
            return redirect()->back()->with('error', 'Leave already exists for selected dates.');
        }

        // Check balance
        $balance = LeaveBalance::where('employee_id', $employee->id)
            ->where('leave_type_id', $request->leave_type_id)
            ->where('year', now()->year)
            ->first();

        if ($balance && $balance->remaining_days < $totalDays) {
            return redirect()->back()
                ->with('error', 'Insufficient leave balance. Available: ' . $balance->remaining_days . ' days');
        }

        // Upload document
        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('leave-documents', 'public');
        }

        // Status based on role
        if ($user->isEmployee()) {
            $status = 'pending_manager';      // Employee → Manager
            $manager_id = $employee->manager_id;
        } elseif ($user->isManager()) {
            $status = 'pending_hr';            // Manager → HR (No manager needed)
            $manager_id = null;
        } elseif ($user->isHR()) {
            $status = 'pending_admin';         // HR → Admin
            $manager_id = null;
        } elseif ($user->isAdmin()) {
            $status = 'approved';              // Admin → Auto Approved
            $manager_id = null;
        }

        // Create leave request
        $leaveRequest = LeaveRequest::create([
            'employee_id' => $employee->id,
            'leave_type_id' => $request->leave_type_id,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'total_days' => $totalDays,
            'reason' => $request->reason,
            'document' => $documentPath,
            'status' => $status,
            'manager_id' => $manager_id ?? null
        ]);

        // If Admin applies, auto approve
        if ($user->isAdmin()) {
            $this->finalApprove($leaveRequest, 'Admin');
        }

        // Log activity
        DB::table('activity_logs')->insert([
            'employee_name' => $employee->first_name . ' ' . $employee->last_name,
            'action' => 'Applied for leave (' . $leaveType->name . ')',
            'performed_by' => auth()->user()->name,
            'performed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $message = 'Leave applied successfully!';
        if ($status == 'pending_manager') {
            $message .= ' Waiting for Manager approval.';
        } elseif ($status == 'pending_hr') {
            $message .= ' Waiting for HR approval.';
        } elseif ($status == 'pending_admin') {
            $message .= ' Waiting for Admin approval.';
        } elseif ($status == 'approved') {
            $message .= ' Leave approved!';
        }

        return redirect()->route('leave.my-leaves')->with('success', $message);
    }
  
    /**
     * My Leaves - Using INNER JOIN
     */
    public function myLeaves(Request $request)
    {
        $employee = Employee::where('email', auth()->user()->email)->first();

        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'Employee record not found.');
        }

        // USING INNER JOIN: Get leave requests with leave type
        $query = LeaveRequest::select(
                'leave_requests.*',
                'leave_types.id as leave_type_id',
                'leave_types.name as leave_type_name',
                'leave_types.code as leave_type_code'
            )
            ->join('leave_types', 'leave_requests.leave_type_id', '=', 'leave_types.id')
            ->where('leave_requests.employee_id', $employee->id);

        if ($request->filled('status')) {
            $query->where('leave_requests.status', $request->status);
        }
        if ($request->filled('leave_type_id')) {
            $query->where('leave_requests.leave_type_id', $request->leave_type_id);
        }
        if ($request->filled('from_date')) {
            $query->where('leave_requests.from_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('leave_requests.to_date', '<=', $request->to_date);
        }

        $leaves = $query->latest('leave_requests.created_at')->paginate(10);
        $leaveTypes = LeaveType::where('is_active', true)->get();

        return view('leave.employee.my-leaves', compact('leaves', 'leaveTypes'));
    }

    /**
     * View Leave Details - Using INNER JOIN with multiple tables
     */
    public function viewLeave($id)
    {
        $leave = LeaveRequest::select(
                'leave_requests.*',
                'employees.id as employee_id',
                'employees.first_name as employee_first_name',
                'employees.last_name as employee_last_name',
                'employees.email as employee_email',
                'employees.manager_id as employee_manager_id',
                'leave_types.id as leave_type_id',
                'leave_types.name as leave_type_name',
                'leave_types.code as leave_type_code',
                'managers.first_name as manager_first_name',
                'managers.last_name as manager_last_name'
            )
            ->join('employees', 'leave_requests.employee_id', '=', 'employees.id')
            ->join('leave_types', 'leave_requests.leave_type_id', '=', 'leave_types.id')
            ->leftJoin('employees as managers', 'employees.manager_id', '=', 'managers.id')
            ->where('leave_requests.id', $id)
            ->firstOrFail();

        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();

        $canView = false;

        if ($user->isAdmin() || $user->isHR()) {
            $canView = true;
        } elseif ($user->isManager() && $employee) {
            $teamIds = Employee::where('manager_id', $employee->id)->pluck('id');
            if ($leave->employee_id == $employee->id || $teamIds->contains($leave->employee_id)) {
                $canView = true;
            }
        } elseif ($employee && $leave->employee_id == $employee->id) {
            $canView = true;
        }

        if (!$canView) {
            return redirect()->back()->with('error', 'You do not have permission to view this leave.');
        }

        return view('leave.employee.view', compact('leave'));
    }

    /**
     * Cancel Leave
     */
    public function cancel($id)
    {
        $employee = Employee::where('email', auth()->user()->email)->first();
            
        $leave = LeaveRequest::where('id', $id)
            ->where('employee_id', $employee->id)
            ->firstOrFail();

        if (!$leave->canBeCancelled()) {
            return redirect()->back()->with('error', 'This leave cannot be cancelled.');
        }

        $leave->status = 'cancelled';
        $leave->save();

        return redirect()->route('leave.my-leaves')->with('success', 'Leave cancelled successfully!');
    }
  
    /*
      Leave Balance - Using LEFT JOIN
     */
    public function leaveBalance()
    {
        $balances = DB::table('employees')
            ->leftJoin('leave_balances', function($join) {
                $join->on('employees.id', '=', 'leave_balances.employee_id')
                     ->where('leave_balances.year', now()->year);
            })
            ->leftJoin('leave_types', 'leave_balances.leave_type_id', '=', 'leave_types.id')
            ->where('employees.email', auth()->user()->email)
            ->select(
                'employees.id as employee_id',
                'employees.first_name',
                'employees.last_name',
                'leave_balances.id as balance_id',
                'leave_balances.allocated_days',
                'leave_balances.used_days',
                'leave_balances.remaining_days',
                'leave_types.name as leave_type_name',
                'leave_types.id as leave_type_id'
            )
            ->get();

        return view('leave.employee.balance', compact('balances'));
    }
 
    //MANAGER SECTION
     
    public function managerDashboard()
    {
        $manager = Employee::where('email', auth()->user()->email)->first();
            
        if (!$manager) {
            return redirect()->back()->with('error', 'Manager record not found.');
        }

        $teamIds = Employee::where('manager_id', $manager->id)->pluck('id');

        $stats = LeaveRequest::select(
                DB::raw("COUNT(*) as total"),
                DB::raw("SUM(CASE WHEN leave_requests.status = 'pending_manager' THEN 1 ELSE 0 END) as pending_manager"),
                DB::raw("SUM(CASE WHEN leave_requests.status = 'pending_hr' THEN 1 ELSE 0 END) as pending_hr"),
                DB::raw("SUM(CASE WHEN leave_requests.status = 'pending_admin' THEN 1 ELSE 0 END) as pending_admin"),
                DB::raw("SUM(CASE WHEN leave_requests.status = 'approved' THEN 1 ELSE 0 END) as approved"),
                DB::raw("SUM(CASE WHEN leave_requests.status IN ('manager_rejected', 'hr_rejected', 'admin_rejected') THEN 1 ELSE 0 END) as rejected")
            )
            ->join('employees', 'leave_requests.employee_id', '=', 'employees.id')
            ->whereIn('leave_requests.employee_id', $teamIds)
            ->first();

        $pendingRequests = LeaveRequest::select(
                'leave_requests.*',
                'employees.id as employee_id',
                'employees.first_name',
                'employees.last_name',
                'employees.email',
                'employees.employee_code',
                'leave_types.id as leave_type_id',
                'leave_types.name as leave_type_name'
            )
            ->join('employees', 'leave_requests.employee_id', '=', 'employees.id')
            ->join('leave_types', 'leave_requests.leave_type_id', '=', 'leave_types.id')
            ->whereIn('leave_requests.employee_id', $teamIds)
            ->where('leave_requests.status', 'pending_manager')
            ->latest('leave_requests.created_at')
            ->paginate(10);

        return view('leave.manager.dashboard', [
            'pendingManager' => $stats->pending_manager ?? 0,
            'pendingHR' => $stats->pending_hr ?? 0,
            'pendingAdmin' => $stats->pending_admin ?? 0,
            'approved' => $stats->approved ?? 0,
            'rejected' => $stats->rejected ?? 0,
            'pendingRequests' => $pendingRequests
        ]);
    }

    public function managerPending()
    {
        $manager = Employee::where('email', auth()->user()->email)->first();
            
        if (!$manager) {
            return redirect()->back()->with('error', 'Manager record not found.');
        }

        $teamIds = Employee::where('manager_id', $manager->id)->pluck('id');

        $leaves = LeaveRequest::select(
                'leave_requests.*',
                'employees.id as employee_id',
                'employees.first_name',
                'employees.last_name',
                'employees.email',
                'employees.employee_code',
                'leave_types.id as leave_type_id',
                'leave_types.name as leave_type_name'
            )
            ->join('employees', 'leave_requests.employee_id', '=', 'employees.id')
            ->join('leave_types', 'leave_requests.leave_type_id', '=', 'leave_types.id')
            ->whereIn('leave_requests.employee_id', $teamIds)
            ->where('leave_requests.status', 'pending_manager')
            ->latest('leave_requests.created_at')
            ->paginate(10);

        return view('leave.manager.pending', compact('leaves'));
    }

    public function managerApprove($id)
    {
        $manager = Employee::where('email', auth()->user()->email)->first();
            
        if (!$manager) {
            return redirect()->back()->with('error', 'Manager record not found.');
        }

        $leave = LeaveRequest::with('employee')
            ->where('id', $id)
            ->where('status', 'pending_manager')
            ->firstOrFail();

        if ($leave->employee->manager_id != $manager->id) {
            return redirect()->back()->with('error', 'You are not authorized to approve this leave.');
        }

        $leave->status = 'pending_hr';
        $leave->manager_approved_at = now();
        $leave->save();

        LeaveApproval::create([
            'leave_request_id' => $leave->id,
            'approver_id' => auth()->id(),
            'approver_role' => 'Manager',
            'action' => 'Approve',
            'comment' => request('comment'),
            'approved_at' => now()
        ]);

        DB::table('activity_logs')->insert([
            'employee_name' => $leave->employee->first_name . ' ' . $leave->employee->last_name,
            'action' => 'Manager approved leave and forwarded to HR',
            'performed_by' => auth()->user()->name,
            'performed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('leave.manager.pending')
            ->with('success', 'Leave forwarded to HR for final approval.');
    }

    public function managerReject(Request $request, $id)
    {
        $request->validate(['comment' => 'required|string|min:5']);

        $manager = Employee::where('email', auth()->user()->email)->first();
            
        if (!$manager) {
            return redirect()->back()->with('error', 'Manager record not found.');
        }

        $leave = LeaveRequest::with('employee')
            ->where('id', $id)
            ->where('status', 'pending_manager')
            ->firstOrFail();

        if ($leave->employee->manager_id != $manager->id) {
            return redirect()->back()->with('error', 'You are not authorized to reject this leave.');
        }

        $leave->status = 'manager_rejected';
        $leave->manager_comment = $request->comment;
        $leave->save();

        LeaveApproval::create([
            'leave_request_id' => $leave->id,
            'approver_id' => auth()->id(),
            'approver_role' => 'Manager',
            'action' => 'Reject',
            'comment' => $request->comment,
            'approved_at' => now()
        ]);

        DB::table('activity_logs')->insert([
            'employee_name' => $leave->employee->first_name . ' ' . $leave->employee->last_name,
            'action' => 'Manager rejected leave',
            'performed_by' => auth()->user()->name,
            'performed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('leave.manager.pending')
            ->with('success', 'Leave rejected.');
    }
  
    /**
     * HR SECTION
     */

    public function hrDashboard()
    {
        $stats = LeaveRequest::select(
                DB::raw("COUNT(*) as total"),
                DB::raw("SUM(CASE WHEN status = 'pending_hr' THEN 1 ELSE 0 END) as pending_hr"),
                DB::raw("SUM(CASE WHEN status = 'pending_admin' THEN 1 ELSE 0 END) as pending_admin"),
                DB::raw("SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved"),
                DB::raw("SUM(CASE WHEN status IN ('manager_rejected', 'hr_rejected', 'admin_rejected') THEN 1 ELSE 0 END) as rejected")
            )
            ->first();

        $pendingRequests = LeaveRequest::select(
                'leave_requests.*',
                'employees.id as employee_id',
                'employees.first_name',
                'employees.last_name',
                'employees.email',
                'employees.employee_code',
                'leave_types.id as leave_type_id',
                'leave_types.name as leave_type_name'
            )
            ->join('employees', 'leave_requests.employee_id', '=', 'employees.id')
            ->join('leave_types', 'leave_requests.leave_type_id', '=', 'leave_types.id')
            ->where('leave_requests.status', 'pending_hr')
            ->latest('leave_requests.created_at')
            ->paginate(10);

        return view('leave.hr.dashboard', [
            'pendingHR' => $stats->pending_hr ?? 0,
            'pendingAdmin' => $stats->pending_admin ?? 0,
            'approved' => $stats->approved ?? 0,
            'rejected' => $stats->rejected ?? 0,
            'total' => $stats->total ?? 0,
            'pendingRequests' => $pendingRequests
        ]);
    }

    public function hrPending()
    {
        $leaves = LeaveRequest::select(
                'leave_requests.*',
                'employees.id as employee_id',
                'employees.first_name',
                'employees.last_name',
                'employees.email',
                'employees.employee_code',
                'leave_types.id as leave_type_id',
                'leave_types.name as leave_type_name'
            )
            ->join('employees', 'leave_requests.employee_id', '=', 'employees.id')
            ->join('leave_types', 'leave_requests.leave_type_id', '=', 'leave_types.id')
            ->where('leave_requests.status', 'pending_hr')
            ->latest('leave_requests.created_at')
            ->paginate(10);

        return view('leave.hr.pending', compact('leaves'));
    }

    public function hrApprove($id)
    {
        $leave = LeaveRequest::where('id', $id)->where('status', 'pending_hr')->firstOrFail();

        $this->finalApprove($leave, 'HR');

        DB::table('activity_logs')->insert([
            'employee_name' => $leave->employee->first_name . ' ' . $leave->employee->last_name,
            'action' => 'HR final approved leave',
            'performed_by' => auth()->user()->name,
            'performed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('leave.hr.pending')
            ->with('success', 'Leave approved successfully! Balance and Attendance updated.');
    }

    public function hrReject(Request $request, $id)
    {
        $request->validate(['comment' => 'required|string|min:5']);

        $leave = LeaveRequest::where('id', $id)->where('status', 'pending_hr')->firstOrFail();

        $leave->status = 'hr_rejected';
        $leave->hr_comment = $request->comment;
        $leave->save();

        LeaveApproval::create([
            'leave_request_id' => $leave->id,
            'approver_id' => auth()->id(),
            'approver_role' => 'HR',
            'action' => 'Reject',
            'comment' => $request->comment,
            'approved_at' => now()
        ]);

        DB::table('activity_logs')->insert([
            'employee_name' => $leave->employee->first_name . ' ' . $leave->employee->last_name,
            'action' => 'HR rejected leave',
            'performed_by' => auth()->user()->name,
            'performed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('leave.hr.pending')
            ->with('success', 'Leave rejected.');
    }

    public function hrAllLeaves(Request $request)
    {
        $query = LeaveRequest::select(
                'leave_requests.*',
                'employees.id as employee_id',
                'employees.first_name',
                'employees.last_name',
                'employees.email as employee_email',
                'employees.employee_code',
                'leave_types.id as leave_type_id',
                'leave_types.name as leave_type_name'
            )
            ->join('employees', 'leave_requests.employee_id', '=', 'employees.id')
            ->join('leave_types', 'leave_requests.leave_type_id', '=', 'leave_types.id');

        if ($request->filled('status')) {
            $query->where('leave_requests.status', $request->status);
        }
        if ($request->filled('employee_name')) {
            $query->where(function($q) use ($request) {
                $q->where('employees.first_name', 'LIKE', "%{$request->employee_name}%")
                  ->orWhere('employees.last_name', 'LIKE', "%{$request->employee_name}%")
                  ->orWhere('employees.email', 'LIKE', "%{$request->employee_name}%");
            });
        }
        if ($request->filled('from_date')) {
            $query->where('leave_requests.from_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('leave_requests.to_date', '<=', $request->to_date);
        }

        $leaves = $query->latest('leave_requests.created_at')->paginate(15);
        return view('leave.hr.all-leaves', compact('leaves'));
    }

    public function hrLeaveBalances(Request $request)
    {
        $query = DB::table('employees')
            ->leftJoin('leave_balances', function($join) use ($request) {
                $join->on('employees.id', '=', 'leave_balances.employee_id')
                     ->where('leave_balances.year', $request->year ?? now()->year);
            })
            ->leftJoin('leave_types', 'leave_balances.leave_type_id', '=', 'leave_types.id')
            ->select(
                'employees.id as employee_id',
                'employees.first_name',
                'employees.last_name',
                'employees.email',
                'leave_balances.id as balance_id',
                'leave_balances.allocated_days',
                'leave_balances.used_days',
                'leave_balances.remaining_days',
                'leave_balances.year',
                'leave_types.name as leave_type_name',
                'leave_types.id as leave_type_id'
            );

        if ($request->filled('employee_name')) {
            $query->where(function($q) use ($request) {
                $q->where('employees.first_name', 'LIKE', "%{$request->employee_name}%")
                  ->orWhere('employees.last_name', 'LIKE', "%{$request->employee_name}%")
                  ->orWhere('employees.email', 'LIKE', "%{$request->employee_name}%");
            });
        }

        if ($request->filled('year')) {
            $query->where('leave_balances.year', $request->year);
        }

        $balances = $query->paginate(15);
        $years = LeaveBalance::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');

        return view('leave.hr.balances', compact('balances', 'years'));
    }

    /**
     * ADMIN SECTION
      */
    public function adminDashboard()
    {
        $stats = LeaveRequest::select(
                DB::raw("COUNT(*) as total"),
                DB::raw("SUM(CASE WHEN status = 'pending_admin' THEN 1 ELSE 0 END) as pending_admin"),
                DB::raw("SUM(CASE WHEN status = 'pending_hr' THEN 1 ELSE 0 END) as pending_hr"),
                DB::raw("SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved"),
                DB::raw("SUM(CASE WHEN status IN ('manager_rejected', 'hr_rejected', 'admin_rejected') THEN 1 ELSE 0 END) as rejected")
            )
            ->first();

        $pendingRequests = LeaveRequest::select(
                'leave_requests.*',
                'employees.id as employee_id',
                'employees.first_name',
                'employees.last_name',
                'employees.email',
                'employees.employee_code',
                'leave_types.id as leave_type_id',
                'leave_types.name as leave_type_name'
            )
            ->join('employees', 'leave_requests.employee_id', '=', 'employees.id')
            ->join('leave_types', 'leave_requests.leave_type_id', '=', 'leave_types.id')
            ->where('leave_requests.status', 'pending_admin')
            ->latest('leave_requests.created_at')
            ->paginate(10);

        return view('leave.admin.dashboard', [
            'pendingAdmin' => $stats->pending_admin ?? 0,
            'pendingHR' => $stats->pending_hr ?? 0,
            'approved' => $stats->approved ?? 0,
            'rejected' => $stats->rejected ?? 0,
            'total' => $stats->total ?? 0,
            'pendingRequests' => $pendingRequests
        ]);
    }

    public function adminPending()
    {
        $leaves = LeaveRequest::select(
                'leave_requests.*',
                'employees.id as employee_id',
                'employees.first_name',
                'employees.last_name',
                'employees.email',
                'employees.employee_code',
                'leave_types.id as leave_type_id',
                'leave_types.name as leave_type_name'
            )
            ->join('employees', 'leave_requests.employee_id', '=', 'employees.id')
            ->join('leave_types', 'leave_requests.leave_type_id', '=', 'leave_types.id')
            ->where('leave_requests.status', 'pending_admin')
            ->latest('leave_requests.created_at')
            ->paginate(10);

        return view('leave.admin.pending', compact('leaves'));
    }

    public function adminApprove($id)
    {
        $leave = LeaveRequest::where('id', $id)->where('status', 'pending_admin')->firstOrFail();

        DB::transaction(function () use ($leave) {
            $this->finalApprove($leave, 'Admin');
        });

        DB::table('activity_logs')->insert([
            'employee_name' => $leave->employee->first_name . ' ' . $leave->employee->last_name,
            'action' => 'Admin final approved HR leave',
            'performed_by' => auth()->user()->name,
            'performed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('leave.admin.pending')
            ->with('success', 'Leave approved successfully! Balance and Attendance updated.');
    }

    public function adminReject(Request $request, $id)
    {
        $request->validate(['comment' => 'required|string|min:5']);

        $leave = LeaveRequest::where('id', $id)->where('status', 'pending_admin')->firstOrFail();

        $leave->status = 'admin_rejected';
        $leave->admin_comment = $request->comment;
        $leave->save();

        LeaveApproval::create([
            'leave_request_id' => $leave->id,
            'approver_id' => auth()->id(),
            'approver_role' => 'Admin',
            'action' => 'Reject',
            'comment' => $request->comment,
            'approved_at' => now()
        ]);

        DB::table('activity_logs')->insert([
            'employee_name' => $leave->employee->first_name . ' ' . $leave->employee->last_name,
            'action' => 'Admin rejected HR leave',
            'performed_by' => auth()->user()->name,
            'performed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('leave.admin.pending')
            ->with('success', 'Leave rejected.');
    }

    public function adminBalances(Request $request)
    {
        $query = DB::table('employees')
            ->leftJoin('leave_balances', function($join) use ($request) {
                $join->on('employees.id', '=', 'leave_balances.employee_id')
                     ->where('leave_balances.year', $request->year ?? now()->year);
            })
            ->leftJoin('leave_types', 'leave_balances.leave_type_id', '=', 'leave_types.id')
            ->select(
                'employees.id as employee_id',
                'employees.first_name',
                'employees.last_name',
                'employees.email',
                'leave_balances.id as balance_id',
                'leave_balances.allocated_days',
                'leave_balances.used_days',
                'leave_balances.remaining_days',
                'leave_balances.year',
                'leave_types.name as leave_type_name',
                'leave_types.id as leave_type_id'
            );

        if ($request->filled('employee_name')) {
            $query->where(function($q) use ($request) {
                $q->where('employees.first_name', 'LIKE', "%{$request->employee_name}%")
                  ->orWhere('employees.last_name', 'LIKE', "%{$request->employee_name}%")
                  ->orWhere('employees.email', 'LIKE', "%{$request->employee_name}%");
            });
        }

        $balances = $query->paginate(15);
        $years = LeaveBalance::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');

        return view('leave.admin.balances', compact('balances', 'years'));
    }

    public function adminBalancesAssign(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'year' => 'required|integer|min:2000',
            'allocated_days' => 'required|integer|min:0'
        ]);

        LeaveBalance::updateOrCreate(
            [
                'employee_id' => $request->employee_id,
                'leave_type_id' => $request->leave_type_id,
                'year' => $request->year
            ],
            [
                'allocated_days' => $request->allocated_days,
                'used_days' => 0,
                'remaining_days' => $request->allocated_days
            ]
        );

        return redirect()->route('leave.admin.balances')
            ->with('success', 'Leave balance assigned successfully!');
    }

    /**
     * COMPLEX REPORT
     */
    public function leaveReport(Request $request)
    {
        $reports = DB::table('leave_requests')
            ->select(
                'employees.first_name',
                'employees.last_name',
                'employees.email',
                'leave_types.name as leave_type',
                'leave_requests.from_date',
                'leave_requests.to_date',
                'leave_requests.total_days',
                'leave_requests.status',
                'users.name as manager_name',
                'leave_approvals.approver_role',
                'leave_approvals.approved_at',
                'leave_balances.allocated_days',
                'leave_balances.remaining_days'
            )
            ->join('employees', 'leave_requests.employee_id', '=', 'employees.id')
            ->leftJoin('leave_types', 'leave_requests.leave_type_id', '=', 'leave_types.id')
            ->leftJoin('users', 'employees.manager_id', '=', 'users.id')
            ->leftJoin('leave_approvals', function($join) {
                $join->on('leave_requests.id', '=', 'leave_approvals.leave_request_id')
                     ->where('leave_approvals.approver_role', 'Admin');
            })
            ->leftJoin('leave_balances', function($join) {
                $join->on('employees.id', '=', 'leave_balances.employee_id')
                     ->where('leave_balances.year', now()->year)
                     ->where('leave_balances.leave_type_id', '=', DB::raw('leave_requests.leave_type_id'));
            });

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $reports->whereBetween('leave_requests.from_date', [$request->from_date, $request->to_date]);
        }

        if ($request->filled('status')) {
            $reports->where('leave_requests.status', $request->status);
        }

        $reports = $reports->orderBy('leave_requests.from_date', 'desc')->get();

        return view('leave.reports.index', compact('reports'));
    }

    /**
     * Final Approve Logic (Balance + Attendance)
     */
    private function finalApprove($leave, $role)
    {
        $leave->status = 'approved';
        $leave->save();

        LeaveApproval::create([
            'leave_request_id' => $leave->id,
            'approver_id' => auth()->id(),
            'approver_role' => $role,
            'action' => 'Approve',
            'comment' => request('comment'),
            'approved_at' => now()
        ]);

        $balance = LeaveBalance::where('employee_id', $leave->employee_id)
            ->where('leave_type_id', $leave->leave_type_id)
            ->where('year', now()->year)
            ->first();

        if ($balance) {
            $balance->used_days += $leave->total_days;
            $balance->remaining_days = $balance->allocated_days - $balance->used_days;
            $balance->save();
        }

        $date = $leave->from_date;
        while ($date <= $leave->to_date) {
            $attendance = Attendance::where('employee_id', $leave->employee_id)
                ->whereDate('date', $date)
                ->first();

            if ($attendance) {
                $attendance->status = 'Leave';
                $attendance->save();
            } else {
                Attendance::create([
                    'employee_id' => $leave->employee_id,
                    'date' => $date,
                    'status' => 'Leave'
                ]);
            }
            $date = $date->addDay();
        }
    }

    /**
     * Calculate total days between two dates
     */
    private function calculateDays($from, $to)
    {
        return \Carbon\Carbon::parse($from)->diffInDays(\Carbon\Carbon::parse($to)) + 1;
    }
}
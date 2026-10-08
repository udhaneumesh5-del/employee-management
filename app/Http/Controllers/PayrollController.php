<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use App\Models\SalaryStructure;
use App\Models\PayrollAdjustment;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Reimbursement;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PayrollController extends Controller
{
    // DASHBOARD
    /**
     * Payroll Dashboard - Role Based
     */
    public function dashboard()
    {
        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();

        // Employee / Manager - View Own Payroll
        if ($user->isEmployee() || $user->isManager()) {
            $payrolls = Payroll::where('employee_id', $employee->id)
                ->orderBy('payroll_year', 'desc')
                ->orderBy('payroll_month', 'desc')
                ->paginate(10);

            $stats = [
                'total' => Payroll::where('employee_id', $employee->id)->count(),
                'paid' => Payroll::where('employee_id', $employee->id)->where('status', 'Paid')->count(),
                'pending' => Payroll::where('employee_id', $employee->id)->whereIn('status', ['Pending', 'Processed'])->count(),
                'total_earned' => Payroll::where('employee_id', $employee->id)->where('status', 'Paid')->sum('net_salary')
            ];

            return view('payroll.employee.dashboard', compact('payrolls', 'stats'));
        }

        // HR - Manage Employee & Manager Payroll
        if ($user->isHR()) {
            $stats = [
                'total_employees' => Employee::where('status', 'Active')->count(),
                'this_month_payroll' => Payroll::where('payroll_month', now()->month)
                    ->where('payroll_year', now()->year)
                    ->sum('net_salary'),
                'total_pending' => Payroll::where('status', 'Pending')->count(),
                'total_paid' => Payroll::where('status', 'Paid')->count(),
                'own_payroll' => Payroll::where('employee_id', $employee->id)
                    ->orderBy('payroll_year', 'desc')
                    ->orderBy('payroll_month', 'desc')
                    ->first()
            ];

            $employeeRoles = ['Employee', 'Manager'];
            $payrolls = Payroll::with(['employee'])
                ->whereHas('employee.user', function ($q) use ($employeeRoles) {
                    $q->whereIn('role', $employeeRoles);
                })
                ->orderBy('payroll_year', 'desc')
                ->orderBy('payroll_month', 'desc')
                ->paginate(15);

            return view('payroll.hr.dashboard', compact('payrolls', 'stats'));
        }

        // Admin - Manage HR Payroll
        if ($user->isAdmin()) {
            $stats = [
                'pending_hr_payroll' => Payroll::whereHas('employee.user', function ($q) {
                    $q->where('role', 'HR');
                })->where('status', 'Pending')->count(),
                'total_hr_payroll' => Payroll::whereHas('employee.user', function ($q) {
                    $q->where('role', 'HR');
                })->sum('net_salary'),
                'paid_hr_payroll' => Payroll::whereHas('employee.user', function ($q) {
                    $q->where('role', 'HR');
                })->where('status', 'Paid')->count(),
            ];

            $payrolls = Payroll::with(['employee'])
                ->whereHas('employee.user', function ($q) {
                    $q->where('role', 'HR');
                })
                ->orderBy('payroll_year', 'desc')
                ->orderBy('payroll_month', 'desc')
                ->paginate(15);

            return view('payroll.admin.dashboard', compact('payrolls', 'stats'));
        }

        return redirect()->route('dashboard');
    }

    // SALARY STRUCTURE
    /**
     * Salary Structure List (Admin & HR)
     */
    public function salaryStructure()
    {
        $user = auth()->user();
        
        if (!$user->isAdmin() && !$user->isHR()) {
            abort(403);
        }

        $employees = Employee::where('status', 'Active')->get();
        $structures = SalaryStructure::with(['employee'])->latest()->paginate(15);

        return view('payroll.salary-structure.index', compact('employees', 'structures'));
    }

    /**
     * Store Salary Structure
     */
    public function storeSalaryStructure(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->isAdmin() && !$user->isHR()) {
            abort(403);
        }

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'basic_salary' => 'required|numeric|min:0',
            'hra' => 'nullable|numeric|min:0',
            'conveyance_allowance' => 'nullable|numeric|min:0',
            'medical_allowance' => 'nullable|numeric|min:0',
            'special_allowance' => 'nullable|numeric|min:0',
            'other_allowance' => 'nullable|numeric|min:0',
            'pf_enabled' => 'boolean',
            'pf_percentage' => 'nullable|numeric|min:0|max:100',
            'professional_tax' => 'nullable|numeric|min:0',
            'other_deduction' => 'nullable|numeric|min:0',
            'effective_from' => 'required|date'
        ]);

        // Deactivate old
        SalaryStructure::where('employee_id', $request->employee_id)
            ->where('status', 'Active')
            ->update(['status' => 'Inactive', 'effective_to' => now()]);

        SalaryStructure::create([
            'employee_id' => $request->employee_id,
            'basic_salary' => $request->basic_salary,
            'hra' => $request->hra ?? 0,
            'conveyance_allowance' => $request->conveyance_allowance ?? 0,
            'medical_allowance' => $request->medical_allowance ?? 0,
            'special_allowance' => $request->special_allowance ?? 0,
            'other_allowance' => $request->other_allowance ?? 0,
            'pf_enabled' => $request->pf_enabled ?? true,
            'pf_percentage' => $request->pf_percentage ?? 12,
            'professional_tax' => $request->professional_tax ?? 200,
            'other_deduction' => $request->other_deduction ?? 0,
            'effective_from' => $request->effective_from,
            'status' => 'Active'
        ]);

        DB::table('activity_logs')->insert([
            'employee_name' => $user->name,
            'action' => "Created salary structure for employee ID: {$request->employee_id}",
            'performed_by' => $user->name,
            'performed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('payroll.salary-structure')
            ->with('success', 'Salary structure created successfully!');
    }

    /**
     * Edit Salary Structure
     */
    public function editSalaryStructure($id)
    {
        $structure = SalaryStructure::with('employee')->findOrFail($id);
        $employees = Employee::where('status', 'Active')->get();
        return view('payroll.salary-structure.edit', compact('structure', 'employees'));
    }

    /**
     * Update Salary Structure
     */
    public function updateSalaryStructure(Request $request, $id)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->isHR()) {
            abort(403);
        }

        $structure = SalaryStructure::findOrFail($id);

        $request->validate([
            'basic_salary' => 'required|numeric|min:0',
            'hra' => 'nullable|numeric|min:0',
            'conveyance_allowance' => 'nullable|numeric|min:0',
            'medical_allowance' => 'nullable|numeric|min:0',
            'special_allowance' => 'nullable|numeric|min:0',
            'other_allowance' => 'nullable|numeric|min:0',
            'pf_percentage' => 'nullable|numeric|min:0|max:100',
            'professional_tax' => 'nullable|numeric|min:0',
            'other_deduction' => 'nullable|numeric|min:0',
            'effective_from' => 'required|date'
        ]);

        $structure->update([
            'basic_salary' => $request->basic_salary,
            'hra' => $request->hra ?? 0,
            'conveyance_allowance' => $request->conveyance_allowance ?? 0,
            'medical_allowance' => $request->medical_allowance ?? 0,
            'special_allowance' => $request->special_allowance ?? 0,
            'other_allowance' => $request->other_allowance ?? 0,
            'pf_enabled' => $request->has('pf_enabled'),
            'pf_percentage' => $request->pf_percentage ?? 12,
            'professional_tax' => $request->professional_tax ?? 200,
            'other_deduction' => $request->other_deduction ?? 0,
            'effective_from' => $request->effective_from
        ]);

        DB::table('activity_logs')->insert([
            'employee_name' => $user->name,
            'action' => "Updated salary structure #{$id}",
            'performed_by' => $user->name,
            'performed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('payroll.salary-structure')
            ->with('success', 'Salary structure updated!');
    }

    // PAYROLL GENERATION
    /**
     * Generate Payroll Page
     */
    public function generate()
    {
        $user = auth()->user();
        
        if (!$user->isAdmin() && !$user->isHR()) {
            abort(403);
        }

        if ($user->isHR()) {
            $employees = Employee::where('status', 'Active')
                ->whereHas('user', function ($q) {
                    $q->whereIn('role', ['Employee', 'Manager']);
                })
                ->get();
        } else {
            $employees = Employee::where('status', 'Active')
                ->whereHas('user', function ($q) {
                    $q->where('role', 'HR');
                })
                ->get();
        }

        return view('payroll.generate', compact('employees'));
    }

    /**
     * Process Payroll Generation
     */
    public function processGenerate(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->isAdmin() && !$user->isHR()) {
            abort(403);
        }

        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2100',
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id'
        ]);

        $month = $request->month;
        $year = $request->year;
        $generatedCount = 0;
        $errors = [];

        foreach ($request->employee_ids as $employeeId) {
            $employee = Employee::with('user')->find($employeeId);

            if ($user->isHR() && !in_array($employee->user->role ?? '', ['Employee', 'Manager', "HR"])) {
                $errors[] = "Employee ID {$employeeId} not allowed for HR.";
                continue;
            }

            if ($user->isAdmin() && ($employee->user->role ?? '') !== 'HR') {
                $errors[] = "Employee ID {$employeeId} not allowed for Admin.";
                continue;
            }

            $exists = Payroll::where('employee_id', $employeeId)
                ->where('payroll_month', $month)
                ->where('payroll_year', $year)
                ->exists();

            if ($exists) {
                $errors[] = "Payroll already exists for Employee ID: {$employeeId}";
                continue;
            }

            $result = $this->calculatePayroll($employeeId, $month, $year, $user);
            
            if ($result['success']) {
                $generatedCount++;
            } else {
                $errors[] = $result['message'];
            }
        }

        $message = "Payroll generated for {$generatedCount} employee(s).";
        if (!empty($errors)) {
            $message .= " Errors: " . implode(', ', $errors);
        }

        DB::table('activity_logs')->insert([
            'employee_name' => $user->name,
            'action' => "Generated payroll for {$generatedCount} employees - {$month}/{$year}",
            'performed_by' => $user->name,
            'performed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('payroll.index')->with('success', $message);
    }

    /**
     * Calculate Payroll
     */
    private function calculatePayroll($employeeId, $month, $year, $user)
    {
        try {
            DB::beginTransaction();

            $employee = Employee::find($employeeId);
            if (!$employee) {
                return ['success' => false, 'message' => 'Employee not found.'];
            }

            $structure = SalaryStructure::where('employee_id', $employeeId)
                ->where('status', 'Active')
                ->where('effective_from', '<=', "{$year}-{$month}-01")
                ->first();

            if (!$structure) {
                return ['success' => false, 'message' => "No salary structure for Employee ID: {$employeeId}"];
            }

            $startDate = Carbon::create($year, $month, 1);
            $endDate = Carbon::create($year, $month, $startDate->daysInMonth);
            $totalWorkingDays = $startDate->daysInMonth;

            $attendances = Attendance::where('employee_id', $employeeId)
                ->whereBetween('date', [$startDate, $endDate])
                ->get();

            $presentDays = $attendances->where('status', 'Present')->count();
            $absentDays = $attendances->where('status', 'Absent')->count();

            $leaveRequests = LeaveRequest::with('leaveType')
                ->where('employee_id', $employeeId)
                ->where(function ($q) {
                    $q->where('final_status', 'approved')
                      ->orWhere('status', 'approved');
                })
                ->where(function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('from_date', [$startDate, $endDate])
                      ->orWhereBetween('to_date', [$startDate, $endDate]);
                })
                ->get();

            $paidLeaveDays = 0;
            $unpaidLeaveDays = 0;

            foreach ($leaveRequests as $leave) {
                $isPaid = $leave->leaveType->is_paid ?? true;
                if ($isPaid) {
                    $paidLeaveDays += $leave->total_days;
                } else {
                    $unpaidLeaveDays += $leave->total_days;
                }
            }

            $reimbursementAmount = Reimbursement::where('requester_id', $employeeId)
                ->where('status', 'approved_final')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('amount');

            $perDaySalary = $structure->gross_salary / $totalWorkingDays;

            $absentDeduction = $absentDays * $perDaySalary;
            $unpaidLeaveDeduction = $unpaidLeaveDays * $perDaySalary;
            
            $pfDeduction = 0;
            if ($structure->pf_enabled) {
                $pfDeduction = ($structure->basic_salary * $structure->pf_percentage) / 100;
            }

            $grossSalary = $structure->gross_salary;
            $totalDeductions = $absentDeduction + $unpaidLeaveDeduction 
                + $pfDeduction + $structure->professional_tax + $structure->other_deduction;
            $netSalary = $grossSalary - $totalDeductions;
            $totalPayable = $netSalary + $reimbursementAmount;

            Payroll::create([
                'employee_id' => $employeeId,
                'payroll_month' => $month,
                'payroll_year' => $year,
                'total_working_days' => $totalWorkingDays,
                'present_days' => $presentDays,
                'absent_days' => $absentDays,
                'paid_leave_days' => $paidLeaveDays,
                'unpaid_leave_days' => $unpaidLeaveDays,
                'basic_salary' => $structure->basic_salary,
                'hra' => $structure->hra,
                'conveyance_allowance' => $structure->conveyance_allowance,
                'medical_allowance' => $structure->medical_allowance,
                'special_allowance' => $structure->special_allowance,
                'other_allowance' => $structure->other_allowance,
                'gross_salary' => $grossSalary,
                'absent_deduction' => $absentDeduction,
                'unpaid_leave_deduction' => $unpaidLeaveDeduction,
                'pf_deduction' => $pfDeduction,
                'professional_tax' => $structure->professional_tax,
                'other_deduction' => $structure->other_deduction,
                'total_deductions' => $totalDeductions,
                'reimbursement_amount' => $reimbursementAmount,
                'net_salary' => $netSalary,
                'total_payable' => $totalPayable,
                'status' => 'Pending',
                'generated_by' => $user->id,
                'generated_at' => now()
            ]);

            DB::commit();
            return ['success' => true, 'message' => "Payroll generated for {$employeeId}"];

        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // PAYROLL LISTING & DETAILS
    /**
     * Payroll List
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();
        
        $query = Payroll::with(['employee.user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('month')) {
            $query->where('payroll_month', $request->month);
        }
        if ($request->filled('year')) {
            $query->where('payroll_year', $request->year);
        }

        if ($user->isEmployee() || $user->isManager()) {
            $query->where('employee_id', $employee->id);
        } elseif ($user->isHR()) {
            $query->whereHas('employee.user', function ($q) {
                $q->whereIn('role', ['Employee', 'Manager', "HR"]);
            });
        } elseif ($user->isAdmin()) {
            $query->whereHas('employee.user', function ($q) {
                $q->where('role', 'HR');
            });
        }

        $payrolls = $query->orderBy('payroll_year', 'desc')
            ->orderBy('payroll_month', 'desc')
            ->paginate(15);

        return view('payroll.index', compact('payrolls'));
    }

    /**
     * Show Payroll Details
     */
    public function show($id)
    {
        $payroll = Payroll::with(['employee.user', 'generatedBy', 'approvedBy', 'paidBy', 'adjustments'])
            ->findOrFail($id);

        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();

        if ($user->isEmployee() || $user->isManager()) {
            if ($payroll->employee_id != $employee->id) {
                abort(403, 'Unauthorized.');
            }
        }

        return view('payroll.show', compact('payroll'));
    }

    // PAYROLL STATUS CHANGES
    /**
     * Approve Payroll
     */
    public function approve($id)
    {
        $user = auth()->user();
        $payroll = Payroll::with('employee.user')->findOrFail($id);

        if (!$user->canProcessPayroll($payroll)) {
            abort(403, 'You cannot approve this payroll.');
        }

        if ($payroll->status != 'Pending') {
            return redirect()->back()->with('error', 'Only pending payroll can be approved.');
        }

        $payroll->update([
            'status' => 'Approved',
            'approved_by' => $user->id,
            'approved_at' => now()
        ]);

        DB::table('activity_logs')->insert([
            'employee_name' => $user->name,
            'action' => "Approved payroll #{$payroll->id}",
            'performed_by' => $user->name,
            'performed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('payroll.index')->with('success', 'Payroll approved!');
    }

    /**
     * Process Payroll (Approved → Processed)
     */
    public function process($id)
    {
        $user = auth()->user();
        $payroll = Payroll::with('employee.user')->findOrFail($id);

        if (!$user->canProcessPayroll($payroll)) {
            abort(403, 'You cannot process this payroll.');
        }

        if ($payroll->status != 'Approved') {
            return redirect()->back()->with('error', 'Only approved payroll can be processed.');
        }

        $payroll->update([
            'status' => 'Processed',
            'processed_by' => $user->id,
            'processed_at' => now()
        ]);

        DB::table('activity_logs')->insert([
            'employee_name' => $user->name,
            'action' => "Processed payroll #{$payroll->id}",
            'performed_by' => $user->name,
            'performed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('payroll.index')->with('success', 'Payroll processed!');
    }

    /**
     * Mark as Paid
     */
    public function markPaid(Request $request, $id)
    {
        $user = auth()->user();
        $payroll = Payroll::with('employee.user')->findOrFail($id);

        if (!$user->canProcessPayroll($payroll)) {
            abort(403, 'You cannot mark this payroll as paid.');
        }

        $request->validate([
            'payment_method' => 'required|string|max:255',
            'payment_reference' => 'nullable|string|max:255'
        ]);

        if ($payroll->status != 'Processed') {
            return redirect()->back()->with('error', 'Only processed payroll can be marked as paid.');
        }

        $payroll->update([
            'status' => 'Paid',
            'paid_by' => $user->id,
            'paid_at' => now(),
            'payment_method' => $request->payment_method,
            'payment_reference' => $request->payment_reference
        ]);

        DB::table('activity_logs')->insert([
            'employee_name' => $user->name,
            'action' => "Marked payroll #{$payroll->id} as paid",
            'performed_by' => $user->name,
            'performed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('payroll.index')->with('success', 'Payroll marked as paid!');
    }

    // PAYSLIP
    /**
     * View Payslip
     */
    public function payslip($id)
    {
        $payroll = Payroll::with(['employee.department', 'employee.user', 'adjustments'])
            ->findOrFail($id);

        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();

        if ($user->isEmployee() || $user->isManager()) {
            if ($payroll->employee_id != $employee->id) {
                abort(403, 'Unauthorized.');
            }
        }

        if ($payroll->status != 'Paid') {
            return redirect()->back()->with('error', 'Payslip available only after payment.');
        }

        return view('payroll.payslip', compact('payroll'));
    }

    /**
     * Download Payslip PDF
     */
    public function downloadPayslipPDF($id)
    {
        $payroll = Payroll::with(['employee.department', 'employee.user', 'adjustments'])
            ->findOrFail($id);

        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();

        // Authorization
        if ($user->isEmployee() || $user->isManager()) {
            if ($payroll->employee_id != $employee->id) {
                abort(403, 'Unauthorized.');
            }
        }

        if ($payroll->status != 'Paid') {
            return redirect()->back()->with('error', 'Payslip available only after payment.');
        }

        // Generate PDF using DomPDF
        // हे use statement वापरतो
        $pdf = Pdf::loadView('payroll.payslip-pdf', compact('payroll'));
        
        $filename = 'Payslip_' . ($payroll->employee->employee_code ?? $payroll->employee_id) 
                  . '_' . $payroll->month_name . '_' . $payroll->payroll_year . '.pdf';
        
        return $pdf->download($filename);
    }

    // ADJUSTMENTS
    /**
     * Add Adjustment
     */
    public function addAdjustment(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->isAdmin() && !$user->isHR()) {
            abort(403);
        }

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'adjustment_type' => 'required|in:Earning,Deduction',
            'category' => 'required|in:Bonus,Incentive,Penalty,Loan,Advance,Other',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string'
        ]);

        PayrollAdjustment::create([
            'employee_id' => $request->employee_id,
            'adjustment_type' => $request->adjustment_type,
            'category' => $request->category,
            'amount' => $request->amount,
            'description' => $request->description,
            'added_by' => $user->id
        ]);

        DB::table('activity_logs')->insert([
            'employee_name' => $user->name,
            'action' => "Added payroll adjustment for Employee ID: {$request->employee_id}",
            'performed_by' => $user->name,
            'performed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Adjustment added!');
    }

    /**
     * Adjustments List
     */
    public function adjustments(Request $request)
    {
        $user = auth()->user();

        if (!$user->isAdmin() && !$user->isHR()) {
            abort(403);
        }

        $adjustments = PayrollAdjustment::with(['employee', 'addedBy'])
            ->latest()
            ->paginate(15);

        $employees = Employee::where('status', 'Active')->get();

        return view('payroll.adjustments.index', compact('adjustments', 'employees'));
    }

    // REPORTS
    /**
     * Payroll Reports Page
     */
    public function reports(Request $request)
    {
        $user = auth()->user();

        if (!$user->isAdmin() && !$user->isHR()) {
            abort(403);
        }

        $query = Payroll::with(['employee.department', 'employee.user']);

        // Filters
        if ($request->filled('month')) {
            $query->where('payroll_month', $request->month);
        }
        if ($request->filled('year')) {
            $query->where('payroll_year', $request->year);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('department_id')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        // Role-based filter
        if ($user->isHR()) {
            $query->whereHas('employee.user', function ($q) {
                $q->whereIn('role', ['Employee', 'Manager']);
            });
        } elseif ($user->isAdmin()) {
            $query->whereHas('employee.user', function ($q) {
                $q->where('role', 'HR');
            });
        }

        // Stats
        $stats = [
            'total' => (clone $query)->count(),
            'total_gross' => (clone $query)->sum('gross_salary'),
            'total_deductions' => (clone $query)->sum('total_deductions'),
            'total_net' => (clone $query)->sum('net_salary'),
            'total_paid' => (clone $query)->where('status', 'Paid')->count(),
            'total_pending' => (clone $query)->whereIn('status', ['Pending', 'Approved', 'Processed'])->count(),
        ];

        $payrolls = $query->orderBy('payroll_year', 'desc')
            ->orderBy('payroll_month', 'desc')
            ->paginate(20);

        $departments = Department::where('status', 'Active')->get();

        return view('payroll.reports.index', compact('payrolls', 'stats', 'departments'));
    }

    /**
     * Export Reports to CSV
     */
    public function exportCSV(Request $request)
    {
        $user = auth()->user();

        if (!$user->isAdmin() && !$user->isHR()) {
            abort(403);
        }

        $query = Payroll::with(['employee.department', 'employee.user']);

        if ($request->filled('month')) {
            $query->where('payroll_month', $request->month);
        }
        if ($request->filled('year')) {
            $query->where('payroll_year', $request->year);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($user->isHR()) {
            $query->whereHas('employee.user', function ($q) {
                $q->whereIn('role', ['Employee', 'Manager']);
            });
        } elseif ($user->isAdmin()) {
            $query->whereHas('employee.user', function ($q) {
                $q->where('role', 'HR');
            });
        }

        $payrolls = $query->get();

        $filename = 'payroll_report_' . date('Y_m_d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($payrolls) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                'Employee Code', 'Employee Name', 'Department', 'Role',
                'Month', 'Year', 'Working Days', 'Present', 'Absent',
                'Gross Salary', 'Total Deductions', 'Net Salary',
                'Reimbursement', 'Total Payable', 'Status', 'Paid Date'
            ]);

            foreach ($payrolls as $p) {
                fputcsv($file, [
                    $p->employee->employee_code ?? '',
                    $p->employee->first_name . ' ' . $p->employee->last_name,
                    $p->employee->department->department_name ?? 'N/A',
                    $p->employee->user->role ?? 'N/A',
                    $p->month_name,
                    $p->payroll_year,
                    $p->total_working_days,
                    $p->present_days,
                    $p->absent_days,
                    $p->gross_salary,
                    $p->total_deductions,
                    $p->net_salary,
                    $p->reimbursement_amount,
                    $p->total_payable,
                    $p->status,
                    $p->paid_at?->format('d-m-Y') ?? 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
/**
 * Export Reports to PDF
 */
public function exportPDF(Request $request)
{
    $user = auth()->user();

    if (!$user->isAdmin() && !$user->isHR()) {
        abort(403);
    }

    $query = Payroll::with(['employee.department', 'employee.user']);

    if ($request->filled('month')) {
        $query->where('payroll_month', $request->month);
    }
    if ($request->filled('year')) {
        $query->where('payroll_year', $request->year);
    }
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($user->isHR()) {
        $query->whereHas('employee.user', function ($q) {
            $q->whereIn('role', ['Employee', 'Manager']);
        });
    } elseif ($user->isAdmin()) {
        $query->whereHas('employee.user', function ($q) {
            $q->where('role', 'HR');
        });
    }

    $payrolls = $query->get();

    $stats = [
        'total' => $payrolls->count(),
        'total_gross' => $payrolls->sum('gross_salary'),
        'total_deductions' => $payrolls->sum('total_deductions'),
        'total_net' => $payrolls->sum('net_salary'),
    ];

    // Generate PDF
    $pdf = Pdf::loadView('payroll.reports.pdf', compact('payrolls', 'stats'));
    
    return $pdf->download('payroll_report_' . date('Y_m_d_His') . '.pdf');
}
}
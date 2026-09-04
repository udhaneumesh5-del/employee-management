<?php

namespace App\Http\Controllers;

use App\Models\Reimbursement;
use App\Models\ExpenseType;
use App\Models\ReimbursementPolicy;
use App\Models\ReimbursementApproval;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ReimbursementController extends Controller
{
    /**
     * Reimbursement Dashboard
     */
    public function dashboard()
    {
        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();

        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'Employee record not found.');
        }

        $stats = [];

        if ($user->isEmployee()) {
            // Employee Stats
            $stats = [
                'total' => Reimbursement::where('requester_id', $employee->id)->count(),
                'pending' => Reimbursement::where('requester_id', $employee->id)
                    ->whereIn('status', ['pending_manager', 'pending_hr', 'pending_admin'])
                    ->count(),
                'approved' => Reimbursement::where('requester_id', $employee->id)
                    ->where('status', 'approved_final')
                    ->count(),
                'rejected' => Reimbursement::where('requester_id', $employee->id)
                    ->whereIn('status', ['manager_rejected', 'hr_rejected', 'admin_rejected'])
                    ->count(),
                'paid' => Reimbursement::where('requester_id', $employee->id)
                    ->where('status', 'paid')
                    ->sum('paid_amount'),
                'pending_amount' => Reimbursement::where('requester_id', $employee->id)
                    ->whereIn('status', ['pending_manager', 'pending_hr', 'pending_admin'])
                    ->sum('amount')
            ];
            return view('reimbursements.employee.dashboard', compact('stats'));
        }

        if ($user->isManager()) {
            // Manager Stats
            $teamIds = Employee::where('manager_id', $employee->id)->pluck('id');
            $stats = [
                'my_pending' => Reimbursement::where('requester_id', $employee->id)
                    ->whereIn('status', ['pending_hr', 'pending_admin'])
                    ->count(),
                'team_pending' => Reimbursement::whereIn('requester_id', $teamIds)
                    ->where('status', 'pending_manager')
                    ->count(),
                'approved' => Reimbursement::whereIn('requester_id', $teamIds)
                    ->where('status', 'approved_final')
                    ->count(),
                'rejected' => Reimbursement::whereIn('requester_id', $teamIds)
                    ->whereIn('status', ['manager_rejected', 'hr_rejected', 'admin_rejected'])
                    ->count(),
                'total_amount' => Reimbursement::whereIn('requester_id', $teamIds)
                    ->where('status', 'approved_final')
                    ->sum('amount')
            ];
            return view('reimbursements.manager.dashboard', compact('stats'));
        }

        if ($user->isHR()) {
            // HR Stats
            $stats = [
                'pending_employee' => Reimbursement::where('status', 'manager_approved')->count(),
                'pending_manager' => Reimbursement::where('status', 'pending_hr')
                    ->where('requester_role', 'Manager')
                    ->count(),
                'pending_hr_final' => Reimbursement::where('status', 'pending_hr')
                    ->where('requester_role', 'Employee')
                    ->count(),
                'pending_admin' => Reimbursement::where('status', 'pending_admin')
                    ->where('requester_role', 'HR')
                    ->count(),
                'rejected' => Reimbursement::whereIn('status', ['hr_rejected'])->count(),
                'total_pending_amount' => Reimbursement::whereIn('status', ['pending_hr', 'pending_admin'])
                    ->sum('amount')
            ];
            return view('reimbursements.hr.dashboard', compact('stats'));
        }

        if ($user->isAdmin()) {
            // Admin Stats
            $stats = [
                'pending_hr' => Reimbursement::where('status', 'pending_admin')
                    ->where('requester_role', 'HR')
                    ->count(),
                'final_approvals' => Reimbursement::where('status', 'approved_final')->count(),
                'rejected' => Reimbursement::where('status', 'admin_rejected')->count(),
                'paid' => Reimbursement::where('status', 'paid')->count(),
                'total_amount' => Reimbursement::where('status', 'approved_final')->sum('amount')
            ];
            return view('reimbursements.admin.dashboard', compact('stats'));
        }

        return redirect()->route('dashboard');
    }

    /**
     * Show Create Reimbursement Form
     */
    public function create()
    {
        $user = auth()->user();

        // Admin cannot create reimbursement
        if ($user->isAdmin()) {
            abort(403, 'Admin cannot create reimbursement requests.');
        }

        $expenseTypes = ExpenseType::where('is_active', true)->get();
        $employee = Employee::where('email', $user->email)->first();

        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'Employee record not found.');
        }

        // Get active policies for validation
        $policies = ReimbursementPolicy::active()->get();

        return view('reimbursements.create', compact('expenseTypes', 'employee', 'policies'));
    }

    /**
     * Store Reimbursement
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // Admin cannot create reimbursement
        if ($user->isAdmin()) {
            abort(403, 'Admin cannot create reimbursement requests.');
        }

        $request->validate([
            'expense_type_id' => 'required|exists:expense_types,id',
            'expense_date' => 'required|date|before_or_equal:today',
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string|min:5',
            'receipt' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'remarks' => 'nullable|string'
        ]);

        $employee = Employee::where('email', $user->email)->first();

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee record not found.');
        }

        // Get expense type
        $expenseType = ExpenseType::findOrFail($request->expense_type_id);

        // Get active policy
        $policy = ReimbursementPolicy::active()
            ->where('expense_type_id', $request->expense_type_id)
            ->first();

        // Validate against policy
        if ($policy) {
            if ($request->amount > $policy->maximum_amount) {
                if (!$policy->exception_allowed) {
                    return redirect()->back()
                        ->with('error', "Amount exceeds policy limit of ₹" . number_format($policy->maximum_amount, 2))
                        ->withInput();
                }
            }

            // Check receipt required
            if ($policy->receipt_required && !$request->hasFile('receipt')) {
                return redirect()->back()
                    ->with('error', 'Receipt is required as per company policy.')
                    ->withInput();
            }
        }

        // Handle receipt upload
        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('reimbursements', 'public');
        }

        // Determine approval flow based on role
        $approvalFlow = $this->getApprovalFlow($user);
        $status = $this->getInitialStatus($user);
        $managerId = null;

        // For Employee, get manager
        if ($user->isEmployee()) {
            $managerId = $employee->manager_id;
            if (!$managerId) {
                return redirect()->back()
                    ->with('error', 'No manager assigned to you. Please contact HR.')
                    ->withInput();
            }
        }

        // Create reimbursement
        $reimbursement = Reimbursement::create([
            'requester_id' => $employee->id,
            'requester_role' => $user->role,
            'expense_type_id' => $request->expense_type_id,
            'policy_id' => $policy?->id,
            'policy_name_at_submission' => $policy?->name,
            'policy_limit_at_submission' => $policy?->maximum_amount,
            'policy_limit_type_at_submission' => $policy?->limit_type,
            'receipt_required_at_submission' => $policy?->receipt_required ?? true,
            'expense_date' => $request->expense_date,
            'amount' => $request->amount,
            'description' => $request->description,
            'receipt' => $receiptPath,
            'status' => $status,
            'approval_flow' => $approvalFlow,
            'current_approval_level' => $this->getCurrentLevel($user),
            'manager_id' => $managerId,
            'remarks' => $request->remarks
        ]);

        // Log approval history
        $this->logApproval($reimbursement, $user, 'submitted', 'draft', $status);

        // Log activity
        DB::table('activity_logs')->insert([
            'employee_name' => $employee->first_name . ' ' . $employee->last_name,
            'action' => "Submitted reimbursement request ({$expenseType->name})",
            'performed_by' => $user->name,
            'performed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $message = 'Reimbursement submitted successfully! ';
        $statusMessages = [
            'pending_manager' => 'Waiting for Manager approval.',
            'pending_hr' => 'Waiting for HR final approval.',
            'pending_admin' => 'Waiting for Admin final approval.',
            'draft' => 'Draft saved successfully.'
        ];
        $message .= $statusMessages[$status] ?? '';

        return redirect()->route('reimbursements.my-requests')
            ->with('success', $message);
    }

    /**
     * My Reimbursements List
     */
    public function myRequests(Request $request)
    {
        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();

        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'Employee record not found.');
        }

        $query = Reimbursement::with(['expenseType', 'policy'])
            ->where('requester_id', $employee->id);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('expense_type_id')) {
            $query->where('expense_type_id', $request->expense_type_id);
        }
        if ($request->filled('from_date')) {
            $query->where('expense_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('expense_date', '<=', $request->to_date);
        }

        $reimbursements = $query->latest()->paginate(10);
        $expenseTypes = ExpenseType::where('is_active', true)->get();

        return view('reimbursements.my-requests', compact('reimbursements', 'expenseTypes'));
    }

    /**
     * View Reimbursement Details
     */
    public function show($id)
    {
        $reimbursement = Reimbursement::with([
            'requester', 'expenseType', 'policy',
            'manager', 'hr', 'admin', 'finalApprover', 'paidBy',
            'approvals.user'
        ])->findOrFail($id);

        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();

        // Authorization
        $canView = false;

        if ($user->isAdmin() || $user->isHR()) {
            $canView = true;
        } elseif ($user->isManager()) {
            if ($reimbursement->requester_id == $employee->id) {
                $canView = true;
            }
            $teamIds = Employee::where('manager_id', $employee->id)->pluck('id');
            if ($teamIds->contains($reimbursement->requester_id)) {
                $canView = true;
            }
        } elseif ($employee && $reimbursement->requester_id == $employee->id) {
            $canView = true;
        }

        if (!$canView) {
            abort(403, 'You do not have permission to view this reimbursement.');
        }

        return view('reimbursements.show', compact('reimbursement'));
    }

    /**
     * Edit Reimbursement
     */
    public function edit($id)
    {
        $reimbursement = Reimbursement::findOrFail($id);
        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();

        // Check if can edit
        if (!$employee || $reimbursement->requester_id != $employee->id) {
            abort(403, 'You cannot edit this reimbursement.');
        }

        if (!$reimbursement->canBeEdited()) {
            return redirect()->route('reimbursements.my-requests')
                ->with('error', 'This reimbursement cannot be edited.');
        }

        $expenseTypes = ExpenseType::where('is_active', true)->get();
        $policies = ReimbursementPolicy::active()->get();

        return view('reimbursements.edit', compact('reimbursement', 'expenseTypes', 'policies'));
    }

    /**
     * Update Reimbursement
     */
    public function update(Request $request, $id)
    {
        $reimbursement = Reimbursement::findOrFail($id);
        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();

        if (!$employee || $reimbursement->requester_id != $employee->id) {
            abort(403, 'You cannot update this reimbursement.');
        }

        if (!$reimbursement->canBeEdited()) {
            return redirect()->route('reimbursements.my-requests')
                ->with('error', 'This reimbursement cannot be edited.');
        }

        $request->validate([
            'expense_type_id' => 'required|exists:expense_types,id',
            'expense_date' => 'required|date|before_or_equal:today',
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string|min:5',
            'receipt' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'remarks' => 'nullable|string'
        ]);

        $data = $request->except(['receipt']);

        // Handle receipt upload
        if ($request->hasFile('receipt')) {
            // Delete old receipt
            if ($reimbursement->receipt) {
                Storage::disk('public')->delete($reimbursement->receipt);
            }
            $data['receipt'] = $request->file('receipt')->store('reimbursements', 'public');
        }

        $reimbursement->update($data);

        // Log activity
        DB::table('activity_logs')->insert([
            'employee_name' => $employee->first_name . ' ' . $employee->last_name,
            'action' => 'Updated reimbursement request',
            'performed_by' => $user->name,
            'performed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('reimbursements.my-requests')
            ->with('success', 'Reimbursement updated successfully!');
    }

    /**
     * Cancel Reimbursement
     */
    public function cancel($id)
    {
        $reimbursement = Reimbursement::findOrFail($id);
        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();

        if (!$employee || $reimbursement->requester_id != $employee->id) {
            abort(403, 'You cannot cancel this reimbursement.');
        }

        if (!$reimbursement->canBeCancelled()) {
            return redirect()->route('reimbursements.my-requests')
                ->with('error', 'This reimbursement cannot be cancelled.');
        }

        $oldStatus = $reimbursement->status;
        $reimbursement->status = 'cancelled';
        $reimbursement->save();

        // Log approval
        $this->logApproval($reimbursement, $user, 'cancelled', $oldStatus, 'cancelled');

        DB::table('activity_logs')->insert([
            'employee_name' => $employee->first_name . ' ' . $employee->last_name,
            'action' => 'Cancelled reimbursement request',
            'performed_by' => $user->name,
            'performed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('reimbursements.my-requests')
            ->with('success', 'Reimbursement cancelled successfully!');
    }

    /**
     * Manager - Pending Approvals
     */
    public function managerPending()
    {
        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();

        if (!$employee || !$user->isManager()) {
            abort(403, 'Unauthorized access.');
        }

        $teamIds = Employee::where('manager_id', $employee->id)->pluck('id');

        $reimbursements = Reimbursement::with(['requester', 'expenseType'])
            ->whereIn('requester_id', $teamIds)
            ->where('status', 'pending_manager')
            ->latest()
            ->paginate(10);

        return view('reimbursements.manager.pending', compact('reimbursements'));
    }

    /**
     * Manager - Approve
     */
    public function managerApprove(Request $request, $id)
    {
        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();

        if (!$employee || !$user->isManager()) {
            abort(403, 'Unauthorized access.');
        }

        $reimbursement = Reimbursement::findOrFail($id);

        // Check authorization
        if ($reimbursement->manager_id != $employee->id) {
            abort(403, 'You are not authorized to approve this request.');
        }

        if ($reimbursement->status != 'pending_manager') {
            return redirect()->back()->with('error', 'This request cannot be approved.');
        }

        $oldStatus = $reimbursement->status;

        if ($request->has('reject')) {
            $request->validate(['remarks' => 'required|string|min:5']);
            $reimbursement->status = 'manager_rejected';
            $reimbursement->manager_remarks = $request->remarks;
            $action = 'rejected';
            $message = 'Reimbursement rejected.';
        } else {
            $reimbursement->status = 'manager_approved';
            $reimbursement->manager_approved_at = now();
            $reimbursement->manager_remarks = $request->remarks;
            $action = 'approved';
            $message = 'Reimbursement approved and forwarded to HR.';
        }

        $reimbursement->save();

        // Log approval
        $this->logApproval($reimbursement, $user, $action, $oldStatus, $reimbursement->status);

        DB::table('activity_logs')->insert([
            'employee_name' => $reimbursement->requester->first_name . ' ' . $reimbursement->requester->last_name,
            'action' => "Manager {$action} reimbursement request",
            'performed_by' => $user->name,
            'performed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('reimbursements.manager.pending')
            ->with('success', $message);
    }

    /**
     * Manager - Reject
     */
    public function managerReject(Request $request, $id)
    {
        return $this->managerApprove($request, $id);
    }

    /**
     * HR - Pending Approvals
     */
    public function hrPending()
    {
        $user = auth()->user();

        if (!$user->isHR()) {
            abort(403, 'Unauthorized access.');
        }

        $reimbursements = Reimbursement::with(['requester', 'expenseType'])
            ->where(function($query) {
                $query->where('status', 'pending_hr')
                      ->orWhere('status', 'manager_approved');
            })
            ->latest()
            ->paginate(10);

        return view('reimbursements.hr.pending', compact('reimbursements'));
    }

    /**
     * HR - Approve (Final for Employee/Manager)
     */
    public function hrApprove(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user->isHR()) {
            abort(403, 'Unauthorized access.');
        }

        $reimbursement = Reimbursement::with(['requester'])->findOrFail($id);

        // Check authorization - HR cannot approve own request
        if ($reimbursement->requester_id == Employee::where('email', $user->email)->first()->id) {
            return redirect()->back()->with('error', 'You cannot approve your own reimbursement request.');
        }

        if (!in_array($reimbursement->status, ['pending_hr', 'manager_approved'])) {
            return redirect()->back()->with('error', 'This request cannot be approved.');
        }

        $oldStatus = $reimbursement->status;
        $employee = Employee::where('email', $user->email)->first();

        if ($request->has('reject')) {
            $request->validate(['remarks' => 'required|string|min:5']);
            $reimbursement->status = 'hr_rejected';
            $reimbursement->hr_remarks = $request->remarks;
            $reimbursement->hr_id = $employee->id;
            $action = 'rejected';
            $message = 'Reimbursement rejected.';
        } else {
            $reimbursement->status = 'approved_final';
            $reimbursement->hr_approved_at = now();
            $reimbursement->hr_id = $employee->id;
            $reimbursement->final_approver_id = $employee->id;
            $reimbursement->final_approver_role = 'HR';
            $reimbursement->final_approved_at = now();
            $action = 'approved';
            $message = 'Reimbursement approved! This is the final approval.';
        }

        $reimbursement->save();

        // Log approval
        $this->logApproval($reimbursement, $user, $action, $oldStatus, $reimbursement->status, true);

        DB::table('activity_logs')->insert([
            'employee_name' => $reimbursement->requester->first_name . ' ' . $reimbursement->requester->last_name,
            'action' => "HR {$action} reimbursement request",
            'performed_by' => $user->name,
            'performed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('reimbursements.hr.pending')
            ->with('success', $message);
    }

    /**
     * HR - Reject
     */
    public function hrReject(Request $request, $id)
    {
        return $this->hrApprove($request, $id);
    }

    /**
     * Admin - Pending Approvals (HR Requests)
     */
    public function adminPending()
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $reimbursements = Reimbursement::with(['requester', 'expenseType'])
            ->where('status', 'pending_admin')
            ->where('requester_role', 'HR')
            ->latest()
            ->paginate(10);

        return view('reimbursements.admin.pending', compact('reimbursements'));
    }

    /**
     * Admin - Approve (Final for HR)
     */
    public function adminApprove(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $reimbursement = Reimbursement::with(['requester'])->findOrFail($id);

        // Only HR requests
        if ($reimbursement->requester_role != 'HR') {
            return redirect()->back()->with('error', 'You can only approve HR reimbursement requests.');
        }

        if ($reimbursement->status != 'pending_admin') {
            return redirect()->back()->with('error', 'This request cannot be approved.');
        }

        $oldStatus = $reimbursement->status;
        $employee = Employee::where('email', $user->email)->first();

        if ($request->has('reject')) {
            $request->validate(['remarks' => 'required|string|min:5']);
            $reimbursement->status = 'admin_rejected';
            $reimbursement->admin_remarks = $request->remarks;
            $reimbursement->admin_id = $employee->id;
            $action = 'rejected';
            $message = 'Reimbursement rejected.';
        } else {
            $reimbursement->status = 'approved_final';
            $reimbursement->admin_approved_at = now();
            $reimbursement->admin_id = $employee->id;
            $reimbursement->final_approver_id = $employee->id;
            $reimbursement->final_approver_role = 'Admin';
            $reimbursement->final_approved_at = now();
            $action = 'approved';
            $message = 'Reimbursement approved! This is the final approval.';
        }

        $reimbursement->save();

        // Log approval
        $this->logApproval($reimbursement, $user, $action, $oldStatus, $reimbursement->status, true);

        DB::table('activity_logs')->insert([
            'employee_name' => $reimbursement->requester->first_name . ' ' . $reimbursement->requester->last_name,
            'action' => "Admin {$action} reimbursement request",
            'performed_by' => $user->name,
            'performed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('reimbursements.admin.pending')
            ->with('success', $message);
    }

    /**
     * Admin - Reject
     */
    public function adminReject(Request $request, $id)
    {
        return $this->adminApprove($request, $id);
    }

    /**
     * All Requests (Admin only)
     */
    public function allRequests(Request $request)
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $query = Reimbursement::with(['requester', 'expenseType', 'policy']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('expense_type_id')) {
            $query->where('expense_type_id', $request->expense_type_id);
        }
        if ($request->filled('from_date')) {
            $query->where('expense_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('expense_date', '<=', $request->to_date);
        }
        if ($request->filled('requester_role')) {
            $query->where('requester_role', $request->requester_role);
        }

        $reimbursements = $query->latest()->paginate(20);
        $expenseTypes = ExpenseType::where('is_active', true)->get();

        return view('reimbursements.admin.all-requests', compact('reimbursements', 'expenseTypes'));
    }

    /**
     * Display payment management page
     */
    public function payments()
    {
        $user = auth()->user();
        
        // Only Admin and HR can manage payments
        if (!$user->isAdmin() && !$user->isHR()) {
            abort(403, 'Unauthorized access.');
        }

        $reimbursements = Reimbursement::with(['requester', 'expenseType', 'paidBy'])
            ->whereIn('status', ['approved_final', 'paid'])
            ->latest()
            ->paginate(15);

        return view('reimbursements.payments.index', compact('reimbursements'));
    }

    /**
     * Process payment for a reimbursement
     */
    public function processPayment(Request $request, $id)
    {
        $user = auth()->user();
        
        if (!$user->isAdmin() && !$user->isHR()) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'paid_amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|max:255',
            'payment_reference' => 'nullable|string|max:255',
            'payment_remarks' => 'nullable|string'
        ]);

        $reimbursement = Reimbursement::findOrFail($id);
        $employee = Employee::where('email', $user->email)->first();

        // Check if already paid
        if ($reimbursement->status === 'paid') {
            return redirect()->back()
                ->with('error', 'This reimbursement is already paid.');
        }

        // Only approved final can be paid
        if ($reimbursement->status !== 'approved_final') {
            return redirect()->back()
                ->with('error', 'Only approved reimbursements can be marked as paid.');
        }

        $oldStatus = $reimbursement->status;
        
        $reimbursement->update([
            'status' => 'paid',
            'paid_amount' => $request->paid_amount,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'payment_reference' => $request->payment_reference,
            'payment_remarks' => $request->payment_remarks,
            'paid_by' => $user->id
        ]);

        // Log approval
        ReimbursementApproval::create([
            'reimbursement_id' => $reimbursement->id,
            'user_id' => auth()->id(),
            'role' => $user->role,
            'approval_level' => 'payment',
            'action' => 'paid',
            'previous_status' => $oldStatus,
            'new_status' => 'paid',
            'remarks' => $request->payment_remarks,
            'is_final_approval' => true
        ]);

        DB::table('activity_logs')->insert([
            'employee_name' => $reimbursement->requester->first_name . ' ' . $reimbursement->requester->last_name,
            'action' => "Processed payment for reimbursement #{$reimbursement->id}",
            'performed_by' => auth()->user()->name,
            'performed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('reimbursements.payments.index')
            ->with('success', 'Payment processed successfully!');
    }

    /**
     * Helper: Get Approval Flow
     */
    private function getApprovalFlow($user)
    {
        if ($user->isEmployee()) {
            return 'employee_to_manager_to_hr';
        }
        if ($user->isManager()) {
            return 'manager_to_hr';
        }
        if ($user->isHR()) {
            return 'hr_to_admin';
        }
        return null;
    }

    /**
     * Helper: Get Initial Status
     */
    private function getInitialStatus($user)
    {
        if ($user->isEmployee()) {
            return 'pending_manager';
        }
        if ($user->isManager()) {
            return 'pending_hr';
        }
        if ($user->isHR()) {
            return 'pending_admin';
        }
        return 'draft';
    }

    /**
     * Helper: Get Current Approval Level
     */
    private function getCurrentLevel($user)
    {
        if ($user->isEmployee()) {
            return 'manager';
        }
        if ($user->isManager()) {
            return 'hr';
        }
        if ($user->isHR()) {
            return 'admin';
        }
        return null;
    }

    /**
     * Helper: Log Approval History
     */
    private function logApproval($reimbursement, $user, $action, $previousStatus, $newStatus, $isFinal = false)
    {
        ReimbursementApproval::create([
            'reimbursement_id' => $reimbursement->id,
            'user_id' => $user->id,
            'role' => $user->role,
            'approval_level' => $reimbursement->current_approval_level ?? 'submitted',
            'action' => $action,
            'previous_status' => $previousStatus,
            'new_status' => $newStatus,
            'remarks' => request('remarks'),
            'is_final_approval' => $isFinal
        ]);
    }
}
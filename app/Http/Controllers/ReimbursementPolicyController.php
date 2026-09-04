<?php

namespace App\Http\Controllers;

use App\Models\ReimbursementPolicy;
use App\Models\ExpenseType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReimbursementPolicyController extends Controller
{
    /**
     * Display a listing of policies
     */
    public function index(Request $request)
    {
        $query = ReimbursementPolicy::with(['expenseType', 'createdBy']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhereHas('expenseType', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%");
                  });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('expense_type_id')) {
            $query->where('expense_type_id', $request->expense_type_id);
        }

        $policies = $query->latest()->paginate(10);
        $expenseTypes = ExpenseType::where('is_active', true)->get();

        return view('reimbursements.policies.index', compact('policies', 'expenseTypes'));
    }

    /**
     * Show form to create new policy
     */
    public function create()
    {
        $expenseTypes = ExpenseType::where('is_active', true)->get();
        $limitTypes = ['per_claim', 'per_day', 'per_month', 'per_year'];
        $statuses = ['Active', 'Inactive'];

        return view('reimbursements.policies.create', compact('expenseTypes', 'limitTypes', 'statuses'));
    }

    /**
     * Store a new policy
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'expense_type_id' => 'required|exists:expense_types,id',
            'maximum_amount' => 'required|numeric|min:0',
            'limit_type' => 'required|in:per_claim,per_day,per_month,per_year',
            'limit_period' => 'nullable|integer|min:1',
            'maximum_claims' => 'nullable|integer|min:1',
            'receipt_required' => 'boolean',
            'exception_allowed' => 'boolean',
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after:effective_from',
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive'
        ]);

        $policy = ReimbursementPolicy::create([
            'name' => $request->name,
            'expense_type_id' => $request->expense_type_id,
            'maximum_amount' => $request->maximum_amount,
            'limit_type' => $request->limit_type,
            'limit_period' => $request->limit_period,
            'maximum_claims' => $request->maximum_claims,
            'receipt_required' => $request->receipt_required ?? true,
            'exception_allowed' => $request->exception_allowed ?? false,
            'effective_from' => $request->effective_from,
            'effective_to' => $request->effective_to,
            'description' => $request->description,
            'status' => $request->status,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id()
        ]);

        DB::table('activity_logs')->insert([
            'employee_name' => auth()->user()->name,
            'action' => "Created reimbursement policy: {$policy->name}",
            'performed_by' => auth()->user()->name,
            'performed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('reimbursements.policies.index')
            ->with('success', 'Policy created successfully!');
    }

    /**
     * Show form to edit policy
     */
    public function edit($id)
    {
        $policy = ReimbursementPolicy::findOrFail($id);
        $expenseTypes = ExpenseType::where('is_active', true)->get();
        $limitTypes = ['per_claim', 'per_day', 'per_month', 'per_year'];
        $statuses = ['Active', 'Inactive'];

        return view('reimbursements.policies.edit', compact('policy', 'expenseTypes', 'limitTypes', 'statuses'));
    }

    /**
     * Update policy
     */
    public function update(Request $request, $id)
    {
        $policy = ReimbursementPolicy::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'expense_type_id' => 'required|exists:expense_types,id',
            'maximum_amount' => 'required|numeric|min:0',
            'limit_type' => 'required|in:per_claim,per_day,per_month,per_year',
            'limit_period' => 'nullable|integer|min:1',
            'maximum_claims' => 'nullable|integer|min:1',
            'receipt_required' => 'boolean',
            'exception_allowed' => 'boolean',
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after:effective_from',
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive'
        ]);

        $policy->update([
            'name' => $request->name,
            'expense_type_id' => $request->expense_type_id,
            'maximum_amount' => $request->maximum_amount,
            'limit_type' => $request->limit_type,
            'limit_period' => $request->limit_period,
            'maximum_claims' => $request->maximum_claims,
            'receipt_required' => $request->receipt_required ?? true,
            'exception_allowed' => $request->exception_allowed ?? false,
            'effective_from' => $request->effective_from,
            'effective_to' => $request->effective_to,
            'description' => $request->description,
            'status' => $request->status,
            'updated_by' => auth()->id()
        ]);

        DB::table('activity_logs')->insert([
            'employee_name' => auth()->user()->name,
            'action' => "Updated reimbursement policy: {$policy->name}",
            'performed_by' => auth()->user()->name,
            'performed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('reimbursements.policies.index')
            ->with('success', 'Policy updated successfully!');
    }

    /**
     * Delete policy
     */
    public function destroy($id)
    {
        $policy = ReimbursementPolicy::findOrFail($id);

        // Check if policy is used in any reimbursement
        $used = $policy->reimbursements()->exists();
        if ($used) {
            return redirect()->back()
                ->with('error', 'Cannot delete policy as it is already used in reimbursement records.');
        }

        $policyName = $policy->name;
        $policy->delete();

        DB::table('activity_logs')->insert([
            'employee_name' => auth()->user()->name,
            'action' => "Deleted reimbursement policy: {$policyName}",
            'performed_by' => auth()->user()->name,
            'performed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('reimbursements.policies.index')
            ->with('success', 'Policy deleted successfully!');
    }

    /**
     * Toggle policy status
     */
    public function toggleStatus($id)
    {
        $policy = ReimbursementPolicy::findOrFail($id);
        $policy->status = $policy->status === 'Active' ? 'Inactive' : 'Active';
        $policy->save();

        DB::table('activity_logs')->insert([
            'employee_name' => auth()->user()->name,
            'action' => "Toggled policy status: {$policy->name} to {$policy->status}",
            'performed_by' => auth()->user()->name,
            'performed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->back()
            ->with('success', 'Policy status updated successfully!');
    }
}
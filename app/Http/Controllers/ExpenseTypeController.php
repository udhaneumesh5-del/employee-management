<?php

namespace App\Http\Controllers;

use App\Models\ExpenseType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseTypeController extends Controller
{
    /**
     * Display a listing of expense types
     */
    public function index(Request $request)
    {
        $query = ExpenseType::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $expenseTypes = $query->latest()->paginate(10);

        return view('reimbursements.expense-types.index', compact('expenseTypes'));
    }

    /**
     * Show form to create new expense type
     */
    public function create()
    {
        return view('reimbursements.expense-types.create');
    }

    /**
     * Store a new expense type
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:expense_types',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $expenseType = ExpenseType::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'is_active' => $request->is_active ?? true
        ]);

        DB::table('activity_logs')->insert([
            'employee_name' => auth()->user()->name,
            'action' => "Created expense type: {$expenseType->name}",
            'performed_by' => auth()->user()->name,
            'performed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('reimbursements.expense-types.index')
            ->with('success', 'Expense type created successfully!');
    }

    /**
     * Show form to edit expense type
     */
    public function edit($id)
    {
        $expenseType = ExpenseType::findOrFail($id);
        return view('reimbursements.expense-types.edit', compact('expenseType'));
    }

    /**
     * Update expense type
     */
    public function update(Request $request, $id)
    {
        $expenseType = ExpenseType::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:expense_types,code,' . $id,
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $expenseType->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'is_active' => $request->is_active ?? true
        ]);

        DB::table('activity_logs')->insert([
            'employee_name' => auth()->user()->name,
            'action' => "Updated expense type: {$expenseType->name}",
            'performed_by' => auth()->user()->name,
            'performed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('reimbursements.expense-types.index')
            ->with('success', 'Expense type updated successfully!');
    }

    /**
     * Delete expense type
     */
    public function destroy($id)
    {
        $expenseType = ExpenseType::findOrFail($id);

        // Check if used in any reimbursement
        $used = $expenseType->reimbursements()->exists();
        if ($used) {
            return redirect()->back()
                ->with('error', 'Cannot delete expense type as it is already used in reimbursement records.');
        }

        $expenseTypeName = $expenseType->name;
        $expenseType->delete();

        DB::table('activity_logs')->insert([
            'employee_name' => auth()->user()->name,
            'action' => "Deleted expense type: {$expenseTypeName}",
            'performed_by' => auth()->user()->name,
            'performed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('reimbursements.expense-types.index')
            ->with('success', 'Expense type deleted successfully!');
    }

    /**
     * Toggle expense type status
     */
    public function toggleStatus($id)
    {
        $expenseType = ExpenseType::findOrFail($id);
        $expenseType->is_active = !$expenseType->is_active;
        $expenseType->save();

        DB::table('activity_logs')->insert([
            'employee_name' => auth()->user()->name,
            'action' => "Toggled expense type status: {$expenseType->name}",
            'performed_by' => auth()->user()->name,
            'performed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->back()
            ->with('success', 'Expense type status updated successfully!');
    }
}
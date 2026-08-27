<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of leave types.
     * USING LEFT JOIN: Show leave types with usage count
     */
    public function index(Request $request)
    {
        // USING LEFT JOIN: Leave types with their usage count
        $query = LeaveType::select(
                'leave_types.*',
                DB::raw('COUNT(leave_requests.id) as total_used')
            )
            ->leftJoin('leave_requests', 'leave_types.id', '=', 'leave_requests.leave_type_id')
            ->groupBy(
                'leave_types.id', 'leave_types.name', 'leave_types.code',
                'leave_types.annual_limit', 'leave_types.max_consecutive_days',
                'leave_types.carry_forward', 'leave_types.is_paid',
                'leave_types.requires_document', 'leave_types.is_active',
                'leave_types.description', 'leave_types.created_at',
                'leave_types.updated_at'
            );

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('leave_types.name', 'LIKE', "%{$search}%")
                  ->orWhere('leave_types.code', 'LIKE', "%{$search}%");
            });
        }

        $leaveTypes = $query->latest('leave_types.created_at')->paginate(10);
        return view('leave.leave-types.index', compact('leaveTypes'));
    }

    public function create()
    {
        return view('leave.leave-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:leave_types',
            'annual_limit' => 'required|integer|min:0',
            'max_consecutive_days' => 'nullable|integer|min:1',
            'carry_forward' => 'boolean',
            'is_paid' => 'boolean',
            'requires_document' => 'boolean',
            'description' => 'nullable|string'
        ]);

        DB::table('leave_types')->insert([
            'name' => $request->name,
            'code' => $request->code,
            'annual_limit' => $request->annual_limit,
            'max_consecutive_days' => $request->max_consecutive_days,
            'carry_forward' => $request->carry_forward ?? false,
            'is_paid' => $request->is_paid ?? true,
            'requires_document' => $request->requires_document ?? false,
            'is_active' => true,
            'description' => $request->description,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('leave-types.index')
            ->with('success', 'Leave type created successfully!');
    }

    public function edit($id)
    {
        // USING LEFT JOIN: Get leave type with usage count
        $leaveType = LeaveType::select(
                'leave_types.*',
                DB::raw('COUNT(leave_requests.id) as total_used')
            )
            ->leftJoin('leave_requests', 'leave_types.id', '=', 'leave_requests.leave_type_id')
            ->where('leave_types.id', $id)
            ->groupBy(
                'leave_types.id', 'leave_types.name', 'leave_types.code',
                'leave_types.annual_limit', 'leave_types.max_consecutive_days',
                'leave_types.carry_forward', 'leave_types.is_paid',
                'leave_types.requires_document', 'leave_types.is_active',
                'leave_types.description', 'leave_types.created_at',
                'leave_types.updated_at'
            )
            ->firstOrFail();

        return view('leave.leave-types.edit', compact('leaveType'));
    }

    public function update(Request $request, $id)
    {
        $leaveType = LeaveType::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:leave_types,code,' . $id,
            'annual_limit' => 'required|integer|min:0',
            'max_consecutive_days' => 'nullable|integer|min:1',
            'carry_forward' => 'boolean',
            'is_paid' => 'boolean',
            'requires_document' => 'boolean',
            'is_active' => 'boolean',
            'description' => 'nullable|string'
        ]);

        $leaveType->update($request->all());

        return redirect()->route('leave-types.index')
            ->with('success', 'Leave type updated successfully!');
    }

    public function destroy($id)
    {
        $leaveType = LeaveType::findOrFail($id);
        
        // USING INNER JOIN: Check if leave type is used in any leave request
        $used = DB::table('leave_requests')
            ->where('leave_type_id', $id)
            ->exists();
            
        if ($used) {
            return redirect()->back()
                ->with('error', 'Cannot delete leave type as it is already used in leave requests.');
        }

        $leaveType->delete();

        return redirect()->route('leave-types.index')
            ->with('success', 'Leave type deleted successfully!');
    }

    public function toggleStatus($id)
    {
        $leaveType = LeaveType::findOrFail($id);
        
        // USING INNER JOIN: Check if there are any pending requests before deactivating
        if ($leaveType->is_active) {
            $pendingRequests = DB::table('leave_requests')
                ->where('leave_type_id', $id)
                ->whereIn('status', ['pending_manager', 'pending_hr', 'pending_admin'])
                ->exists();
                
            if ($pendingRequests) {
                return redirect()->back()
                    ->with('error', 'Cannot deactivate leave type. There are pending leave requests.');
            }
        }
        
        $leaveType->is_active = !$leaveType->is_active;
        $leaveType->save();

        return redirect()->back()
            ->with('success', 'Leave type status updated successfully!');
    }
}
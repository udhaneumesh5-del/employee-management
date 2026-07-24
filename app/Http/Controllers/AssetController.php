<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Employee;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    // Show asset list
    public function index(Request $request)
    {
        $query = Asset::query();

        // Search by Employee Code or Name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('employee_code', 'LIKE', "%{$search}%")
                  ->orWhere('employee_name', 'LIKE', "%{$search}%");
            });
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by Asset Type
        if ($request->filled('asset_type')) {
            $query->where('asset_type', $request->asset_type);
        }

        $assets = $query->latest()->paginate(10);
        
        //  Get unique asset types from database
        $assetTypes = Asset::getAssetTypes();

        return view('assets.index', compact('assets', 'assetTypes'));
    }

    // Show create form
    public function create()
    {
        //  Get all unique asset types from database
        $assetTypes = Asset::getAssetTypes();
        
        // If no assets exist, use default types
        if (empty($assetTypes)) {
            $assetTypes = ['Laptop', 'Mouse', 'Keyboard', 'Charger', 'Bag'];
        }
        
        $employees = Employee::where('status', 'Active')
                            ->orderBy('employee_code', 'asc')
                            ->get(['id', 'employee_code', 'first_name', 'last_name', 'department_id']);
        
        return view('assets.create', compact('assetTypes', 'employees'));
    }

    // Store asset
    public function store(Request $request)
    {
        $request->validate([
            'employee_code' => 'required|string|max:50',
            'employee_name' => 'required|string|max:100',
            'department' => 'required|string|max:100',
            'asset_types' => 'required|array|min:1',
            'asset_types.*' => 'string|max:50',
            'issue_date' => 'required|date',
            'return_date' => 'nullable|date|after_or_equal:issue_date',
            'status' => 'required|in:Issued,Returned',
            'condition' => 'required|in:Good,Damaged',
            'remarks' => 'nullable|string'
        ]);

        // Create separate record for each asset
        foreach ($request->asset_types as $assetType) {
            Asset::create([
                'employee_code' => $request->employee_code,
                'employee_name' => $request->employee_name,
                'department' => $request->department,
                'asset_type' => $assetType,
                'issue_date' => $request->issue_date,
                'return_date' => $request->return_date,
                'status' => $request->status,
                'condition' => $request->condition,
                'remarks' => $request->remarks
            ]);
        }

        return redirect()->route('assets.index')
            ->with('success', count($request->asset_types) . ' assets assigned successfully!');
    }

    // Show edit form
    public function edit(Asset $asset)
    {
        //  Get all unique asset types from database
        $assetTypes = Asset::getAssetTypes();
        
        // If no assets exist, use default types
        if (empty($assetTypes)) {
            $assetTypes = ['Laptop', 'Mouse', 'Keyboard', 'Charger', 'Bag'];
        }
        
        $employees = Employee::where('status', 'Active')
                            ->orderBy('employee_code', 'asc')
                            ->get(['id', 'employee_code', 'first_name', 'last_name', 'department_id']);
        
        return view('assets.edit', compact('asset', 'assetTypes', 'employees'));
    }

    // Update asset
    public function update(Request $request, Asset $asset)
    {
        $request->validate([
            'employee_code' => 'required|string|max:50',
            'employee_name' => 'required|string|max:100',
            'department' => 'required|string|max:100',
            'asset_type' => 'required|string|max:50',
            'issue_date' => 'required|date',
            'return_date' => 'nullable|date|after_or_equal:issue_date',
            'status' => 'required|in:Issued,Returned',
            'condition' => 'required|in:Good,Damaged',
            'remarks' => 'nullable|string'
        ]);

        $asset->update($request->all());

        return redirect()->route('assets.index')
            ->with('success', 'Asset updated successfully!');
    }

    // Delete asset
    public function destroy(Asset $asset)
    {
        $asset->delete();

        return redirect()->route('assets.index')
            ->with('success', 'Asset deleted successfully!');
    }

    // Get Employee Details (AJAX)
    public function getEmployeeDetails(Request $request)
    {
        $employee = Employee::where('employee_code', $request->employee_code)
                           ->with('department')
                           ->first();
        
        if ($employee) {
            return response()->json([
                'employee_name' => $employee->first_name . ' ' . $employee->last_name,
                'department' => $employee->department ? $employee->department->department_name : 'N/A'
            ]);
        }
        
        return response()->json(null);
    }

    // Reports
    public function reports()
    {
        $allAssets = Asset::count();
        $issuedAssets = Asset::where('status', 'Issued')->count();
        $returnedAssets = Asset::where('status', 'Returned')->count();
        $pendingReturns = Asset::where('status', 'Issued')
                              ->where('return_date', '<', now())
                              ->count();

        return view('assets.reports', compact(
            'allAssets',
            'issuedAssets',
            'returnedAssets',
            'pendingReturns'
        ));
    }

    // All Asset Report
    public function allAssetsReport()
    {
        $assets = Asset::latest()->get();
        return view('assets.report-all', compact('assets'));
    }

    // Issued Asset Report
    public function issuedAssetsReport()
    {
        $assets = Asset::where('status', 'Issued')->latest()->get();
        return view('assets.report-issued', compact('assets'));
    }

    // Returned Asset Report
    public function returnedAssetsReport()
    {
        $assets = Asset::where('status', 'Returned')->latest()->get();
        return view('assets.report-returned', compact('assets'));
    }

    // Pending Return Asset Report
    public function pendingReturnsReport()
    {
        $assets = Asset::where('status', 'Issued')
                      ->where('return_date', '<', now())
                      ->latest()
                      ->get();
        return view('assets.report-pending', compact('assets'));
    }

    // Employee-wise Asset History
    public function employeeHistory(Request $request)
    {
        $query = Asset::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('employee_code', 'LIKE', "%{$search}%")
                  ->orWhere('employee_name', 'LIKE', "%{$search}%");
            });
        }

        $assets = $query->latest()->paginate(10);
        return view('assets.history', compact('assets'));
    }
}
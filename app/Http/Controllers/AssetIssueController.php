<?php

namespace App\Http\Controllers;

use App\Models\AssetMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssetIssueController extends Controller
{
    // Index with JOIN - No duplicate data
    public function index(Request $request)
    {
        $query = DB::table('asset_issues')
            ->join('assets_master', 'asset_issues.asset_id', '=', 'assets_master.id')
            ->select(
                'asset_issues.*',
                'assets_master.asset_code',
                'assets_master.asset_type',
                'assets_master.company_name',
                'assets_master.model',
                'assets_master.condition'
            );

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('asset_issues.employee_code', 'LIKE', "%{$search}%")
                  ->orWhere('asset_issues.employee_name', 'LIKE', "%{$search}%");
            });
        }

        $issues = $query->orderBy('asset_issues.id', 'desc')->paginate(10);
        return view('asset-issue.index', compact('issues'));
    }

    public function create()
    {
        $assets = DB::table('assets_master')
            ->where('status', 'Available')
            ->orderBy('asset_code', 'asc')
            ->get();

        $employees = DB::table('employees')
            ->where('status', 'Active')
            ->get();

        return view('asset-issue.create', compact('assets', 'employees'));
    }

    // Store - Only asset_id stored
    public function store(Request $request)
    {
        $request->validate([
            'employee_code' => 'required|string|max:50',
            'employee_name' => 'required|string|max:100',
            'department' => 'required|string|max:100',
            'asset_id' => 'required|exists:assets_master,id',
            'issue_date' => 'required|date',
            'remarks' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request) {
            // Insert only essential fields
            DB::table('asset_issues')->insert([
                'employee_code' => $request->employee_code,
                'employee_name' => $request->employee_name,
                'department' => $request->department,
                'asset_id' => $request->asset_id,
                'issue_date' => $request->issue_date,
                'remarks' => $request->remarks,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Update asset status
            DB::table('assets_master')
                ->where('id', $request->asset_id)
                ->update(['status' => 'Issued', 'updated_at' => now()]);
        });

        return redirect()->route('asset-issue.index')
            ->with('success', 'Asset issued successfully!');
    }

    // Get Asset Details (For AJAX)
    public function getAssetDetails($id)
    {
        $asset = DB::table('assets_master')->where('id', $id)->first();
        return response()->json($asset);
    }

    // Get Employee Details (For AJAX)
    public function getEmployeeDetails(Request $request)
    {
        $employee = DB::table('employees')
            ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
            ->select(
                'employees.first_name',
                'employees.last_name',
                DB::raw('COALESCE(departments.department_name, "N/A") as department')
            )
            ->where('employees.employee_code', $request->employee_code)
            ->first();

        if ($employee) {
            return response()->json([
                'employee_name' => $employee->first_name . ' ' . $employee->last_name,
                'department' => $employee->department
            ]);
        }
        return response()->json(null);
    }

    // Report with JOIN
    public function issuedReport(Request $request)
    {
        $query = DB::table('asset_issues')
            ->join('assets_master', 'asset_issues.asset_id', '=', 'assets_master.id')
            ->select(
                'asset_issues.*',
                'assets_master.asset_code',
                'assets_master.asset_type',
                'assets_master.company_name',
                'assets_master.model',
                'assets_master.condition'
            );

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('asset_issues.employee_code', 'LIKE', "%{$search}%")
                  ->orWhere('asset_issues.employee_name', 'LIKE', "%{$search}%");
            });
        }

        $issues = $query->orderBy('asset_issues.id', 'desc')->paginate(10);
        return view('asset-issue.report', compact('issues'));
    }

    // CSV Export with JOIN
    public function exportCSV()
    {
        $issues = DB::table('asset_issues')
            ->join('assets_master', 'asset_issues.asset_id', '=', 'assets_master.id')
            ->select(
                'asset_issues.employee_code',
                'asset_issues.employee_name',
                'asset_issues.department',
                'assets_master.asset_code',
                'assets_master.asset_type',
                'assets_master.company_name',
                'assets_master.model',
                'assets_master.condition',
                'asset_issues.issue_date'
            )
            ->orderBy('asset_issues.id', 'desc')
            ->get();

        $filename = 'issued_assets_' . date('Y_m_d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($issues) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Employee Code', 'Employee Name', 'Department',
                'Asset Code', 'Asset Type', 'Company Name', 'Model', 'Condition', 'Issue Date'
            ]);

            foreach ($issues as $issue) {
                fputcsv($file, [
                    $issue->employee_code,
                    $issue->employee_name,
                    $issue->department,
                    $issue->asset_code,
                    $issue->asset_type,
                    $issue->company_name,
                    $issue->model,
                    $issue->condition,
                    date('d-m-Y', strtotime($issue->issue_date))
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
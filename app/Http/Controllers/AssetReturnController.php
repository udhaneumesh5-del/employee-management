<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AssetReturn;

class AssetReturnController extends Controller
{
    /**
     * Display a listing of returned assets.
     */
    public function index(Request $request)
    {
        $query = DB::table('asset_returns')
            ->join('assets_master', 'asset_returns.asset_id', '=', 'assets_master.id')
            ->select(
                'asset_returns.*',
                'assets_master.asset_code',
                'assets_master.asset_type',
                'assets_master.company_name',
                'assets_master.model',
                'assets_master.condition'
            );

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('asset_returns.employee_code', 'LIKE', "%{$search}%")
                  ->orWhere('asset_returns.employee_name', 'LIKE', "%{$search}%")
                  ->orWhere('assets_master.asset_code', 'LIKE', "%{$search}%")
                  ->orWhere('assets_master.asset_type', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('return_reason')) {
            $query->where('asset_returns.return_reason', $request->return_reason);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('asset_returns.return_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('asset_returns.return_date', '<=', $request->to_date);
        }

        $returns = $query->orderBy('asset_returns.id', 'desc')->paginate(10);
        
        // Get return reasons for filter
        $returnReasons = AssetReturn::getReturnReasons();
        
        return view('asset-return.index', compact('returns', 'returnReasons'));
    }

    /**
     * Show the form for creating a new return.
     */
    public function create()
    {
        // Get issued assets with JOIN
        $issuedAssets = DB::table('asset_issues')
            ->join('assets_master', 'asset_issues.asset_id', '=', 'assets_master.id')
            ->select(
                'asset_issues.*',
                'assets_master.asset_code',
                'assets_master.asset_type',
                'assets_master.company_name',
                'assets_master.model',
                'assets_master.condition'
            )
            ->where('assets_master.status', 'Issued')
            ->orderBy('asset_issues.id', 'desc')
            ->get();

        $returnReasons = AssetReturn::getReturnReasons();
        $conditions = AssetReturn::getConditions();

        return view('asset-return.create', compact('issuedAssets', 'returnReasons', 'conditions'));
    }

    /**
     * Store a newly created return in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'issue_id' => 'required|exists:asset_issues,id',
            'return_date' => 'required|date',
            'return_reason' => 'required|in:Repair Required,Exchange,Employee Resigned,Hardware Problem,Other',
            'remarks' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request) {
            // Get issue details
            $issue = DB::table('asset_issues')->where('id', $request->issue_id)->first();
            
            if (!$issue) {
                throw new \Exception('Issue record not found.');
            }

            // Insert into returns
            DB::table('asset_returns')->insert([
                'employee_code' => $issue->employee_code,
                'employee_name' => $issue->employee_name,
                'department' => $issue->department,
                'asset_id' => $issue->asset_id,
                'asset_code' => $issue->asset_code,
                'asset_type' => $issue->asset_type,
                'company_name' => $issue->company_name,
                'model' => $issue->model,
                'condition' => $issue->condition,
                'issue_date' => $issue->issue_date,
                'return_date' => $request->return_date,
                'return_reason' => $request->return_reason,
                'remarks' => $request->remarks,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Update asset status to Available
            DB::table('assets_master')
                ->where('id', $issue->asset_id)
                ->update([
                    'status' => 'Available',
                    'updated_at' => now()
                ]);

            // Delete from asset_issues
            DB::table('asset_issues')->where('id', $request->issue_id)->delete();
        });

        return redirect()->route('asset-return.index')
            ->with('success', 'Asset returned successfully!');
    }

    /**
     * Get asset issue details via AJAX.
     */
    public function getAssetIssueDetails($id)
    {
        $issue = DB::table('asset_issues')
            ->join('assets_master', 'asset_issues.asset_id', '=', 'assets_master.id')
            ->select(
                'asset_issues.*',
                'assets_master.asset_code',
                'assets_master.asset_type',
                'assets_master.company_name',
                'assets_master.model',
                'assets_master.condition'
            )
            ->where('asset_issues.id', $id)
            ->first();

        if ($issue) {
            // Format date for display
            $issue->issue_date = date('d-m-Y', strtotime($issue->issue_date));
        }

        return response()->json($issue);
    }

    /**
     * Display the returned asset report.
     */
    public function returnedReport(Request $request)
    {
        $query = DB::table('asset_returns')
            ->join('assets_master', 'asset_returns.asset_id', '=', 'assets_master.id')
            ->select(
                'asset_returns.*',
                'assets_master.asset_code',
                'assets_master.asset_type',
                'assets_master.company_name',
                'assets_master.model',
                'assets_master.condition'
            );

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('asset_returns.employee_code', 'LIKE', "%{$search}%")
                  ->orWhere('asset_returns.employee_name', 'LIKE', "%{$search}%")
                  ->orWhere('assets_master.asset_code', 'LIKE', "%{$search}%")
                  ->orWhere('assets_master.asset_type', 'LIKE', "%{$search}%");
            });
        }

        // Return Reason filter
        if ($request->filled('return_reason')) {
            $query->where('asset_returns.return_reason', $request->return_reason);
        }

        // Date range filters
        if ($request->filled('from_date')) {
            $query->whereDate('asset_returns.return_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('asset_returns.return_date', '<=', $request->to_date);
        }

        $returns = $query->orderBy('asset_returns.id', 'desc')->paginate(10);
        
        // Get return reasons for filter dropdown
        $returnReasons = AssetReturn::getReturnReasons();

        return view('asset-return.report', compact('returns', 'returnReasons'));
    }

    /**
     * Export returned assets to CSV.
     */
    public function exportCSV(Request $request)
    {
        $query = DB::table('asset_returns')
            ->join('assets_master', 'asset_returns.asset_id', '=', 'assets_master.id')
            ->select(
                'asset_returns.employee_code',
                'asset_returns.employee_name',
                'asset_returns.department',
                'assets_master.asset_code',
                'assets_master.asset_type',
                'assets_master.company_name',
                'assets_master.model',
                'assets_master.condition',
                'asset_returns.issue_date',
                'asset_returns.return_date',
                'asset_returns.return_reason'
            );

        // Apply filters if provided
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('asset_returns.employee_code', 'LIKE', "%{$search}%")
                  ->orWhere('asset_returns.employee_name', 'LIKE', "%{$search}%")
                  ->orWhere('assets_master.asset_code', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('return_reason')) {
            $query->where('asset_returns.return_reason', $request->return_reason);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('asset_returns.return_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('asset_returns.return_date', '<=', $request->to_date);
        }

        $returns = $query->orderBy('asset_returns.id', 'desc')->get();

        $filename = 'returned_assets_' . date('Y_m_d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($returns) {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM for Excel compatibility
            fwrite($file, "\xEF\xBB\xBF");

            // Headers
            fputcsv($file, [
                'Employee Code',
                'Employee Name',
                'Department',
                'Asset Code',
                'Asset Type',
                'Company Name',
                'Model',
                'Condition',
                'Issue Date',
                'Return Date',
                'Return Reason'
            ]);

            // Data rows
            foreach ($returns as $return) {
                fputcsv($file, [
                    $return->employee_code,
                    $return->employee_name,
                    $return->department,
                    $return->asset_code,
                    $return->asset_type,
                    $return->company_name,
                    $return->model,
                    $return->condition,
                    date('d-m-Y', strtotime($return->issue_date)),
                    date('d-m-Y', strtotime($return->return_date)),
                    $return->return_reason
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Destroy the specified return record.
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $return = DB::table('asset_returns')->where('id', $id)->first();
            
            if ($return) {
                // Update asset status back to Issued
                DB::table('assets_master')
                    ->where('id', $return->asset_id)
                    ->update([
                        'status' => 'Issued',
                        'updated_at' => now()
                    ]);

                // Delete the return record
                DB::table('asset_returns')->where('id', $id)->delete();
            }
        });

        return redirect()->route('asset-return.index')
            ->with('success', 'Asset return record deleted successfully!');
    }
}
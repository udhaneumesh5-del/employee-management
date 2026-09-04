<?php

namespace App\Http\Controllers;

use App\Models\Reimbursement;
use App\Models\ExpenseType;
use App\Models\Employee;
use Illuminate\Http\Request;

class ReimbursementReportController extends Controller
{
    /**
     * Show reports page
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Only Admin and HR can view reports
        if (!$user->isAdmin() && !$user->isHR()) {
            abort(403, 'Unauthorized access.');
        }

        $query = Reimbursement::with(['requester', 'expenseType', 'manager', 'hr', 'admin', 'finalApprover']);

        // Filters
        if ($request->filled('requester_name')) {
            $query->whereHas('requester', function($q) use ($request) {
                $q->where('first_name', 'LIKE', "%{$request->requester_name}%")
                  ->orWhere('last_name', 'LIKE', "%{$request->requester_name}%");
            });
        }

        if ($request->filled('requester_role')) {
            $query->where('requester_role', $request->requester_role);
        }

        if ($request->filled('expense_type_id')) {
            $query->where('expense_type_id', $request->expense_type_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->where('expense_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->where('expense_date', '<=', $request->to_date);
        }

        if ($request->filled('amount_min')) {
            $query->where('amount', '>=', $request->amount_min);
        }

        if ($request->filled('amount_max')) {
            $query->where('amount', '<=', $request->amount_max);
        }

        $reimbursements = $query->latest()->paginate(20);
        $expenseTypes = ExpenseType::where('is_active', true)->get();

        // Summary stats
        $stats = [
            'total' => $query->count(),
            'total_amount' => $query->sum('amount'),
            'approved' => (clone $query)->where('status', 'approved_final')->count(),
            'approved_amount' => (clone $query)->where('status', 'approved_final')->sum('amount'),
            'pending' => (clone $query)->whereIn('status', ['pending_manager', 'pending_hr', 'pending_admin'])->count(),
            'pending_amount' => (clone $query)->whereIn('status', ['pending_manager', 'pending_hr', 'pending_admin'])->sum('amount'),
            'rejected' => (clone $query)->whereIn('status', ['manager_rejected', 'hr_rejected', 'admin_rejected'])->count(),
            'paid' => (clone $query)->where('status', 'paid')->count(),
            'paid_amount' => (clone $query)->where('status', 'paid')->sum('paid_amount')
        ];

        return view('reimbursements.reports.index', compact('reimbursements', 'expenseTypes', 'stats'));
    }

    /**
     * Export reports to CSV
     */
    public function exportCSV(Request $request)
    {
        $user = auth()->user();

        if (!$user->isAdmin() && !$user->isHR()) {
            abort(403, 'Unauthorized access.');
        }

        $query = Reimbursement::with(['requester', 'expenseType', 'manager', 'hr', 'admin', 'finalApprover']);

        // Apply filters
        if ($request->filled('requester_name')) {
            $query->whereHas('requester', function($q) use ($request) {
                $q->where('first_name', 'LIKE', "%{$request->requester_name}%")
                  ->orWhere('last_name', 'LIKE', "%{$request->requester_name}%");
            });
        }

        if ($request->filled('requester_role')) {
            $query->where('requester_role', $request->requester_role);
        }

        if ($request->filled('expense_type_id')) {
            $query->where('expense_type_id', $request->expense_type_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->where('expense_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->where('expense_date', '<=', $request->to_date);
        }

        $reimbursements = $query->get();

        $filename = 'reimbursement_report_' . date('Y_m_d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($reimbursements) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                'ID', 'Requester', 'Requester Role', 'Expense Type', 'Expense Date',
                'Amount', 'Status', 'Approval Flow', 'Manager', 'Manager Status',
                'HR', 'HR Status', 'Admin', 'Admin Status', 'Final Approver',
                'Payment Status', 'Paid Amount', 'Payment Date'
            ]);

            foreach ($reimbursements as $r) {
                fputcsv($file, [
                    $r->id,
                    $r->requester->first_name . ' ' . $r->requester->last_name ?? 'N/A',
                    $r->requester_role,
                    $r->expenseType->name ?? 'N/A',
                    $r->expense_date->format('d-m-Y'),
                    $r->amount,
                    $r->status_text,
                    $r->approval_flow_text,
                    $r->manager->first_name . ' ' . $r->manager->last_name ?? 'N/A',
                    $r->manager_approved_at ? 'Approved' : ($r->manager_remarks ? 'Rejected' : 'Pending'),
                    $r->hr->first_name . ' ' . $r->hr->last_name ?? 'N/A',
                    $r->hr_approved_at ? 'Approved' : ($r->hr_remarks ? 'Rejected' : 'Pending'),
                    $r->admin->first_name . ' ' . $r->admin->last_name ?? 'N/A',
                    $r->admin_approved_at ? 'Approved' : ($r->admin_remarks ? 'Rejected' : 'Pending'),
                    $r->finalApprover->first_name . ' ' . $r->finalApprover->last_name ?? 'N/A',
                    $r->status === 'paid' ? 'Paid' : 'Not Paid',
                    $r->paid_amount ?? 0,
                    $r->payment_date ?? 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Reimbursement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'requester_id',
        'requester_role',
        'expense_type_id',
        'policy_id',
        'policy_name_at_submission',
        'policy_limit_at_submission',
        'policy_limit_type_at_submission',
        'receipt_required_at_submission',
        'expense_date',
        'amount',
        'description',
        'receipt',
        'status',
        'approval_flow',
        'current_approval_level',
        'manager_id',
        'manager_approved_at',
        'manager_remarks',
        'hr_id',
        'hr_approved_at',
        'hr_remarks',
        'admin_id',
        'admin_approved_at',
        'admin_remarks',
        'final_approver_id',
        'final_approver_role',
        'final_approved_at',
        'paid_amount',
        'payment_date',
        'payment_method',
        'payment_reference',
        'paid_by',
        'payment_remarks',
        'is_exception',
        'exception_amount',
        'exception_reason',
        'exception_approved_by',
        'exception_approved_at',
        'remarks'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'exception_amount' => 'decimal:2',
        'expense_date' => 'date',
        'manager_approved_at' => 'datetime',
        'hr_approved_at' => 'datetime',
        'admin_approved_at' => 'datetime',
        'final_approved_at' => 'datetime',
        'payment_date' => 'date',
        'exception_approved_at' => 'datetime',
        'is_exception' => 'boolean',
        'receipt_required_at_submission' => 'boolean'
    ];

    // ==========================================
    // Relationships
    // ==========================================

    public function requester()
    {
        return $this->belongsTo(Employee::class, 'requester_id');
    }

    public function expenseType()
    {
        return $this->belongsTo(ExpenseType::class);
    }

    public function policy()
    {
        return $this->belongsTo(ReimbursementPolicy::class);
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function hr()
    {
        return $this->belongsTo(Employee::class, 'hr_id');
    }

    public function admin()
    {
        return $this->belongsTo(Employee::class, 'admin_id');
    }

    public function finalApprover()
    {
        return $this->belongsTo(Employee::class, 'final_approver_id');
    }

    public function paidBy()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    public function exceptionApprovedBy()
    {
        return $this->belongsTo(Employee::class, 'exception_approved_by');
    }

    public function approvals()
    {
        return $this->hasMany(ReimbursementApproval::class)->orderBy('created_at', 'asc');
    }

    // Status Check Methods
    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function isPending()
    {
        return in_array($this->status, ['pending_manager', 'pending_hr', 'pending_admin']);
    }

    public function isPendingManager()
    {
        return $this->status === 'pending_manager';
    }

    public function isPendingHR()
    {
        return $this->status === 'pending_hr';
    }

    public function isPendingAdmin()
    {
        return $this->status === 'pending_admin';
    }

    public function isApproved()
    {
        return $this->status === 'approved_final';
    }

    public function isRejected()
    {
        return in_array($this->status, ['manager_rejected', 'hr_rejected', 'admin_rejected']);
    }

    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function canBeEdited()
    {
        return in_array($this->status, ['draft', 'pending_manager', 'pending_hr', 'pending_admin']);
    }

    public function canBeDeleted()
    {
        return $this->status === 'draft';
    }

    public function canBeCancelled()
    {
        return in_array($this->status, ['draft', 'pending_manager', 'pending_hr', 'pending_admin']);
    }

    // Getter Methods

    public function getStatusTextAttribute()
    {
        $statusLabels = [
            'draft' => 'Draft',
            'pending_manager' => 'Pending Manager',
            'pending_hr' => 'Pending HR',
            'pending_admin' => 'Pending Admin',
            'manager_approved' => 'Manager Approved',
            'manager_rejected' => 'Manager Rejected',
            'hr_approved' => 'HR Approved',
            'hr_rejected' => 'HR Rejected',
            'admin_approved' => 'Admin Approved',
            'admin_rejected' => 'Admin Rejected',
            'approved_final' => 'Approved - Final',
            'paid' => 'Paid',
            'cancelled' => 'Cancelled'
        ];
        return $statusLabels[$this->status] ?? $this->status;
    }

    public function getStatusBadgeClassAttribute()
    {
        $statusClasses = [
            'draft' => 'bg-secondary',
            'pending_manager' => 'bg-warning text-dark',
            'pending_hr' => 'bg-warning text-dark',
            'pending_admin' => 'bg-warning text-dark',
            'manager_approved' => 'bg-info text-white',
            'manager_rejected' => 'bg-danger',
            'hr_approved' => 'bg-info text-white',
            'hr_rejected' => 'bg-danger',
            'admin_approved' => 'bg-info text-white',
            'admin_rejected' => 'bg-danger',
            'approved_final' => 'bg-success',
            'paid' => 'bg-primary',
            'cancelled' => 'bg-secondary'
        ];
        return $statusClasses[$this->status] ?? 'bg-secondary';
    }

    public function getApprovalFlowTextAttribute()
    {
        $flows = [
            'employee_to_manager_to_hr' => 'Employee → Manager → HR Final',
            'manager_to_hr' => 'Manager → HR Final',
            'hr_to_admin' => 'HR → Admin Final'
        ];
        return $flows[$this->approval_flow] ?? $this->approval_flow;
    }

    public function getReceiptUrlAttribute()
    {
        if ($this->receipt && Storage::disk('public')->exists($this->receipt)) {
            return asset('storage/' . $this->receipt);
        }
        return null;
    }

    // Scopes

    public function scopePending($query)
    {
        return $query->whereIn('status', ['pending_manager', 'pending_hr', 'pending_admin']);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved_final');
    }

    public function scopeRejected($query)
    {
        return $query->whereIn('status', ['manager_rejected', 'hr_rejected', 'admin_rejected']);
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeForRequester($query, $employeeId)
    {
        return $query->where('requester_id', $employeeId);
    }

    public function scopeForManager($query, $managerId)
    {
        return $query->where('manager_id', $managerId);
    }

    public function scopeForHR($query)
    {
        return $query->whereIn('status', ['pending_hr', 'manager_approved']);
    }

    public function scopeForAdmin($query)
    {
        return $query->where('status', 'pending_admin');
    }

    public function scopeByExpenseType($query, $expenseTypeId)
    {
        return $query->where('expense_type_id', $expenseTypeId);
    }

    public function scopeByDateRange($query, $from, $to)
    {
        return $query->whereBetween('expense_date', [$from, $to]);
    }

    public function scopeByAmountRange($query, $min, $max)
    {
        return $query->whereBetween('amount', [$min, $max]);
    }
}
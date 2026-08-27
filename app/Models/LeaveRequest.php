<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'from_date',
        'to_date',
        'total_days',
        'reason',
        'document',
        'status',
        'manager_id',
        'manager_comment',
        'hr_comment',
        'admin_comment',
        'manager_approved_at',
        'hr_approved_at'
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
        'manager_approved_at' => 'datetime',
        'hr_approved_at' => 'datetime',
    ];

    // Relationships
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function approvals()
    {
        return $this->hasMany(LeaveApproval::class, 'leave_request_id');
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }
    // Status Check Methods
    public function canBeCancelled()
    {
        return in_array($this->status, ['pending_manager', 'pending_hr', 'pending_admin']);
    }

    public function isPending()
    {
        return in_array($this->status, ['pending_manager', 'pending_hr', 'pending_admin']);
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return in_array($this->status, ['manager_rejected', 'hr_rejected', 'admin_rejected']);
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }
    // Getter Methods

    public function getStatusTextAttribute()
    {
        $statusLabels = [
            'pending_manager' => 'Pending Manager',
            'pending_hr' => 'Pending HR',
            'pending_admin' => 'Pending Admin',
            'approved' => 'Approved',
            'manager_rejected' => 'Manager Rejected',
            'hr_rejected' => 'HR Rejected',
            'admin_rejected' => 'Admin Rejected',
            'cancelled' => 'Cancelled'
        ];

        return $statusLabels[$this->status] ?? $this->status;
    }

    public function getStatusBadgeClassAttribute()
    {
        $statusClasses = [
            'pending_manager' => 'bg-warning text-dark',
            'pending_hr' => 'bg-warning text-dark',
            'pending_admin' => 'bg-warning text-dark',
            'approved' => 'bg-success',
            'manager_rejected' => 'bg-danger',
            'hr_rejected' => 'bg-danger',
            'admin_rejected' => 'bg-danger',
            'cancelled' => 'bg-secondary'
        ];

        return $statusClasses[$this->status] ?? 'bg-secondary';
    }

    public function getCurrentLevelAttribute()
    {
        if ($this->status === 'approved') {
            return 'Approved';
        }
        if ($this->isRejected()) {
            return 'Rejected';
        }
        if ($this->status === 'cancelled') {
            return 'Cancelled';
        }

        switch ($this->status) {
            case 'pending_manager':
                return 'Manager';
            case 'pending_hr':
                return 'HR';
            case 'pending_admin':
                return 'Admin';
            default:
                return 'Pending';
        }
    }

    public function getApprovalFlowAttribute()
    {
        $flow = [];
        
        $flow[] = [
            'level' => 'Manager',
            'status' => $this->status === 'pending_manager' ? 'pending' : 
                       ($this->status === 'manager_rejected' ? 'rejected' : 
                       ($this->status === 'pending_hr' || $this->status === 'pending_admin' || $this->status === 'approved' ? 'approved' : 'pending')),
            'remarks' => $this->manager_comment,
            'approved_at' => $this->manager_approved_at,
        ];

        $flow[] = [
            'level' => 'HR',
            'status' => $this->status === 'pending_hr' ? 'pending' : 
                       ($this->status === 'hr_rejected' ? 'rejected' : 
                       ($this->status === 'pending_admin' || $this->status === 'approved' ? 'approved' : 'pending')),
            'remarks' => $this->hr_comment,
            'approved_at' => $this->hr_approved_at,
        ];

        $flow[] = [
            'level' => 'Admin',
            'status' => $this->status === 'pending_admin' ? 'pending' : 
                       ($this->status === 'admin_rejected' ? 'rejected' : 
                       ($this->status === 'approved' ? 'approved' : 'pending')),
            'remarks' => $this->admin_comment,
            'approved_at' => null,
        ];

        $flow[] = [
            'level' => 'Final',
            'status' => $this->status === 'approved' ? 'approved' : 
                       ($this->isRejected() ? 'rejected' : 
                       ($this->status === 'cancelled' ? 'cancelled' : 'pending')),
            'remarks' => null,
            'approved_at' => null,
        ];

        return $flow;
    }

    // Scopes

    public function scopePending($query)
    {
        return $query->whereIn('status', ['pending_manager', 'pending_hr', 'pending_admin']);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->whereIn('status', ['manager_rejected', 'hr_rejected', 'admin_rejected']);
    }

    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeForManager($query, $managerId)
    {
        return $query->whereHas('employee', function($q) use ($managerId) {
            $q->where('manager_id', $managerId);
        });
    }
}
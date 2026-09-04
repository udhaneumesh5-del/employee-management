<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReimbursementApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'reimbursement_id',
        'user_id',
        'role',
        'approval_level',
        'action',
        'previous_status',
        'new_status',
        'remarks',
        'is_final_approval'
    ];

    protected $casts = [
        'is_final_approval' => 'boolean'
    ];

    // Relationships
    public function reimbursement()
    {
        return $this->belongsTo(Reimbursement::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Get action text
    public function getActionTextAttribute()
    {
        $actions = [
            'submitted' => 'Submitted',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'cancelled' => 'Cancelled',
            'paid' => 'Paid',
            'exception_approved' => 'Exception Approved'
        ];
        return $actions[$this->action] ?? $this->action;
    }
}
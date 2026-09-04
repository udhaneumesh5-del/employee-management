<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReimbursementPolicy extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'expense_type_id',
        'maximum_amount',
        'limit_type',
        'limit_period',
        'maximum_claims',
        'receipt_required',
        'exception_allowed',
        'effective_from',
        'effective_to',
        'description',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'maximum_amount' => 'decimal:2',
        'receipt_required' => 'boolean',
        'exception_allowed' => 'boolean',
        'effective_from' => 'date',
        'effective_to' => 'date'
    ];

    // Relationships
    public function expenseType()
    {
        return $this->belongsTo(ExpenseType::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Fix: Use 'policy_id' instead of default 'reimbursement_policy_id'
    public function reimbursements()
    {
        return $this->hasMany(Reimbursement::class, 'policy_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active')
                     ->where('effective_from', '<=', now())
                     ->where(function($q) {
                         $q->where('effective_to', '>=', now())
                           ->orWhereNull('effective_to');
                     });
    }

    public function isActive()
    {
        return $this->status === 'Active' &&
               $this->effective_from <= now() &&
               ($this->effective_to === null || $this->effective_to >= now());
    }
}
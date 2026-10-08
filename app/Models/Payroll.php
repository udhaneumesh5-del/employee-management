<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'payroll_month', 'payroll_year',
        'total_working_days', 'present_days', 'absent_days',
        'paid_leave_days', 'unpaid_leave_days',
        'basic_salary', 'hra', 'conveyance_allowance', 'medical_allowance',
        'special_allowance', 'other_allowance', 'bonus', 'incentive', 'gross_salary',
        'absent_deduction', 'unpaid_leave_deduction', 'pf_deduction',
        'professional_tax', 'other_deduction', 'total_deductions',
        'reimbursement_amount', 'net_salary', 'total_payable',
        'status', 'generated_by', 'generated_at',
        'approved_by', 'approved_at', 'processed_by', 'processed_at',
        'paid_by', 'paid_at', 'payment_method', 'payment_reference', 'remarks'
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'hra' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'total_payable' => 'decimal:2',
        'generated_at' => 'datetime',
        'approved_at' => 'datetime',
        'processed_at' => 'datetime',
        'paid_at' => 'datetime'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function paidBy()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    public function adjustments()
    {
        return $this->hasMany(PayrollAdjustment::class);
    }

    public function getMonthNameAttribute()
    {
        return date('F', mktime(0, 0, 0, $this->payroll_month, 1));
    }

    public function getStatusBadgeClassAttribute()
    {
        $classes = [
            'Draft' => 'bg-secondary',
            'Pending' => 'bg-warning text-dark',
            'Approved' => 'bg-info text-white',
            'Processed' => 'bg-primary',
            'Paid' => 'bg-success',
            'Cancelled' => 'bg-danger'
        ];
        return $classes[$this->status] ?? 'bg-secondary';
    }
}
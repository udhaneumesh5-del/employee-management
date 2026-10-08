<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'basic_salary', 'hra', 'conveyance_allowance',
        'medical_allowance', 'special_allowance', 'other_allowance',
        'pf_enabled', 'pf_percentage', 'professional_tax', 'other_deduction',
        'effective_from', 'effective_to', 'status'
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'hra' => 'decimal:2',
        'conveyance_allowance' => 'decimal:2',
        'medical_allowance' => 'decimal:2',
        'special_allowance' => 'decimal:2',
        'other_allowance' => 'decimal:2',
        'pf_percentage' => 'decimal:2',
        'professional_tax' => 'decimal:2',
        'other_deduction' => 'decimal:2',
        'effective_from' => 'date',
        'effective_to' => 'date',
        'pf_enabled' => 'boolean'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getGrossSalaryAttribute()
    {
        return $this->basic_salary + $this->hra + $this->conveyance_allowance
            + $this->medical_allowance + $this->special_allowance + $this->other_allowance;
    }
}
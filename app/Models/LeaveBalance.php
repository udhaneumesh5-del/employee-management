<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'year',
        'allocated_days',
        'used_days',
        'remaining_days'
    ];

    protected $casts = [
        'year' => 'integer',
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

    // Update remaining days
    public function updateRemaining()
    {
        $this->remaining_days = $this->allocated_days - $this->used_days;
        $this->save();
        return $this;
    }

    // Check if sufficient balance
    public function hasSufficientBalance($days)
    {
        return $this->remaining_days >= $days;
    }

    // Deduct used days
    public function deductUsedDays($days)
    {
        $this->used_days += $days;
        $this->updateRemaining();
        return $this;
    }
}
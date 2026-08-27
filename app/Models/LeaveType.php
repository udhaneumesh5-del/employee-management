<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'annual_limit',
        'max_consecutive_days',
        'carry_forward',
        'is_paid',
        'requires_document',
        'is_active',
        'description'
    ];

    protected $casts = [
        'carry_forward' => 'boolean',
        'is_paid' => 'boolean',
        'requires_document' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function leaveBalances()
    {
        return $this->hasMany(LeaveBalance::class);
    }

    // Get active leave types
    public static function getActive()
    {
        return self::where('is_active', true)->get();
    }

    // Get leave type by code
    public static function getByCode($code)
    {
        return self::where('code', $code)->first();
    }
}
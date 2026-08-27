<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeavePolicy extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'max_consecutive_days',
        'allow_carry_forward',
        'carry_forward_limit',
        'requires_document',
        'is_active'
    ];

    protected $casts = [
        'allow_carry_forward' => 'boolean',
        'requires_document' => 'boolean',
        'is_active' => 'boolean',
    ];

    public static function getActive()
    {
        return self::where('is_active', true)->first();
    }
}
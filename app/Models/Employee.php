<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_code',
        'first_name',
        'last_name',
        'email',
        'mobile_number',
        'designation',
        'salary',
        'joining_date',
        'status',
        'profile_image',
        'department_id'  
    ];

    // Employee belongs to department
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Get profile image URL
    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image && Storage::disk('public')->exists($this->profile_image)) {
            return asset('storage/' . $this->profile_image);
        }
        
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->first_name . '+' . $this->last_name) . '&background=0D6EFD&color=fff&size=100';
    }
}
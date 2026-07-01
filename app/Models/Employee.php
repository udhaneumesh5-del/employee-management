<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'profile_image'
    ];

    // ✅ ही एकच method ठेवा (जुनी काढून टाका)
    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image && file_exists(storage_path('app/public/' . $this->profile_image))) {
            return asset('storage/' . $this->profile_image);
        }

        $name = urlencode($this->first_name . ' ' . $this->last_name);

        return 'https://ui-avatars.com/api/?name=' . $name . '&background=0D6EFD&color=fff&size=100';
    }
}
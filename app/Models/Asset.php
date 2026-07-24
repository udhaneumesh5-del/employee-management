<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_code',
        'employee_name',
        'department',
        'asset_type',
        'issue_date',
        'return_date',
        'status',
        'condition',
        'remarks'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'return_date' => 'date',
    ];

    // Get all unique asset types from database
    public static function getAssetTypes()
    {
        return Asset::distinct()->pluck('asset_type')->toArray();
    }

    // Get all unique asset types with count
    public static function getAssetTypesWithCount()
    {
        return Asset::select('asset_type', \DB::raw('count(*) as total'))
                    ->groupBy('asset_type')
                    ->get();
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetReturn extends Model
{
    use HasFactory;

    protected $table = 'asset_returns';

    protected $fillable = [
        'employee_code',
        'employee_name',
        'department',
        'asset_id',
        'asset_code',
        'asset_type',
        'company_name',
        'model',
        'condition',
        'issue_date',
        'return_date',
        'return_reason',
        'remarks'
    ];

    public function asset()
    {
        return $this->belongsTo(AssetMaster::class, 'asset_id');
    }

    public static function getReturnReasons()
    {
        return ['Exchange', 'Employee Resigned', 'Hardware Problem', 'Repair Required', 'Other'];
    }

    public static function getConditions()
    {
        return ['Good', 'Damaged', 'Repair Required'];
    }
}
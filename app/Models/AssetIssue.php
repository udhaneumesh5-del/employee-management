<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetIssue extends Model
{
    use HasFactory;

    protected $table = 'asset_issues';

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
        'remarks'
    ];

    public function asset()
    {
        return $this->belongsTo(AssetMaster::class, 'asset_id');
    }
}
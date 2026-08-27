<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMaster extends Model
{
    use HasFactory;

    protected $table = 'assets_master';

    protected $fillable = [
        'asset_code',
        'asset_type',
        'company_name',
        'model',
        'condition',
        'status',
        'remarks'
    ];

    // Get Available Assets
    public static function getAvailable()
    {
        return self::where('status', 'Available')->get();
    }

    // Get Issued Assets
    public static function getIssued()
    {
        return self::where('status', 'Issued')->get();
    }
}
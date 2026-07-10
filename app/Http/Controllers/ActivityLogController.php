<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = DB::table('activity_logs')
                    ->orderBy('performed_at', 'desc')
                    ->paginate(15);
        
        return view('activity-logs.index', compact('logs'));
    }
}
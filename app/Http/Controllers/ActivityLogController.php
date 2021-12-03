<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function userLogs(){
        $logs = ActivityLog::where('user_type', 'User')->where('user_id', auth()->user()->id)->latest()->get();
        return response()->json($logs);
    }

    public function adminLogs(){
        $logs = ActivityLog::where('user_type', 'Admin')->where('user_id', auth('admin')->user()->id)->latest()->get();
        return response()->json($logs);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\SystemLog;

class AdminSystemLogController extends Controller
{
    public function index()
    {
        $systemLogs = SystemLog::with('user')->latest()->paginate(50);

        return view('admin.system-logs.index', compact('systemLogs'));
    }
}


<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $stats = [
            'buses' => Bus::count(),
            'drivers' => User::where('role', 'driver')->count(),
            'billings' => 6, // Hardcoded for now until Billing module is active
        ];

        return view('admin.dashboard', compact('stats'));
    }
}

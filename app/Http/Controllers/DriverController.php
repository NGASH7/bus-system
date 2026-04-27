<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DriverController extends Controller
{
    /**
     * Display the driver dashboard.
     */
    public function index()
    {
        return view('driver.dashboard');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminLicenseController extends Controller
{
    /**
     * Display a listing of driver licenses.
     */
    public function index()
    {
        $drivers = User::where('role', 'driver')->get();
        
        // Add status to each driver for the view
        $drivers->each(function($driver) {
            if (!$driver->license_expiry) {
                $driver->license_status = 'none';
            } else {
                $days = (int) now()->startOfDay()->diffInDays(Carbon::parse($driver->license_expiry)->startOfDay(), false);
                if ($days < 0) {
                    $driver->license_status = 'expired';
                } elseif ($days <= 14) {
                    $driver->license_status = 'critical';
                } elseif ($days <= 60) {
                    $driver->license_status = 'warning';
                } else {
                    $driver->license_status = 'ok';
                }
            }
        });

        return view('admin.licenses.index', compact('drivers'));
    }

    /**
     * Show the form for editing the specified license.
     */
    public function edit(User $license)
    {
        // Using $license as the variable name to match resource routing if needed
        $driver = $license;
        return view('admin.licenses.edit', compact('driver'));
    }

    /**
     * Update the specified license in storage.
     */
    public function update(Request $request, User $license)
    {
        $driver = $license;
        $request->validate([
            'license_number' => 'required|string|max:255',
            'license_expiry' => 'required|date',
        ]);

        $driver->update([
            'license_number' => $request->license_number,
            'license_expiry' => $request->license_expiry,
        ]);

        return redirect()->route('admin.licenses.index')
            ->with('success', 'Driving license for ' . $driver->name . ' updated successfully.');
    }
}

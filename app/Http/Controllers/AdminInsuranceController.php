<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminInsuranceController extends Controller
{
    /**
     * Display a listing of bus insurances.
     */
    public function index()
    {
        $buses = Bus::with('driver')->get();
        
        // Add status to each bus for the view
        $buses->each(function($bus) {
            if (!$bus->insurance_expiry) {
                $bus->insurance_status = 'none';
            } else {
                $days = (int) now()->startOfDay()->diffInDays(Carbon::parse($bus->insurance_expiry)->startOfDay(), false);
                if ($days < 0) {
                    $bus->insurance_status = 'expired';
                } elseif ($days <= 7) {
                    $bus->insurance_status = 'critical';
                } elseif ($days <= 30) {
                    $bus->insurance_status = 'warning';
                } else {
                    $bus->insurance_status = 'ok';
                }
            }
        });

        return view('admin.insurance.index', compact('buses'));
    }

    /**
     * Show the form for editing the specified insurance.
     */
    public function edit(Bus $insurance)
    {
        $bus = $insurance;
        return view('admin.insurance.edit', compact('bus'));
    }

    /**
     * Update the specified insurance in storage.
     */
    public function update(Request $request, Bus $insurance)
    {
        $bus = $insurance;
        $request->validate([
            'policy_number' => 'required|string|max:255',
            'underwriter' => 'required|string|max:255',
            'insurance_expiry' => 'required|date',
            'coverage_type' => 'required|string|max:255',
            'emergency_number' => 'required|string|max:255',
        ]);

        $bus->update([
            'policy_number' => $request->policy_number,
            'underwriter' => $request->underwriter,
            'insurance_expiry' => $request->insurance_expiry,
            'coverage_type' => $request->coverage_type,
            'emergency_number' => $request->emergency_number,
        ]);

        return redirect()->route('admin.insurance.index')
            ->with('success', 'Insurance details for ' . $bus->plate_number . ' updated successfully.');
    }
}

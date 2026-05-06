<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminInspectionController extends Controller
{
    /**
     * Display a listing of bus inspections.
     */
    public function index()
    {
        $buses = Bus::with('driver')->get();
        
        // Add status to each bus for the view
        $buses->each(function($bus) {
            if (!$bus->inspection_expiry) {
                $bus->inspection_status = 'none';
            } else {
                $days = (int) now()->startOfDay()->diffInDays(Carbon::parse($bus->inspection_expiry)->startOfDay(), false);
                if ($days < 0) {
                    $bus->inspection_status = 'expired';
                } elseif ($days <= 7) {
                    $bus->inspection_status = 'critical';
                } elseif ($days <= 30) {
                    $bus->inspection_status = 'warning';
                } else {
                    $bus->inspection_status = 'ok';
                }
            }
        });

        return view('admin.inspection.index', compact('buses'));
    }

    /**
     * Show the form for editing the specified inspection.
     */
    public function edit(Bus $inspection)
    {
        $bus = $inspection;
        return view('admin.inspection.edit', compact('bus'));
    }

    /**
     * Update the specified inspection in storage.
     */
    public function update(Request $request, Bus $inspection)
    {
        $bus = $inspection;
        $request->validate([
            'inspection_expiry' => 'required|date',
            'inspection_certificate' => 'nullable|string|max:255',
        ]);

        $bus->update([
            'inspection_expiry' => $request->inspection_expiry,
            'inspection_certificate' => $request->inspection_certificate,
        ]);

        return redirect()->route('admin.inspection.index')
            ->with('success', 'Inspection details for ' . $bus->plate_number . ' updated successfully.');
    }
}

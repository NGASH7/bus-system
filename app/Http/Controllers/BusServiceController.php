<?php

namespace App\Http\Controllers;

use App\Models\BusService;
use App\Models\Bus;
use App\Models\Billing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusServiceController extends Controller
{
    // Driver side: List and form
    public function driverIndex()
    {
        $services = BusService::where('driver_id', Auth::id())->latest()->get();
        $bus = Auth::user()->bus;
        return view('driver.bus-service.index', compact('services', 'bus'));
    }

    // Driver side: Store
    public function driverStore(Request $request)
    {
        $request->validate([
            'description' => 'required|string',
            'cost' => 'required|numeric|min:0',
        ]);

        $bus = Auth::user()->bus;
        if (!$bus) {
            return back()->with('error', 'You are not assigned to any bus.');
        }

        BusService::create([
            'bus_id' => $bus->id,
            'driver_id' => Auth::id(),
            'description' => $request->description,
            'cost' => $request->cost,
            'status' => 'pending',
            'added_by' => 'driver',
        ]);

        return back()->with('success', 'Bus service request submitted successfully.');
    }

    // Admin side: List and billing
    public function adminIndex()
    {
        $services = BusService::with(['bus', 'driver'])->latest()->get();
        $buses = Bus::all();

        // Expense Summary Calculation
        $summary = [
            'pending' => $services->where('status', 'pending')->sum('cost'),
            'approved' => $services->where('status', 'approved')->sum('cost'),
            'paid' => $services->where('status', 'paid')->sum('cost'),
            'total' => $services->sum('cost'),
        ];

        return view('admin.billing.index', compact('services', 'summary', 'buses'));
    }

    // Admin side: Store (Admin adding a bill/service)
    public function adminStore(Request $request)
    {
        $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'description' => 'required|string',
            'cost' => 'required|numeric|min:0',
            'status' => 'required|string',
        ]);

        BusService::create([
            'bus_id' => $request->bus_id,
            'driver_id' => null, // Admin added
            'description' => $request->description,
            'cost' => $request->cost,
            'status' => $request->status,
            'added_by' => 'admin',
        ]);

        return back()->with('success', 'Billing entry added successfully.');
    }

    // Admin side: Update status
    public function updateStatus(Request $request, BusService $busService)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $busService->update(['status' => $request->status]);

        return back()->with('success', 'Status updated successfully.');
    }
}

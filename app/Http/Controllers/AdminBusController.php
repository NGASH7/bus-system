<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminBusController extends Controller
{
    public function index()
    {
        $buses = Bus::latest()->get();
        return view('admin.buses.index', compact('buses'));
    }

    public function create()
    {
        return view('admin.buses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plate_number' => 'required|unique:buses',
            'model' => 'required',
            'capacity' => 'required|integer|min:1',
            'insurance_expiry' => 'nullable|date',
            'license_expiry' => 'nullable|date',
            'photo' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('buses', 'public');
        }

        Bus::create($validated);

        return redirect()->route('admin.buses.index')->with('success', 'Bus added successfully.');
    }

    public function edit(Bus $bus)
    {
        return view('admin.buses.edit', compact('bus'));
    }

    public function show(Bus $bus)
    {
        return view('admin.buses.show', compact('bus'));
    }

    public function update(Request $request, Bus $bus)
    {
        $validated = $request->validate([
            'plate_number' => 'required|unique:buses,plate_number,' . $bus->id,
            'model' => 'required',
            'capacity' => 'required|integer|min:1',
            'insurance_expiry' => 'nullable|date',
            'license_expiry' => 'nullable|date',
            'photo' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('photo')) {
            if ($bus->photo_path) {
                Storage::disk('public')->delete($bus->photo_path);
            }
            $validated['photo_path'] = $request->file('photo')->store('buses', 'public');
        }

        $bus->update($validated);

        return redirect()->route('admin.buses.index')->with('success', 'Bus updated successfully.');
    }

    public function destroy(Bus $bus)
    {
        $bus->delete();
        return redirect()->route('admin.buses.index')->with('success', 'Bus removed successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminDriverController extends Controller
{
    public function index()
    {
        $drivers = User::where('role', 'driver')->latest()->get();
        return view('admin.drivers.index', compact('drivers'));
    }

    public function create()
    {
        return view('admin.drivers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required|string|max:20',
            'license_number' => 'nullable|string|max:50',
            'license_expiry' => 'nullable|date',
            'national_id' => 'nullable|string|max:50',
        ]);

        User::create(array_merge($validated, [
            'password' => Hash::make('driver123'),
            'role' => 'driver',
            'must_change_password' => true,
        ]));

        return redirect()->route('admin.drivers.index')->with('success', 'Driver added successfully.');
    }

    public function show(User $driver)
    {
        $driver->load('bus');
        return view('admin.drivers.show', compact('driver'));
    }

    public function edit(User $driver)
    {
        return view('admin.drivers.edit', compact('driver'));
    }

    public function update(Request $request, User $driver)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $driver->id,
            'phone_number' => 'required|string|max:20',
            'license_number' => 'nullable|string|max:50',
            'license_expiry' => 'nullable|date',
            'national_id' => 'nullable|string|max:50',
        ]);

        $data = $validated;

        // Emergency Password Reset to default
        if ($request->has('reset_password')) {
            $data['password'] = Hash::make('driver123');
            $data['must_change_password'] = true;
        }

        $driver->update($data);

        return redirect()->route('admin.drivers.index')->with('success', 'Driver profile updated successfully.');
    }

    public function destroy(User $driver)
    {
        $driver->delete();
        return redirect()->route('admin.drivers.index')->with('success', 'Driver removed successfully.');
    }
}

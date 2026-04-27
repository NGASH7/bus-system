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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'driver',
        ]);

        return redirect()->route('admin.drivers.index')->with('success', 'Driver added successfully.');
    }

    public function edit(User $driver)
    {
        return view('admin.drivers.edit', compact('driver'));
    }

    public function update(Request $request, User $driver)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $driver->id,
        ]);

        $driver->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $driver->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.drivers.index')->with('success', 'Driver updated successfully.');
    }

    public function destroy(User $driver)
    {
        $driver->delete();
        return redirect()->route('admin.drivers.index')->with('success', 'Driver removed successfully.');
    }
}

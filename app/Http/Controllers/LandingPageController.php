<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Bus;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index(Request $request)
    {
        $availableBuses = null;
        $selectedDate = null;
        $destination = null;
        $preferredCapacity = null;

        if ($request->filled('availability_date')) {
            $validated = $request->validate([
                'availability_date' => ['required', 'date', 'after_or_equal:today'],
                'destination' => ['nullable', 'string', 'max:255'],
                'preferred_capacity' => ['nullable', 'integer', 'min:1', 'max:200'],
            ]);

            $selectedDate = $validated['availability_date'];
            $destination = $validated['destination'] ?? null;
            $preferredCapacity = $validated['preferred_capacity'] ?? null;

            $bookedBusIds = Booking::query()
                ->whereIn('status', ['pending', 'countered', 'accepted'])
                ->where(function ($query) use ($selectedDate) {
                    $query->whereDate('date', $selectedDate)
                        ->orWhere(function ($subQuery) use ($selectedDate) {
                            $subQuery->whereNotNull('return_date')
                                ->whereDate('date', '<=', $selectedDate)
                                ->whereDate('return_date', '>=', $selectedDate);
                        });
                })
                ->whereNotNull('bus_id')
                ->pluck('bus_id');

            $availableBuses = Bus::query()
                ->where('is_active', true)
                ->whereNotIn('id', $bookedBusIds)
                ->when($preferredCapacity, function ($query) use ($preferredCapacity) {
                    $query->where('capacity', '>=', $preferredCapacity);
                })
                ->orderBy('plate_number')
                ->get();
        }

        return view('index', compact('availableBuses', 'selectedDate', 'destination', 'preferredCapacity'));
    }
}

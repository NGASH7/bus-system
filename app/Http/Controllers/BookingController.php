<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Bus;
use App\Support\SystemActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Show the booking form for a specific bus.
     */
    public function create(Request $request)
    {
        $selectedBus = null;
        if ($request->has('bus_id')) {
            $selectedBus = Bus::find($request->bus_id);
        }
        
        $buses = Bus::where('is_active', true)->get();
        $prefill = [
            'destination' => $request->input('destination'),
            'date' => $request->input('date'),
            'pickup_time' => $request->input('pickup_time'),
        ];
        
        return view('bookings.create', compact('buses', 'selectedBus', 'prefill'));
    }

    /**
     * Store a new booking request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'service_type' => 'required|string',
            'pickup_location' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'date' => 'required|date|after_or_equal:today',
            'pickup_time' => 'required',
            'return_date' => 'nullable|date|after_or_equal:date',
            'return_time' => 'nullable',
            'offered_price' => 'required|numeric|min:0',
            'details' => 'nullable|string',
        ]);

        $booking = Booking::create(array_merge($validated, [
            'user_id' => Auth::id(),
            'status' => 'pending',
            'amount' => $validated['offered_price'],
        ]));
        SystemActivity::record(
            'booking.created',
            Auth::user()->name . " created booking #{$booking->id} for {$booking->destination}.",
            Auth::user(),
            ['booking_id' => $booking->id, 'bus_id' => $booking->bus_id]
        );

        return redirect()->route('dashboard')->with('success', 'Your booking request has been submitted. The admin will review your offer shortly.');
    }

    /**
     * Display a listing of the user's bookings.
     */
    public function index()
    {
        $bookings = Booking::with('bus.driver')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
            
        return view('bookings.index', compact('bookings'));
    }

    /**
     * Accept a counter-offer from the admin.
     */
    public function acceptCounter(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status !== 'countered') {
            return back()->with('error', 'This booking does not have an active counter-offer.');
        }

        $booking->update([
            'status' => 'accepted',
            'offered_price' => $booking->counter_price // Formalize the new price
        ]);
        SystemActivity::record(
            'booking.counter.accepted',
            Auth::user()->name . " accepted counter-offer for booking #{$booking->id}.",
            Auth::user(),
            ['booking_id' => $booking->id]
        );

        return back()->with('success', 'You have accepted the counter-offer. Your booking is now confirmed!');
    }
}

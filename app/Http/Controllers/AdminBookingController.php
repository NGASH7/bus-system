<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Support\SystemActivity;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'bus'])->latest()->get();
        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'bus']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function accept(Booking $booking)
    {
        $booking->update([
            'status' => 'accepted'
        ]);
        SystemActivity::record(
            'booking.accepted',
            auth()->user()->name . " accepted booking #{$booking->id}.",
            auth()->user(),
            ['booking_id' => $booking->id]
        );

        return redirect()->route('admin.bookings.show', $booking->id)->with('success', 'Offer accepted and booking confirmed.');
    }

    public function reject(Booking $booking)
    {
        $booking->update([
            'status' => 'rejected'
        ]);
        SystemActivity::record(
            'booking.rejected',
            auth()->user()->name . " rejected booking #{$booking->id}.",
            auth()->user(),
            ['booking_id' => $booking->id]
        );

        return redirect()->route('admin.bookings.show', $booking->id)->with('success', 'Booking offer has been rejected.');
    }

    public function counter(Request $request, Booking $booking)
    {
        $request->validate([
            'counter_price' => 'required|numeric|min:0',
        ]);

        $booking->update([
            'counter_price' => $request->counter_price,
            'status' => 'countered'
        ]);
        SystemActivity::record(
            'booking.countered',
            auth()->user()->name . " countered booking #{$booking->id} with {$request->counter_price}.",
            auth()->user(),
            ['booking_id' => $booking->id, 'counter_price' => $request->counter_price]
        );

        return redirect()->route('admin.bookings.show', $booking->id)->with('success', 'Counter-offer has been sent to the user.');
    }
}

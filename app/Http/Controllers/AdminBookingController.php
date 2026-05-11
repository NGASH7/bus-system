<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminBookingController extends Controller
{
    public function index()
    {
        // Only show bookings that need attention (pending or countered)
        $bookings = Booking::with(['user', 'bus'])
            ->whereNotIn('status', ['accepted', 'rejected'])
            ->latest()
            ->get();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function history()
    {
        // Show bookings that are already finalized
        $bookings = Booking::with(['user', 'bus'])
            ->whereIn('status', ['accepted', 'rejected'])
            ->latest()
            ->get();

        return view('admin.bookings.history', compact('bookings'));
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

        // Redirect to receipt generation with pre-filled data
        return redirect()->route('admin.receipts.create', ['booking_id' => $booking->id])
            ->with('success', 'Booking accepted. Review and generate the receipt below.');
    }

    public function reject(Booking $booking)
    {
        $booking->update([
            'status' => 'rejected'
        ]);

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

        return redirect()->route('admin.bookings.show', $booking->id)->with('success', 'Counter-offer has been sent to the user.');
    }

    public function confirmPayment(Booking $booking)
    {
        if ($booking->status !== 'accepted') {
            return back()->with('Jerror', 'Only accepted bookings can be payment-confirmed.');
        }

        $booking->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        if (!\App\Models\Receipt::where('booking_id', $booking->id)->exists()) {
            \App\Models\Receipt::create([
                'receipt_no' => 'MW-' . strtoupper(\Illuminate\Support\Str::random(8)),
                'customer_name' => $booking->user->name,
                'customer_phone' => $booking->payer_phone ?: ($booking->user->phone_number ?? 'N/A'),
                'bus_number' => optional($booking->bus)->plate_number ?: 'N/A',
                'trip_route' => $booking->pickup_location . ' to ' . $booking->destination,
                'amount' => $booking->counter_price ?: $booking->offered_price ?: $booking->amount,
                'payment_method' => $booking->payment_method ?: 'Manual',
                'receipt_date' => now()->toDateString(),
                'booking_id' => $booking->id,
            ]);
        }

        return back()->with('success', 'Payment confirmed and receipt generated.');
    }

    public function rejectPayment(Booking $booking)
    {
        if ($booking->status !== 'accepted') {
            return back()->with('error', 'Only accepted bookings can have payment updates.');
        }

        $booking->update([
            'payment_status' => 'failed',
        ]);

        return back()->with('success', 'Payment request rejected. User will need to retry payment.');
    }
}

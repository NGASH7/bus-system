<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

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

        // Notify User
        if ($booking->user && $booking->user->phone_number) {
            $msg = "Congratulations! Your booking #{$booking->id} for {$booking->destination} has been ACCEPTED. Thank you for choosing Mwigito Excel.";
            app(\App\Services\CelcomSmsService::class)->send($booking->user->phone_number, $msg);
        }
        
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

        // Notify User
        if ($booking->user && $booking->user->phone_number) {
            $msg = "Review Needed: Mwigito Excel has sent a counter-offer for Booking #{$booking->id}. New price: KES " . number_format($request->counter_price, 0) . ". Check your history to accept.";
            app(\App\Services\CelcomSmsService::class)->send($booking->user->phone_number, $msg);
        }

        return redirect()->route('admin.bookings.show', $booking->id)->with('success', 'Counter-offer has been sent to the user.');
    }
}

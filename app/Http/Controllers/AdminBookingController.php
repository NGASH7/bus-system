<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Support\SystemActivity;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    protected $smsService;

    public function __construct(\App\Services\CelcomSmsService $smsService)
    {
        $this->smsService = $smsService;
    }

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

        // Notify User
        if ($booking->user) {
            $msg = "Congratulations! Your booking #{$booking->id} for {$booking->destination} has been ACCEPTED. Thank you for choosing Mwigito Excel.";
            if ($booking->user->phone_number) {
                $this->smsService->send($booking->user->phone_number, $msg);
            }
            if ($booking->user->email) {
                \Illuminate\Support\Facades\Mail::raw($msg, function ($mail) use ($booking) {
                    $mail->to($booking->user->email)->subject("Booking Accepted - #" . $booking->id);
                });
            }
        }

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

        // Notify User
        if ($booking->user) {
            $msg = "Review Needed: Mwigito Excel has sent a counter-offer for Booking #{$booking->id}. New price: KES " . number_format($request->counter_price, 0) . ". Check your history to accept.";
            if ($booking->user->phone_number) {
                $this->smsService->send($booking->user->phone_number, $msg);
            }
            if ($booking->user->email) {
                \Illuminate\Support\Facades\Mail::raw($msg, function ($mail) use ($booking) {
                    $mail->to($booking->user->email)->subject("Counter-offer Received - #" . $booking->id);
                });
            }
        }

        return redirect()->route('admin.bookings.show', $booking->id)->with('success', 'Counter-offer has been sent to the user.');
    }
}

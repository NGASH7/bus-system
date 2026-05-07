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

        $booking->load(['bus.driver']);

        // Notify User
        if ($booking->user) {
            $msg = "Congratulations! Your booking #{$booking->id} for {$booking->destination} has been ACCEPTED. Thank you for choosing Mwigito Excel.";
            if ($booking->user->phone_number) {
                app(\App\Services\CelcomSmsService::class)->send($booking->user->phone_number, $msg);
            }
            if ($booking->user->email) {
                try {
                    \Illuminate\Support\Facades\Mail::raw($msg, function ($mail) use ($booking) {
                        $mail->to($booking->user->email)->subject('Mwigito Excel: Booking Accepted');
                    });
                } catch (\Exception $e) {
                    Log::error("Failed to send booking accepted email to {$booking->user->email}: " . $e->getMessage());
                }
            }
        }

        // Notify Driver
        if ($booking->bus && $booking->bus->driver) {
            $driverMsg = "New scheduled trip! You have been assigned a trip to {$booking->destination} on " . \Carbon\Carbon::parse($booking->date)->format('d M, Y') . ". Check your dashboard for details.";
            if ($booking->bus->driver->phone_number) {
                app(\App\Services\CelcomSmsService::class)->send($booking->bus->driver->phone_number, $driverMsg);
            }
            if ($booking->bus->driver->email) {
                try {
                    \Illuminate\Support\Facades\Mail::raw($driverMsg, function ($mail) use ($booking) {
                        $mail->to($booking->bus->driver->email)->subject('Mwigito Excel: New Trip Assignment');
                    });
                } catch (\Exception $e) {
                    Log::error("Failed to send assignment email to driver {$booking->bus->driver->email}: " . $e->getMessage());
                }
            }
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
        if ($booking->user) {
            $msg = "Review Needed: Mwigito Excel has sent a counter-offer for Booking #{$booking->id}. New price: KES " . number_format($request->counter_price, 0) . ". Check your history to accept.";
            if ($booking->user->phone_number) {
                app(\App\Services\CelcomSmsService::class)->send($booking->user->phone_number, $msg);
            }
            if ($booking->user->email) {
                try {
                    \Illuminate\Support\Facades\Mail::raw($msg, function ($mail) use ($booking) {
                        $mail->to($booking->user->email)->subject('Mwigito Excel: Booking Counter-Offer');
                    });
                } catch (\Exception $e) {
                    Log::error("Failed to send counter-offer email to {$booking->user->email}: " . $e->getMessage());
                }
            }
        }

        return redirect()->route('admin.bookings.show', $booking->id)->with('success', 'Counter-offer has been sent to the user.');
    }
}

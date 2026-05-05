<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Bus;
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
        
        return view('bookings.create', compact('buses', 'selectedBus'));
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

        return back()->with('success', 'You have accepted the counter-offer. Your booking is now confirmed!');
    }

    /**
     * Display the receipt for a specific booking.
     */
    public function showReceipt(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $receipt = \App\Models\Receipt::where('booking_id', $booking->id)->first();

        if (!$receipt) {
            return back()->with('error', 'No receipt has been generated for this booking yet.');
        }

        return view('admin.receipts.show', compact('receipt'));
    }

    /**
     * Display a listing of the user's receipts.
     */
    public function indexReceipts()
    {
        $receipts = \App\Models\Receipt::whereHas('booking', function ($query) {
            $query->where('user_id', Auth::id());
        })->orderBy('receipt_date', 'desc')->get();

        return view('receipts.index', compact('receipts'));
    }
}

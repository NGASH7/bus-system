<?php
/*
 * This file is part of the Mwigito Excel Bus Management System.
 * (c) 2026 AdminReceiptController.php
 */

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AdminReceiptController extends Controller
{
    /**
     * Display a listing of manual receipts.
     */
    public function index()
    {
        $receipts = Receipt::whereNull('booking_id')->orderBy('receipt_date', 'desc')->get();
        $viewType = 'Manual';
        return view('admin.receipts.index', compact('receipts', 'viewType'));
    }

    /**
     * Display a listing of system-generated receipts.
     */
    public function systemIndex()
    {
        $receipts = Receipt::whereNotNull('booking_id')->orderBy('receipt_date', 'desc')->get();
        $viewType = 'System';
        return view('admin.receipts.index', compact('receipts', 'viewType'));
    }

    /**
     * Show the form for creating a new receipt.
     */
    public function create(Request $request)
    {
        $buses = Bus::where('is_active', true)->get();
        $prefill = null;

        if ($request->has('booking_id')) {
            $booking = \App\Models\Booking::with(['user', 'bus'])->find($request->booking_id);
            if ($booking) {
                $prefill = [
                    'booking_id' => $booking->id,
                    'customer_name' => $booking->user->name,
                    'customer_phone' => $booking->user->phone_number,
                    'bus_number' => $booking->bus->plate_number ?? '',
                    'trip_route' => $booking->pickup_location . ' to ' . $booking->destination,
                    'amount' => $booking->amount ?? $booking->counter_price ?? $booking->offered_price,
                ];
            }
        }

        return view('admin.receipts.create', compact('buses', 'prefill'));
    }

    /**
     * Store a newly created receipt in storage and display it.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'bus_number' => 'required|string|max:50',
            'trip_route' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'receipt_date' => 'required|date|before_or_equal:' . now()->format('Y-m-d'),
            'booking_id' => 'nullable|exists:bookings,id',
        ]);

        // Generate a random receipt number
        $validated['receipt_no'] = 'MW-' . strtoupper(Str::random(8));
        
        // Save to Database
        $receipt = Receipt::create($validated);

        // If it's a system booking, update the booking status to show it's receipted
        if ($receipt->booking_id) {
            $booking = \App\Models\Booking::find($receipt->booking_id);
            $booking->update(['payment_status' => 'paid']);
        }
        
        // Redirect to the receipt view page
        return redirect()->route('admin.receipts.show', $receipt->id)
            ->with('success', 'Receipt generated and saved successfully.');
    }

    /**
     * Display the specified receipt.
     */
    public function show(Receipt $receipt)
    {
        return view('admin.receipts.show', compact('receipt'));
    }
    
    /**
     * Send the receipt to the client.
     */
    public function send(Receipt $receipt)
    {
        $message = "Payment Confirmed! Receipt #{$receipt->receipt_no} for KES " . number_format($receipt->amount, 0) . " has been generated. Thank you for choosing Mwigito Excel.";
        
        if ($receipt->customer_phone) {
            app(\App\Services\CelcomSmsService::class)->send($receipt->customer_phone, $message);
        }

        // Send Email if we can get it via booking
        $receipt->load('booking.user');
        if ($receipt->booking && $receipt->booking->user && $receipt->booking->user->email) {
            try {
                \Illuminate\Support\Facades\Mail::raw($message, function ($mail) use ($receipt) {
                    $mail->to($receipt->booking->user->email)->subject('Mwigito Excel: Payment Confirmed');
                });
            } catch (\Exception $e) {
                Log::error("Failed to send receipt email to {$receipt->booking->user->email}: " . $e->getMessage());
            }
        }
        
        return redirect()->back()->with('success', 'Receipt has been sent to ' . $receipt->customer_name . ' via SMS & Email successfully.');
    }

    /**
     * Show the form for editing the specified receipt.
     */
    public function edit(Receipt $receipt)
    {
        $buses = Bus::where('is_active', true)->get();
        return view('admin.receipts.edit', compact('receipt', 'buses'));
    }

    /**
     * Update the specified receipt in storage.
     */
    public function update(Request $request, Receipt $receipt)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'bus_number' => 'required|string|max:50',
            'trip_route' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'receipt_date' => 'required|date|before_or_equal:' . now()->format('Y-m-d'),
        ]);

        $receipt->update($validated);

        return redirect()->route('admin.receipts.index')
            ->with('success', 'Receipt updated successfully.');
    }

    /**
     * Remove the specified receipt from storage.
     */
    public function destroy(Receipt $receipt)
    {
        $receipt->delete();
        return redirect()->route('admin.receipts.index')
            ->with('success', 'Receipt deleted successfully.');
    }
}

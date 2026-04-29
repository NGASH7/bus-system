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

class AdminReceiptController extends Controller
{
    /**
     * Display a listing of receipts.
     */
    public function index()
    {
        $receipts = Receipt::orderBy('receipt_date', 'desc')->get();
        return view('admin.receipts.index', compact('receipts'));
    }

    /**
     * Show the form for creating a new receipt.
     */
    public function create()
    {
        $buses = Bus::where('is_active', true)->get();
        return view('admin.receipts.create', compact('buses'));
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
        ]);

        // Generate a random receipt number
        $validated['receipt_no'] = 'MW-' . strtoupper(Str::random(8));
        
        // Save to Database
        $receipt = Receipt::create($validated);
        
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

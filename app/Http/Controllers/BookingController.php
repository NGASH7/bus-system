<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Bus;
use App\Models\Receipt;
use App\Services\DarajaStkService;
use App\Support\SystemActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

        $handoff = [
            'date' => $request->query('date'),
            'destination' => $request->query('destination'),
            'preferred_capacity' => $request->query('preferred_capacity'),
            'service_type' => $request->query('service_type'),
        ];

        $handoff = validator($handoff, [
            'date' => ['nullable', 'date', 'after_or_equal:today'],
            'destination' => ['nullable', 'string', 'max:255'],
            'preferred_capacity' => ['nullable', 'integer', 'min:1', 'max:200'],
            'service_type' => ['nullable', 'string', 'max:255'],
        ])->validate();

        // Suggest a baseline budget using bus capacity when a bus is selected.
        $suggestedPrice = null;
        if ($selectedBus) {
            $suggestedPrice = max(3000, ((int) $selectedBus->capacity) * 180);
        } elseif (!empty($handoff['preferred_capacity'])) {
            $suggestedPrice = max(3000, ((int) $handoff['preferred_capacity']) * 180);
        }

        $buses = Bus::where('is_active', true)->get();

        return view('bookings.create', compact('buses', 'selectedBus', 'handoff', 'suggestedPrice'));
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
            'Booking request submitted for destination "' . $booking->destination . '" on ' . $booking->date->format('d M Y') . '.',
            Auth::user(),
            [
                'booking_id' => $booking->id,
                'bus_id' => $booking->bus_id,
            ]
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

        return back()->with('success', 'You have accepted the counter-offer. Your booking is now confirmed!');
    }

    public function submitPayment(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status !== 'accepted') {
            return back()->with('error', 'Payment can only be submitted for accepted bookings.');
        }

        $validated = $request->validate([
            'payment_method' => ['required', 'in:Mpesa,Cheque,Bank,Cash'],
            'payer_phone' => ['nullable', 'string', 'max:20'],
        ]);

        $amount = (int) ($booking->counter_price ?: $booking->offered_price ?: $booking->amount);
        if ($amount <= 0) {
            $amount = 1;
        }

        if ($validated['payment_method'] !== 'Mpesa') {
            $booking->update([
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending_confirmation',
            ]);

            return back()->with('success', 'Payment mode submitted successfully. Awaiting finance confirmation.');
        }

        $phone = $this->normalizeKenyanPhone($validated['payer_phone'] ?? '');
        if (!$phone) {
            return back()->with('error', 'Please enter a valid Safaricom number, e.g. 07XXXXXXXX or 2547XXXXXXXX.');
        }

        $daraja = app(DarajaStkService::class);
        if (!$daraja->isConfigured()) {
            return back()->with('error', 'M-Pesa is not configured yet. Please contact admin to add Daraja credentials.');
        }

        $callbackUrl = config('services.mpesa.callback_url') ?: route('payments.mpesa.callback');
        if (!str_starts_with($callbackUrl, 'https://')) {
            return back()->with('error', 'Invalid Daraja callback URL. Set DARAJA_CALLBACK_URL to a public HTTPS URL (e.g. ngrok) and try again.');
        }

        if (str_contains($callbackUrl, 'localhost') || str_contains($callbackUrl, '127.0.0.1')) {
            return back()->with('error', 'Daraja callback URL cannot be localhost. Use a public tunnel URL in DARAJA_CALLBACK_URL.');
        }

        try {
            $response = $daraja->push([
                'amount' => $amount,
                'phone' => $phone,
                'callback_url' => $callbackUrl,
                'reference' => 'BOOK-' . $booking->id,
                'description' => 'Bus booking payment',
            ]);
        } catch (\Throwable $e) {
            return back()->with('error', 'Unable to send STK push right now: ' . $e->getMessage());
        }

        if (($response['ResponseCode'] ?? null) !== '0') {
            $endpointHint = !empty($response['_base_url']) ? ' [endpoint: ' . $response['_base_url'] . ']' : '';
            return back()->with('error', ($response['errorMessage'] ?? 'STK push request was not accepted. Try again.') . $endpointHint);
        }

        $booking->update([
            'payment_method' => 'Mpesa',
            'payer_phone' => $phone,
            'payment_status' => 'processing',
            'mpesa_checkout_request_id' => $response['CheckoutRequestID'] ?? null,
            'mpesa_merchant_request_id' => $response['MerchantRequestID'] ?? null,
        ]);

        return back()->with('success', 'STK push sent to ' . $phone . '. Complete payment on your phone.');
    }

    public function mpesaCallback(Request $request)
    {
        $callback = $request->input('Body.stkCallback');
        if (!$callback) {
            return response()->json(['ok' => false], 400);
        }

        $checkoutId = $callback['CheckoutRequestID'] ?? null;
        if (!$checkoutId) {
            return response()->json(['ok' => false], 400);
        }

        $booking = Booking::where('mpesa_checkout_request_id', $checkoutId)->first();
        if (!$booking) {
            return response()->json(['ok' => true]);
        }

        $resultCode = (int) ($callback['ResultCode'] ?? 1);
        if ($resultCode !== 0) {
            $booking->update(['payment_status' => 'failed']);
            return response()->json(['ok' => true]);
        }

        $metadataItems = collect($callback['CallbackMetadata']['Item'] ?? []);
        $receiptNo = $metadataItems->firstWhere('Name', 'MpesaReceiptNumber')['Value'] ?? null;
        $phone = (string) ($metadataItems->firstWhere('Name', 'PhoneNumber')['Value'] ?? $booking->payer_phone);

        $booking->update([
            'payment_status' => 'paid',
            'payment_reference' => $receiptNo,
            'payer_phone' => $phone,
            'paid_at' => now(),
        ]);

        $this->createReceiptIfMissing($booking, 'M-Pesa');

        return response()->json(['ok' => true]);
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

    public function schedule()
    {
        $today = now()->startOfDay();

        $upcomingBookings = Booking::with(['user', 'bus.driver'])
            ->where('status', 'accepted')
            ->whereDate('date', '>=', $today)
            ->orderBy('date')
            ->orderBy('pickup_time')
            ->get();

        $buses = Bus::where('is_active', true)
            ->with(['driver'])
            ->get()
            ->map(function (Bus $bus) use ($upcomingBookings) {
                $busBookings = $upcomingBookings->where('bus_id', $bus->id)->values();

                $nextBooking = $busBookings->first();
                $estimatedFreeAt = null;

                if ($nextBooking) {
                    if ($nextBooking->return_date && $nextBooking->return_time) {
                        $estimatedFreeAt = Carbon::parse($nextBooking->return_date->format('Y-m-d') . ' ' . $nextBooking->return_time);
                    } elseif ($nextBooking->return_date) {
                        $estimatedFreeAt = Carbon::parse($nextBooking->return_date->format('Y-m-d') . ' 18:00');
                    } else {
                        $estimatedFreeAt = Carbon::parse($nextBooking->date->format('Y-m-d') . ' ' . ($nextBooking->pickup_time ?: '08:00'))->addHours(4);
                    }
                }

                return [
                    'bus' => $bus,
                    'bookings' => $busBookings,
                    'next_booking' => $nextBooking,
                    'estimated_free_at' => $estimatedFreeAt,
                ];
            });

        return view('bookings.schedule', compact('buses', 'upcomingBookings'));
    }

    private function normalizeKenyanPhone(string $raw): ?string
    {
        $digits = preg_replace('/\D+/', '', $raw);
        if (!$digits) {
            return null;
        }

        if (Str::startsWith($digits, '0') && strlen($digits) === 10) {
            return '254' . substr($digits, 1);
        }

        if (Str::startsWith($digits, '254') && strlen($digits) === 12) {
            return $digits;
        }

        return null;
    }

    private function createReceiptIfMissing(Booking $booking, string $method): void
    {
        if (Receipt::where('booking_id', $booking->id)->exists()) {
            return;
        }

        Receipt::create([
            'receipt_no' => 'MW-' . strtoupper(Str::random(8)),
            'customer_name' => $booking->user->name,
            'customer_phone' => $booking->payer_phone ?: ($booking->user->phone_number ?? 'N/A'),
            'bus_number' => optional($booking->bus)->plate_number ?: 'N/A',
            'trip_route' => $booking->pickup_location . ' to ' . $booking->destination,
            'amount' => $booking->counter_price ?: $booking->offered_price ?: $booking->amount,
            'payment_method' => $method,
            'receipt_date' => now()->toDateString(),
            'booking_id' => $booking->id,
        ]);
    }
}

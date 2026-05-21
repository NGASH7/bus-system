<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\BookingController;

class MpesaController extends Controller
{
    public function callback(Request $request)
    {
        Log::info('M-Pesa Callback Received:', $request->all());
        
        $callbackData = $request->input('Body.stkCallback');
        
        if ($callbackData) {
            $checkoutId = $callbackData['CheckoutRequestID'] ?? null;
            $resultCode = $callbackData['ResultCode'] ?? null;
            $resultDesc = $callbackData['ResultDesc'] ?? null;
            
            Log::info("STK Push Result: $resultCode - $resultDesc for CheckoutID: $checkoutId");
            
            $booking = Booking::where('mpesa_checkout_request_id', $checkoutId)->first();
            
            if ($booking) {
                if ($resultCode == 0) {
                    $metadataItems = collect($callbackData['CallbackMetadata']['Item'] ?? []);
                    $receiptNo = $metadataItems->firstWhere('Name', 'MpesaReceiptNumber')['Value'] ?? null;
                    $phone = (string) ($metadataItems->firstWhere('Name', 'PhoneNumber')['Value'] ?? $booking->payer_phone);

                    $booking->update([
                        'payment_status' => 'paid',
                        'payment_reference' => $receiptNo,
                        'payer_phone' => $phone,
                        'paid_at' => now(),
                    ]);

                    // Use the existing logic to generate receipt
                    app(BookingController::class)->createReceiptIfMissing($booking, 'M-Pesa');
                    
                    // Send payment confirmation notifications
                    try {
                        app(\App\Services\BookingNotificationService::class)->sendPaymentConfirmedNotifications($booking);
                    } catch (\Exception $e) {
                        Log::error("Failed to send payment confirmation notifications for booking #{$booking->id}: " . $e->getMessage());
                    }
                    
                    Log::info("Booking #{$booking->id} marked as PAID.");
                } else {
                    $booking->update(['payment_status' => 'failed']);
                    Log::warning("Booking #{$booking->id} payment FAILED.");
                }
            } else {
                Log::error("M-Pesa Callback: No booking found for CheckoutID: $checkoutId");
            }
        }
        
        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Success']);
    }
}

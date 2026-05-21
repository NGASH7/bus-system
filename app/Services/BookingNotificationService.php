<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\User;
use App\Mail\PaymentReceived;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class BookingNotificationService
{
    protected $smsService;

    public function __construct(CelcomSmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Send all necessary notifications (User, Driver, Admin) when a booking is successfully paid.
     */
    public function sendPaymentConfirmedNotifications(Booking $booking)
    {
        // 1. Ensure essential relations are loaded
        $booking->load(['user', 'bus.driver']);

        $amountFormatted = number_format($booking->counter_price ?: $booking->offered_price ?: $booking->amount, 0);
        $routeStr = "{$booking->pickup_location} to {$booking->destination}";
        $dateStr = \Carbon\Carbon::parse($booking->date)->format('d M, Y');

        // 2. Notify the User (Payment Confirmed)
        if ($booking->user) {
            $userMsg = "Payment Confirmed! Your payment of KES {$amountFormatted} for booking #{$booking->id} to {$booking->destination} has been received. Thank you for choosing Mwigito Excel.";
            
            // Send SMS to User
            if ($booking->user->phone_number) {
                try {
                    $this->smsService->send($booking->user->phone_number, $userMsg);
                } catch (\Exception $e) {
                    Log::error("SMS notify user failed: " . $e->getMessage());
                }
            }

            // Send Email to User
            if ($booking->user->email) {
                try {
                    Mail::to($booking->user->email)->send(new PaymentReceived($booking));
                } catch (\Exception $e) {
                    Log::error("Email notify user failed: " . $e->getMessage());
                }
            }
        }

        // 3. Notify the Driver (Trip Assignment / Confirmation)
        if ($booking->bus && $booking->bus->driver) {
            $driver = $booking->bus->driver;
            $driverMsg = "New scheduled trip! You have been assigned a trip to {$booking->destination} on {$dateStr} ({$routeStr}). Check your dashboard for details.";

            // Send SMS to Driver
            if ($driver->phone_number) {
                try {
                    $this->smsService->send($driver->phone_number, $driverMsg);
                } catch (\Exception $e) {
                    Log::error("SMS notify driver failed: " . $e->getMessage());
                }
            }

            // Send Email to Driver
            if ($driver->email) {
                try {
                    Mail::raw($driverMsg, function ($mail) use ($driver) {
                        $mail->to($driver->email)->subject('Mwigito Excel: New Trip Assignment');
                    });
                } catch (\Exception $e) {
                    Log::error("Email notify driver failed: " . $e->getMessage());
                }
            }
        }

        // 4. Notify all Admins (Booking Paid Alert)
        try {
            $admins = User::where('role', 'admin')->get();
            $adminMsg = "Payment Confirmed! Booking #{$booking->id} (Route: {$routeStr}) for KES {$amountFormatted} has been paid successfully by {$booking->user->name} via {$booking->payment_method}.";
            
            foreach ($admins as $admin) {
                if ($admin->email) {
                    Mail::raw($adminMsg, function ($mail) use ($admin) {
                        $mail->to($admin->email)->subject('Mwigito Excel: Payment Received');
                    });
                }
            }
        } catch (\Exception $e) {
            Log::error("Email notify admins failed: " . $e->getMessage());
        }
    }
}

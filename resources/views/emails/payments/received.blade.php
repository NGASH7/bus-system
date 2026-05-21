<x-mail::message>
# Payment Received & Confirmed!

Hello {{ $booking->user->name }},

Thank you! We have successfully received and confirmed your payment for **Booking #{{ 1000 + $booking->id }}**. Your reservation is now fully secured.

**Trip Summary:**
- **From:** {{ $booking->pickup_location }}
- **To:** {{ $booking->destination }}
- **Date:** {{ $booking->date->format('l, M d, Y') }}
- **Amount Paid:** KES {{ number_format($booking->counter_price ?: $booking->offered_price ?: $booking->amount, 0) }}
- **Payment Method:** {{ $booking->payment_method }}
- **Payment Reference:** {{ $booking->payment_reference ?: 'N/A' }}

Your driver has been assigned and notified. You can download or view your receipt by clicking the button below.

<x-mail::button :url="$url">
View My Receipt
</x-mail::button>

We look forward to hosting you! If you have any further questions, please let us know.

Safe travels,<br>
{{ config('app.name') }}
</x-mail::message>

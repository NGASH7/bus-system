<x-mail::message>
# Payment Method Selected

Hello Admin,

A user has selected their payment method for **Booking #{{ 1000 + $booking->id }}**.

**Booking Details:**
- **Customer:** {{ $booking->user->name }}
- **Route:** {{ $booking->pickup_location }} to {{ $booking->destination }}
- **Date:** {{ $booking->date->format('l, M d, Y') }}
- **Amount:** KES {{ number_format($booking->counter_price ?: $booking->offered_price ?: $booking->amount, 0) }}
- **Selected Payment Method:** **{{ $booking->payment_method }}**
- **Payer Phone (if applicable):** {{ $booking->payer_phone ?: 'N/A' }}

Please coordinate with the user to verify, process, or confirm this payment.

<x-mail::button :url="$url">
Review Booking & Payment
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

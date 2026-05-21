<x-mail::message>
# Booking Confirmed!

Hello {{ $booking->user->name }},

Great news! Your booking request to **{{ $booking->destination }}** on **{{ $booking->date->format('M d, Y') }}** has been confirmed.

**Booking Details:**
- **From:** {{ $booking->pickup_location }}
- **To:** {{ $booking->destination }}
- **Date:** {{ $booking->date->format('l, M d, Y') }}
- **Amount:** KES {{ number_format($booking->counter_price ?: $booking->offered_price ?: $booking->amount, 0) }}

Please proceed to select your payment method and complete the payment to secure your reservation.

<x-mail::button :url="$url">
Select Payment Method
</x-mail::button>

If you have any questions, feel free to reply to this email.

Safe travels,<br>
{{ config('app.name') }}
</x-mail::message>

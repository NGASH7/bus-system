<x-mail::message>
# New Booking Request

A new booking request has been submitted by **{{ $booking->user->name }}**.

**Request Details:**
- **From:** {{ $booking->pickup_location }}
- **To:** {{ $booking->destination }}
- **Date:** {{ $booking->date->format('l, M d, Y') }}
- **Offered Price:** KES {{ number_format($booking->offered_price, 0) }}

Please review the request and provide a counter-offer or accept it.

<x-mail::button :url="$url">
Review Booking
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

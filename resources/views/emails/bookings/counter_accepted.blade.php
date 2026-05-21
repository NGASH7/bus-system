<x-mail::message>
# Counter-Offer Accepted!

Hello Admin,

Great news! **{{ $booking->user->name }}** has accepted the counter-offer for **Booking #{{ 1000 + $booking->id }}**.

**Booking Details:**
- **Route:** {{ $booking->pickup_location }} to {{ $booking->destination }}
- **Date:** {{ $booking->date->format('l, M d, Y') }}
- **Accepted Counter Price:** **KES {{ number_format($booking->counter_price, 0) }}**
- **Original Offer:** KES {{ number_format($booking->offered_price, 0) }}

The user has been prompted to complete their payment. Please monitor the booking and payment status.

<x-mail::button :url="$url">
View Booking Details
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

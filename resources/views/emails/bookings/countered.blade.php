<x-mail::message>
# New Counter-Offer

Hello {{ $booking->user->name }},

The fleet management team has reviewed your booking request to **{{ $booking->destination }}** and proposed a counter-offer.

**Counter-Offer Details:**
- **Proposed Price:** KES {{ number_format($booking->counter_price, 0) }}
- **Original Offer:** KES {{ number_format($booking->offered_price, 0) }}

You can accept this counter-offer on your dashboard to lock in your reservation.

<x-mail::button :url="$url">
Review Counter-Offer
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Bus;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index(Request $request)
    {
        $availability = null;

        if ($request->filled(['rental_date', 'rental_dest'])) {
            $request->validate([
                'rental_date' => ['required', 'date', 'after_or_equal:today'],
                'rental_dest' => ['required', 'string', 'max:255'],
                'rental_time' => ['nullable', 'date_format:H:i'],
                'rental_capacity' => ['nullable', 'integer', 'min:1', 'max:200'],
            ]);

            $destination = trim((string) $request->string('rental_dest'));
            $requestedDate = (string) $request->input('rental_date');
            $requestedTime = (string) ($request->input('rental_time') ?: '08:00');
            $requestedCapacity = $request->integer('rental_capacity');

            $requestedStart = Carbon::parse($requestedDate . ' ' . $requestedTime);
            $requestedEnd = $requestedStart->copy()->addHours(4);

            $activeBuses = Bus::where('is_active', true)->get();
            $activeBookings = Booking::whereIn('status', ['pending', 'countered', 'accepted'])
                ->whereNotNull('bus_id')
                ->whereIn('bus_id', $activeBuses->pluck('id'))
                ->get();

            $busyBusIds = [];
            $returnTimes = [];

            foreach ($activeBookings as $booking) {
                $bookingDate = $booking->date instanceof Carbon
                    ? $booking->date->toDateString()
                    : Carbon::parse($booking->date)->toDateString();

                $bookingStartTime = $booking->pickup_time ?: '08:00';
                $bookingStart = Carbon::parse($bookingDate . ' ' . $bookingStartTime);

                if ($booking->return_date) {
                    $bookingEndTime = $booking->return_time ?: '18:00';
                    $bookingEnd = Carbon::parse($booking->return_date->toDateString() . ' ' . $bookingEndTime);
                } else {
                    $bookingEnd = $bookingStart->copy()->addHours(4);
                }

                $isOverlap = $bookingStart->lt($requestedEnd) && $bookingEnd->gt($requestedStart);

                if ($isOverlap) {
                    $busyBusIds[] = (int) $booking->bus_id;
                    $returnTimes[] = $bookingEnd;
                }
            }

            $availableBuses = $activeBuses
                ->filter(fn ($bus) => !in_array((int) $bus->id, $busyBusIds, true))
                ->values();
            $nextReturn = empty($returnTimes) ? null : collect($returnTimes)->sort()->first();

            $availability = [
                'destination' => $destination,
                'date' => $requestedStart->format('D, d M Y'),
                'time' => $requestedStart->format('h:i A'),
                'is_available' => $availableBuses->isNotEmpty(),
                'buses' => $availableBuses,
                'next_return' => $nextReturn?->format('D, d M Y \\a\\t h:i A'),
                'requested_capacity' => $requestedCapacity > 0 ? $requestedCapacity : null,
            ];

            if (($availability['requested_capacity'] ?? null) !== null) {
                $minCapacity = $availability['requested_capacity'];
                $availability['buses'] = $availableBuses
                    ->filter(fn ($bus) => (int) ($bus->capacity ?? 0) >= $minCapacity)
                    ->values();
                $availability['is_available'] = $availability['buses']->isNotEmpty();
            }
        }

        return view('index', compact('availability'));
    }
}


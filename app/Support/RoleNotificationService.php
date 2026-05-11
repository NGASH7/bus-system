<?php

namespace App\Support;

use App\Models\Booking;
use App\Models\Bus;
use App\Models\BusService;
use App\Models\Receipt;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class RoleNotificationService
{
    public static function forUser(User $user, int $limit = 8): array
    {
        if ($user->isAdmin()) {
            return self::forAdmin($limit);
        }

        if ($user->isDriver()) {
            return self::forDriver($user, $limit);
        }

        return self::forRenter($user, $limit);
    }

    private static function forRenter(User $user, int $limit): array
    {
        $items = collect();

        $bookings = Booking::query()
            ->where('user_id', $user->id)
            ->latest()
            ->take(12)
            ->get();

        foreach ($bookings as $booking) {
            if (in_array($booking->status, ['accepted', 'completed'], true)) {
                $items->push(self::makeItem(
                    'Booking approved',
                    'Your trip to ' . $booking->destination . ' has been approved.',
                    'fas fa-check-circle',
                    'success',
                    $booking->updated_at ?? $booking->created_at,
                    'booking',
                    route('bookings.index')
                ));
            }

            if ($booking->payment_status === 'pending') {
                $items->push(self::makeItem(
                    'Payment pending',
                    'Payment is pending for your booking to ' . $booking->destination . '.',
                    'fas fa-hourglass-half',
                    'warning',
                    $booking->updated_at ?? $booking->created_at,
                    'billing',
                    route('receipts.index')
                ));
            }

            if (in_array($booking->status, ['rejected', 'cancelled'], true) || $booking->payment_status === 'cancelled') {
                $items->push(self::makeItem(
                    'Payment or booking cancelled',
                    'A booking/payment related to ' . $booking->destination . ' was cancelled.',
                    'fas fa-times-circle',
                    'danger',
                    $booking->updated_at ?? $booking->created_at,
                    'booking',
                    route('bookings.index')
                ));
            }
        }

        return self::finalize($items, $limit);
    }

    private static function forDriver(User $driver, int $limit): array
    {
        $items = collect();
        $today = now()->startOfDay();

        if ($driver->license_expiry) {
            $daysToExpiry = $today->diffInDays($driver->license_expiry, false);
            if ($daysToExpiry < 0) {
                $items->push(self::makeItem(
                    'License expired',
                    'Your driver license expired ' . abs($daysToExpiry) . ' day(s) ago.',
                    'fas fa-id-card',
                    'danger',
                    $driver->updated_at ?? $driver->created_at,
                    'driver',
                    route('driver.license')
                ));
            } elseif ($daysToExpiry <= 30) {
                $items->push(self::makeItem(
                    'License expiring soon',
                    'Your driver license expires in ' . $daysToExpiry . ' day(s).',
                    'fas fa-id-card',
                    'warning',
                    $driver->updated_at ?? $driver->created_at,
                    'driver',
                    route('driver.license')
                ));
            }
        }

        $assignedBusId = optional($driver->bus)->id;
        if ($assignedBusId) {
            $nextTrip = Booking::query()
                ->where('bus_id', $assignedBusId)
                ->whereIn('status', ['accepted', 'pending', 'countered'])
                ->whereDate('date', '>=', $today)
                ->orderBy('date')
                ->first();

            if ($nextTrip) {
                $daysToTrip = $today->diffInDays($nextTrip->date, false);
                if ($daysToTrip <= 1) {
                    $items->push(self::makeItem(
                        'Next trip soon',
                        'Your next trip to ' . $nextTrip->destination . ' is ' . ($daysToTrip === 0 ? 'today' : 'in 1 day') . '.',
                        'fas fa-bus',
                        'info',
                        $nextTrip->created_at,
                        'driver',
                        route('driver.schedule')
                    ));
                }
            }

            $newTrips = Booking::query()
                ->where('bus_id', $assignedBusId)
                ->whereIn('status', ['pending', 'accepted'])
                ->where('created_at', '>=', now()->subDays(7))
                ->latest()
                ->take(4)
                ->get();

            foreach ($newTrips as $trip) {
                $items->push(self::makeItem(
                    'New trip booked',
                    'A new trip for your assigned bus was booked: ' . $trip->destination . '.',
                    'fas fa-calendar-plus',
                    'success',
                    $trip->created_at,
                    'driver',
                    route('driver.schedule')
                ));
            }
        } else {
            $items->push(self::makeItem(
                'No bus assignment',
                'You currently do not have a bus assigned. Contact admin.',
                'fas fa-info-circle',
                'info',
                now(),
                'driver',
                route('driver.dashboard')
            ));
        }

        return self::finalize($items, $limit);
    }

    private static function forAdmin(int $limit): array
    {
        $items = collect();
        $today = now()->startOfDay();

        $expiredInsurance = Bus::query()
            ->whereDate('insurance_expiry', '<', $today)
            ->orderByDesc('insurance_expiry')
            ->take(4)
            ->get();

        foreach ($expiredInsurance as $bus) {
            $items->push(self::makeItem(
                'Bus insurance expired',
                'Insurance for bus ' . $bus->plate_number . ' expired on ' . $bus->insurance_expiry?->format('d M Y') . '.',
                'fas fa-shield-alt',
                'danger',
                $bus->updated_at ?? $bus->created_at,
                'insurance',
                route('admin.insurance.index')
            ));
        }

        $recentReceipts = Receipt::query()->latest()->take(4)->get();
        foreach ($recentReceipts as $receipt) {
            $items->push(self::makeItem(
                'Payment confirmed',
                'Payment of KES ' . number_format((float) $receipt->amount, 2) . ' recorded for ' . $receipt->customer_name . '.',
                'fas fa-money-check-dollar',
                'success',
                $receipt->created_at,
                'billing',
                route('admin.receipts.index')
            ));
        }

        $newBookings = Booking::query()
            ->whereIn('status', ['pending', 'countered'])
            ->latest()
            ->take(4)
            ->get();

        foreach ($newBookings as $booking) {
            $requester = optional($booking->user)->name ?? 'customer';
            $items->push(self::makeItem(
                'New booking',
                'New booking request from ' . $requester . ' to ' . $booking->destination . '.',
                'fas fa-ticket',
                'info',
                $booking->created_at,
                'booking',
                route('admin.bookings.index')
            ));
        }

        $serviceNeeded = BusService::query()
            ->where('status', 'pending')
            ->latest()
            ->take(4)
            ->get();

        foreach ($serviceNeeded as $service) {
            $items->push(self::makeItem(
                'Bus service required',
                'Service request for ' . optional($service->bus)->plate_number . ': ' . $service->description . '.',
                'fas fa-screwdriver-wrench',
                'warning',
                $service->created_at,
                'service',
                route('admin.billing.index')
            ));
        }

        return self::finalize($items, $limit);
    }

    private static function makeItem(
        string $title,
        string $message,
        string $icon,
        string $level,
        Carbon $time,
        string $type = 'system',
        ?string $link = null
    ): array
    {
        return [
            'title' => $title,
            'message' => $message,
            'icon' => $icon,
            'level' => $level,
            'time' => $time,
            'type' => $type,
            'link' => $link,
        ];
    }

    private static function finalize(Collection $items, int $limit): array
    {
        $sorted = $items
            ->sortByDesc('time')
            ->take($limit)
            ->values()
            ->map(function (array $item) {
                $item['time_text'] = $item['time']->diffForHumans();
                return $item;
            });

        return [
            'count' => $sorted->count(),
            'items' => $sorted,
        ];
    }
}


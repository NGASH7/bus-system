<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class AdminScheduleController extends Controller
{
    public function index()
    {
        // Only show accepted/confirmed bookings on the schedule
        $bookings = Booking::with(['user', 'bus'])
            ->where('status', 'accepted')
            ->where('date', '>=', now()->startOfDay())
            ->orderBy('date')
            ->orderBy('pickup_time')
            ->get()
            ->groupBy(function ($item) {
                return $item->date->format('Y-m-d');
            });

        return view('admin.schedule.index', compact('bookings'));
    }
}

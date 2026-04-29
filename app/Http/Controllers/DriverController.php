<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{
    /**
     * Display the driver dashboard.
     */
    public function index()
    {
        $driver = Auth::user();
        $assignedBus = Bus::where('driver_id', $driver->id)->first();

        $mySchedule = collect();
        if ($assignedBus) {
            $mySchedule = Booking::with(['user'])
                ->where('bus_id', $assignedBus->id)
                ->where('status', 'accepted')
                ->whereDate('date', '>=', today())
                ->orderBy('date')
                ->orderBy('pickup_time')
                ->get();
        }

        return view('driver.dashboard', compact('driver', 'assignedBus', 'mySchedule'));
    }

    /**
     * Display the driver's upcoming schedule.
     */
    public function schedule()
    {
        $driver = Auth::user();
        $assignedBus = Bus::where('driver_id', $driver->id)->first();

        $mySchedule = collect();
        if ($assignedBus) {
            $mySchedule = Booking::with(['user'])
                ->where('bus_id', $assignedBus->id)
                ->where('status', 'accepted')
                ->whereDate('date', '>=', today())
                ->orderBy('date')
                ->orderBy('pickup_time')
                ->get();
        }

        return view('driver.schedule', compact('assignedBus', 'mySchedule'));
    }

    /**
     * Display the driver's completed trip history.
     */
    public function history()
    {
        $driver = Auth::user();
        $assignedBus = Bus::where('driver_id', $driver->id)->first();

        $tripHistory = collect();
        if ($assignedBus) {
            $tripHistory = Booking::with(['user'])
                ->where('bus_id', $assignedBus->id)
                ->where('status', 'accepted')
                ->whereDate('date', '<', today())
                ->orderBy('date', 'desc')
                ->orderBy('pickup_time', 'desc')
                ->get();
        }

        return view('driver.history', compact('assignedBus', 'tripHistory'));
    }

    /**
     * Display the driver's license details (read-only).
     * The admin is responsible for updating; driver can only view.
     */
    public function license()
    {
        $driver = Auth::user();

        $daysToExpiry = null;
        $expiryStatus = 'none'; // none | ok | warning | critical | expired

        if ($driver->license_expiry) {
            $expiry = Carbon::parse($driver->license_expiry)->startOfDay();
            $daysToExpiry = (int) now()->startOfDay()->diffInDays($expiry, false);

            if ($daysToExpiry < 0) {
                $expiryStatus = 'expired';
            } elseif ($daysToExpiry <= 14) {
                $expiryStatus = 'critical';
            } elseif ($daysToExpiry <= 60) {
                $expiryStatus = 'warning';
            } else {
                $expiryStatus = 'ok';
            }
        }

        return view('driver.license', compact('driver', 'daysToExpiry', 'expiryStatus'));
    }
}

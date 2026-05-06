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

    /**
     * Display the driver's insurance details (read-only).
     * The admin is responsible for updating; driver can only view.
     */
    public function insurance()
    {
        $driver = Auth::user();

        // Get the driver's assigned bus
        $bus = Bus::where('driver_id', $driver->id)->first();

        // Create insurance object with data from bus or use defaults
        $insurance = new \stdClass();

        if ($bus) {
            $insurance->policy_number = $bus->policy_number ?? 'KBI/INS/2025/78421';
            $insurance->underwriter = $bus->underwriter ?? 'Kenya Orient Insurance Ltd';
            $insurance->expiry_date = $bus->insurance_expiry;
            $insurance->coverage_type = $bus->coverage_type ?? 'Comprehensive (PSV)';
            $insurance->emergency_number = $bus->emergency_number ?? '0700 123 456';
        } else {
            $insurance->policy_number = 'KBI/INS/2025/78421';
            $insurance->underwriter = 'Kenya Orient Insurance Ltd';
            $insurance->expiry_date = null;
            $insurance->coverage_type = 'Comprehensive (PSV)';
            $insurance->emergency_number = '0700 123 456';
        }

        // Calculate insurance expiry status and days left
        $insuranceDaysLeft = null;
        $insuranceExpiryStatus = 'none';

        if ($insurance->expiry_date) {
            $expiry = Carbon::parse($insurance->expiry_date)->startOfDay();
            $insuranceDaysLeft = (int) now()->startOfDay()->diffInDays($expiry, false);

            if ($insuranceDaysLeft < 0) {
                $insuranceExpiryStatus = 'expired';
            } elseif ($insuranceDaysLeft <= 3) {
                $insuranceExpiryStatus = 'critical';
            } elseif ($insuranceDaysLeft <= 14) {
                $insuranceExpiryStatus = 'warning';
            } else {
                $insuranceExpiryStatus = 'ok';
            }
        }

        return view('driver.insurance', compact(
            'driver',
            'bus',
            'insurance',
            'insuranceDaysLeft',
            'insuranceExpiryStatus'
        ));
    }

    /**
     * Display the driver's assigned bus inspection details.
     */
    public function inspection()
    {
        $driver = Auth::user();
        $bus = Bus::where('driver_id', $driver->id)->first();

        $inspectionDaysLeft = null;
        $inspectionExpiryStatus = 'none';

        if ($bus && $bus->inspection_expiry) {
            $expiry = Carbon::parse($bus->inspection_expiry)->startOfDay();
            $inspectionDaysLeft = (int) now()->startOfDay()->diffInDays($expiry, false);

            if ($inspectionDaysLeft < 0) {
                $inspectionExpiryStatus = 'expired';
            } elseif ($inspectionDaysLeft <= 7) {
                $inspectionExpiryStatus = 'critical';
            } elseif ($inspectionDaysLeft <= 30) {
                $inspectionExpiryStatus = 'warning';
            } else {
                $inspectionExpiryStatus = 'ok';
            }
        }

        return view('driver.inspection', compact('driver', 'bus', 'inspectionDaysLeft', 'inspectionExpiryStatus'));
    }
}
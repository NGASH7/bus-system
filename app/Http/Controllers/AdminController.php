<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\User;
use App\Models\Receipt;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $stats = [
            'buses' => Bus::count(),
            'drivers' => User::where('role', 'driver')->count(),
            'pending_billing' => \App\Models\BusService::where('status', 'pending')->count(),
            'total_revenue' => Receipt::whereNull('booking_id')->sum('amount') + \App\Models\Booking::whereIn('status', ['accepted', 'completed'])->sum('amount'),
        ];

        // Fetch various activities
        $maintenance = \App\Models\BusService::with(['bus', 'driver'])->latest()->take(5)->get()->map(function($item) {
            return [
                'type' => 'maintenance',
                'title' => ($item->driver->name ?? 'Driver') . ' — ' . $item->bus->plate_number,
                'msg' => 'Requested payment for: ' . $item->description . ' (KES ' . number_format($item->cost) . ')',
                'time' => $item->created_at,
                'icon' => 'fas fa-tools',
                'accent' => 'service-accent'
            ];
        });

        $receipts = Receipt::latest()->take(5)->get()->map(function($item) {
            return [
                'type' => 'revenue',
                'title' => 'Revenue Recorded',
                'msg' => 'Income of KES ' . number_format($item->amount) . ' received on ' . $item->receipt_date->format('d M'),
                'time' => $item->created_at,
                'icon' => 'fas fa-hand-holding-usd',
                'accent' => 'billing-accent'
            ];
        });

        $drivers = User::where('role', 'driver')->latest()->take(3)->get()->map(function($item) {
            return [
                'type' => 'driver',
                'title' => 'New Driver Joined',
                'msg' => 'Driver ' . $item->name . ' has been registered in the system.',
                'time' => $item->created_at,
                'icon' => 'fas fa-user-plus',
                'accent' => 'driver-accent'
            ];
        });

        // Merge and sort for dashboard (last 5)
        $activities = collect()
            ->concat($maintenance)
            ->concat($receipts)
            ->concat($drivers)
            ->sortByDesc('time')
            ->take(5);

        // Fetch Alerts (Expiries)
        $today = now();
        $warningDate = now()->addDays(30);

        $insuranceAlerts = Bus::where('insurance_expiry', '<=', $warningDate)
            ->get()
            ->map(function($bus) use ($today) {
                $status = $bus->insurance_expiry < $today ? 'Expired' : 'Expiring Soon';
                return [
                    'type' => 'Insurance',
                    'item' => $bus->plate_number,
                    'expiry' => $bus->insurance_expiry,
                    'status' => $status,
                    'days' => $today->diffInDays($bus->insurance_expiry, false),
                    'icon' => 'fas fa-shield-alt'
                ];
            });

        $busLicenseAlerts = Bus::where('license_expiry', '<=', $warningDate)
            ->get()
            ->map(function($bus) use ($today) {
                $status = $bus->license_expiry < $today ? 'Expired' : 'Expiring Soon';
                return [
                    'type' => 'Inspection',
                    'item' => $bus->plate_number,
                    'expiry' => $bus->license_expiry,
                    'status' => $status,
                    'days' => $today->diffInDays($bus->license_expiry, false),
                    'icon' => 'fas fa-clipboard-check'
                ];
            });

        $driverLicenseAlerts = User::where('role', 'driver')
            ->where('license_expiry', '<=', $warningDate)
            ->get()
            ->map(function($driver) use ($today) {
                $status = $driver->license_expiry < $today ? 'Expired' : 'Expiring Soon';
                return [
                    'type' => 'Driver License',
                    'item' => $driver->name,
                    'expiry' => $driver->license_expiry,
                    'status' => $status,
                    'days' => $today->diffInDays($driver->license_expiry, false),
                    'icon' => 'fas fa-id-card'
                ];
            });

        $alerts = collect()
            ->concat($insuranceAlerts)
            ->concat($busLicenseAlerts)
            ->concat($driverLicenseAlerts)
            ->map(function($alert) use ($today) {
                $expiry = $alert['expiry'];
                $diff = $today->diff($expiry);
                
                if ($expiry < $today) {
                    $text = $diff->days . 'd ' . $diff->h . 'h ago';
                } else {
                    $text = 'in ' . $diff->days . 'd ' . $diff->h . 'h';
                }
                
                $alert['countdown_text'] = $text;
                $alert['sort_days'] = $today->diffInDays($expiry, false);
                return $alert;
            })
            ->sortBy('sort_days');

        return view('admin.dashboard', compact('stats', 'activities', 'alerts'));
    }

    /**
     * Display a full listing of system activities.
     */
    public function logs()
    {
        // Fetch larger datasets for the full log page
        $maintenance = \App\Models\BusService::with(['bus', 'driver'])->latest()->take(50)->get()->map(function($item) {
            return [
                'type' => 'maintenance',
                'title' => ($item->driver->name ?? 'Driver') . ' — ' . $item->bus->plate_number,
                'msg' => 'Requested payment for: ' . $item->description . ' (KES ' . number_format($item->cost) . ')',
                'time' => $item->created_at,
                'icon' => 'fas fa-tools',
                'accent' => 'service-accent'
            ];
        });

        $receipts = Receipt::latest()->take(50)->get()->map(function($item) {
            return [
                'type' => 'revenue',
                'title' => 'Revenue Recorded',
                'msg' => 'Income of KES ' . number_format($item->amount) . ' received on ' . $item->receipt_date->format('d M'),
                'time' => $item->created_at,
                'icon' => 'fas fa-hand-holding-usd',
                'accent' => 'billing-accent'
            ];
        });

        $drivers = User::where('role', 'driver')->latest()->take(20)->get()->map(function($item) {
            return [
                'type' => 'driver',
                'title' => 'New Driver Joined',
                'msg' => 'Driver ' . $item->name . ' has been registered in the system.',
                'time' => $item->created_at,
                'icon' => 'fas fa-user-plus',
                'accent' => 'driver-accent'
            ];
        });

        $activities = collect()
            ->concat($maintenance)
            ->concat($receipts)
            ->concat($drivers)
            ->sortByDesc('time');

        return view('admin.logs.index', compact('activities'));
    }
}

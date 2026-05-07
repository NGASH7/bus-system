<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\SystemLog;
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

        // Dashboard shows only the latest 5 system log entries.
        $activities = SystemLog::query()
            ->with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn (SystemLog $log) => $this->mapSystemLog($log));

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

        $roadLicenseAlerts = Bus::where('license_expiry', '<=', $warningDate)
            ->get()
            ->map(function($bus) use ($today) {
                $status = $bus->license_expiry < $today ? 'Expired' : 'Expiring Soon';
                return [
                    'type' => 'Road License',
                    'item' => $bus->plate_number,
                    'expiry' => $bus->license_expiry,
                    'status' => $status,
                    'days' => $today->diffInDays($bus->license_expiry, false),
                    'icon' => 'fas fa-id-card'
                ];
            });

        $inspectionAlerts = Bus::where('inspection_expiry', '<=', $warningDate)
            ->get()
            ->map(function($bus) use ($today) {
                $status = $bus->inspection_expiry < $today ? 'Expired' : 'Expiring Soon';
                return [
                    'type' => 'Inspection',
                    'item' => $bus->plate_number,
                    'expiry' => $bus->inspection_expiry,
                    'status' => $status,
                    'days' => $today->diffInDays($bus->inspection_expiry, false),
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
                    'icon' => 'fas fa-id-card-clip'
                ];
            });

        $alerts = collect()
            ->concat($insuranceAlerts)
            ->concat($roadLicenseAlerts)
            ->concat($inspectionAlerts)
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
                
                // Set critical status if expiring within 7 days
                if ($alert['sort_days'] >= 0 && $alert['sort_days'] <= 7) {
                    $alert['status'] = 'Critical';
                }
                
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
        $activities = SystemLog::query()
            ->with('user')
            ->latest()
            ->get()
            ->map(fn (SystemLog $log) => $this->mapSystemLog($log));

        return view('admin.logs.index', compact('activities'));
    }

    private function mapSystemLog(SystemLog $log): array
    {
        $action = strtolower($log->action);
        $accent = 'system-accent';
        $icon = 'fas fa-clipboard-list';

        if (str_contains($action, 'login') || str_contains($action, 'auth')) {
            $accent = 'driver-accent';
            $icon = 'fas fa-right-to-bracket';
        } elseif (str_contains($action, 'booking')) {
            $accent = 'service-accent';
            $icon = 'fas fa-calendar-check';
        } elseif (str_contains($action, 'payment') || str_contains($action, 'receipt')) {
            $accent = 'billing-accent';
            $icon = 'fas fa-money-bill-wave';
        }

        return [
            'type' => 'system',
            'title' => $log->user?->name ? $log->user->name . ' (' . $log->action . ')' : 'System (' . $log->action . ')',
            'msg' => e($log->description),
            'time' => $log->created_at,
            'icon' => $icon,
            'accent' => $accent,
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\User;
use App\Models\Booking;
use App\Models\BusService;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'monthly');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');

        $overviewStartDate = match ($filter) {
            'daily' => Carbon::now()->subDays(30),
            'weekly' => Carbon::now()->subWeeks(12),
            default => Carbon::now()->subMonths(6),
        };

        if ($fromDate && $toDate) {
            $filterStartDate = Carbon::parse($fromDate)->startOfDay();
            $filterEndDate = Carbon::parse($toDate)->endOfDay();
        } else {
            $filterStartDate = $overviewStartDate;
            $filterEndDate = Carbon::now();
        }

        $fleetStats = [
            'total' => Bus::count(),
            'active' => Bus::where('is_active', true)->count(),
            'maintenance' => Bus::where('is_active', false)->count(),
        ];

        $bookingData = Booking::whereBetween('created_at', [$filterStartDate, $filterEndDate])
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $revenue = Receipt::whereBetween('receipt_date', [$filterStartDate, $filterEndDate])->sum('amount');
        $expenses = BusService::where('status', 'paid')
            ->whereBetween('updated_at', [$filterStartDate, $filterEndDate])
            ->sum('cost');

        $financials = [
            'revenue' => $revenue,
            'expenses' => $expenses,
            'profit' => $revenue - $expenses,
        ];

        // TREND DATA (Dual Lines & Profit Bar)
        $trendRangeStart = ($fromDate && $toDate) ? $filterStartDate : $overviewStartDate;
        $trendRangeEnd = ($fromDate && $toDate) ? $filterEndDate : Carbon::now();

        $revenueQuery = Receipt::whereBetween('receipt_date', [$trendRangeStart, $trendRangeEnd]);
        $expenseQuery = BusService::where('status', 'paid')->whereBetween('updated_at', [$trendRangeStart, $trendRangeEnd]);

        $dateFormats = [
            'daily' => ['label' => "DATE_FORMAT(receipt_date, '%d %b')", 'sort' => 'receipt_date', 'label_exp' => "DATE_FORMAT(updated_at, '%d %b')", 'sort_exp' => 'updated_at'],
            'weekly' => ['label' => "DATE_FORMAT(receipt_date, 'Week %u')", 'sort' => "DATE_FORMAT(receipt_date, '%Y-%u')", 'label_exp' => "DATE_FORMAT(updated_at, 'Week %u')", 'sort_exp' => "DATE_FORMAT(updated_at, '%Y-%u')"],
            'monthly' => ['label' => "DATE_FORMAT(receipt_date, '%M')", 'sort' => "DATE_FORMAT(receipt_date, '%Y-%m')", 'label_exp' => "DATE_FORMAT(updated_at, '%M')", 'sort_exp' => "DATE_FORMAT(updated_at, '%Y-%m')"],
        ];

        $fmt = $dateFormats[$filter];
        if ($fromDate && $toDate && Carbon::parse($fromDate)->diffInDays(Carbon::parse($toDate)) <= 31) {
            $fmt = $dateFormats['daily'];
        }

        $revenueTrend = $revenueQuery->select(DB::raw("sum(amount) as total"), DB::raw($fmt['label'] . " as label"), DB::raw($fmt['sort'] . " as sort_date"))
            ->groupBy('sort_date', 'label')->orderBy('sort_date', 'asc')->get()->pluck('total', 'label')->toArray();

        $expenseTrend = $expenseQuery->select(DB::raw("sum(cost) as total"), DB::raw($fmt['label_exp'] . " as label"), DB::raw($fmt['sort_exp'] . " as sort_date"))
            ->groupBy('sort_date', 'label')->orderBy('sort_date', 'asc')->get()->pluck('total', 'label')->toArray();

        $allLabels = array_unique(array_merge(array_keys($revenueTrend), array_keys($expenseTrend)));

        $finalTrend = [];
        foreach ($allLabels as $label) {
            $rev = $revenueTrend[$label] ?? 0;
            $exp = $expenseTrend[$label] ?? 0;
            $finalTrend[] = [
                'label' => $label,
                'revenue' => $rev,
                'expenses' => $exp,
                'profit' => $rev - $exp,
            ];
        }

        $driverCount = User::where('role', 'driver')->count();
        $userCount = User::where('role', 'user')->count();

        return view('admin.analytics.index', compact(
            'fleetStats',
            'bookingData',
            'financials',
            'finalTrend',
            'driverCount',
            'userCount',
            'filter',
            'fromDate',
            'toDate',
            'filterStartDate',
            'filterEndDate'
        ));
    }
}

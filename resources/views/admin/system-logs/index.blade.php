<x-admin-layout>
    <div class="admin-dashboard-wrapper fade-up">
        <div class="max-w-[1440px] mx-auto">
            <div class="welcome-header-minimal mb-8">
                <h1 class="page-title"><i class="fas fa-history mr-2"></i>System Logs</h1>
                <p class="text-sm text-gray-500 mt-2">All tracked actions across authentication, bookings, and receipts.</p>
            </div>

            <div class="activity-container">
                @forelse($systemLogs as $log)
                    @php
                        $icon = 'fa-circle-info';
                        $accent = 'service-accent';
                        if (\Illuminate\Support\Str::startsWith($log->action, 'auth.')) {
                            $icon = 'fa-user-shield';
                            $accent = 'driver-accent';
                        } elseif (\Illuminate\Support\Str::startsWith($log->action, 'booking.')) {
                            $icon = 'fa-calendar-check';
                            $accent = 'odometer-accent';
                        } elseif (\Illuminate\Support\Str::startsWith($log->action, 'receipt.')) {
                            $icon = 'fa-receipt';
                            $accent = 'billing-accent';
                        }
                    @endphp

                    <div class="activity-row {{ $loop->last ? 'last-row' : '' }}">
                        <div class="activity-marker {{ $accent }}">
                            <i class="fas {{ $icon }}"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-top">
                                <span class="activity-user">{{ $log->user?->name ?? 'System' }} <small class="text-gray-400">({{ $log->action }})</small></span>
                                <span class="activity-time">{{ $log->created_at->format('d M Y, h:i:s A') }}</span>
                            </div>
                            <p class="activity-msg">{{ $log->description }}</p>
                        </div>
                    </div>
                @empty
                    <div class="activity-row last-row">
                        <div class="activity-marker service-accent">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-top">
                                <span class="activity-user">System</span>
                                <span class="activity-time">Now</span>
                            </div>
                            <p class="activity-msg">No logs found yet.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $systemLogs->links() }}
            </div>
        </div>
    </div>

    <style>
        .activity-container {
            background: white;
            border-radius: 20px;
            border: 1px solid #f1f5f9;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        }
        .activity-row {
            padding: 20px 30px;
            display: flex;
            gap: 20px;
            align-items: center;
            border-bottom: 1px solid #f8fafc;
            transition: background 0.2s;
        }
        .activity-row:hover { background: #fcfcfc; }
        .activity-row.last-row { border-bottom: none; }
        .activity-marker {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        .odometer-accent { background: rgba(128, 0, 0, 0.05); color: #800000; }
        .billing-accent { background: rgba(201, 168, 76, 0.05); color: #c9a84c; }
        .driver-accent { background: rgba(31, 41, 55, 0.05); color: #1a202c; }
        .service-accent { background: rgba(22, 163, 74, 0.05); color: #16a34a; }
        .activity-content { flex: 1; }
        .activity-top {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
            gap: 8px;
        }
        .activity-user { font-size: 14px; font-weight: 800; color: #1a202c; }
        .activity-time { font-size: 12px; color: #718096; font-weight: 600; }
        .activity-msg { font-size: 14px; color: #4b5563; margin: 0; line-height: 1.4; }
    </style>
</x-admin-layout>


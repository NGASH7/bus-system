<x-admin-layout>
    <div class="admin-dashboard-wrapper fade-up">
        <div class="max-w-[1440px] mx-auto">
            
            <!-- MINIMAL HEADER -->
            <div class="welcome-header-minimal mb-8">
                <h1 class="page-title">Admin Dashboard</h1>
            </div>

            <div class="stats-row">
                <!-- Fleet Card -->
                <div class="admin-stat-card">
                    <div class="sc-icon-circle"><i class="fas fa-bus"></i></div>
                    <div class="sc-number">{{ $stats['buses'] }}</div>
                    <div class="sc-label">ACTIVE FLEET</div>
                </div>

                <!-- Drivers Card -->
                <div class="admin-stat-card">
                    <div class="sc-icon-circle"><i class="fas fa-id-card"></i></div>
                    <div class="sc-number">{{ $stats['drivers'] }}</div>
                    <div class="sc-label">TOTAL DRIVERS</div>
                </div>

                <!-- Billing Card -->
                <div class="admin-stat-card">
                    <div class="sc-icon-circle {{ $stats['pending_billing'] > 0 ? 'danger-tint' : '' }}"><i class="fas fa-file-invoice-dollar"></i></div>
                    <div class="sc-number {{ $stats['pending_billing'] > 0 ? 'danger-text' : '' }}">{{ $stats['pending_billing'] }}</div>
                    <div class="sc-label">PENDING BILLING</div>
                </div>

                <!-- Revenue Card -->
                <div class="admin-stat-card">
                    <div class="sc-icon-circle"><i class="fas fa-hand-holding-usd"></i></div>
                    <div class="sc-number" style="font-size: 24px;">KES {{ number_format($stats['total_revenue'] / 1000, 1) }}k</div>
                    <div class="sc-label">TOTAL REVENUE</div>
                </div>
            </div>

            <!-- CRITICAL ALERTS SECTION -->
            @if($alerts->count() > 0)
            <div class="alerts-section fade-up" style="animation-delay: 0.1s; margin-top: 40px;">
                <div class="section-header-compact mb-6">
                    <h3 class="section-title-premium text-maroon"><i class="fas fa-exclamation-triangle mr-2"></i> Critical Expiry Alerts</h3>
                </div>
                
                <div class="alerts-grid">
                    @foreach($alerts as $alert)
                        <div class="alert-card {{ $alert['status'] == 'Expired' ? 'alert-expired' : ($alert['status'] == 'Critical' ? 'alert-critical' : 'alert-warning') }}">
                            <div class="alert-icon">
                                <i class="{{ $alert['icon'] }}"></i>
                            </div>
                            <div class="alert-details">
                                <div class="alert-header">
                                    <span class="alert-type">{{ $alert['type'] }}</span>
                                    <span class="alert-badge">{{ $alert['status'] }}</span>
                                </div>
                                <div class="alert-item">{{ $alert['item'] }}</div>
                                <div class="alert-date">
                                    Expires: {{ $alert['expiry']->format('d M, Y') }} 
                                    <span class="alert-countdown">({{ $alert['countdown_text'] }})</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- SYSTEM ACTIVITY LOG -->
            <div class="activity-section fade-up" style="animation-delay: 0.2s;">
                <div class="section-header-compact mb-6">
                    <h3 class="section-title-premium section-title-link" onclick="window.location='{{ route('admin.logs') }}'">
                        <i class="fas fa-history mr-2"></i> System Activity Log
                    </h3>
                    <a href="{{ route('admin.logs') }}" class="view-all-link">View Everything</a>
                </div>

                <div class="activity-container activity-container-link" onclick="window.location='{{ route('admin.logs') }}'">
                    @forelse($activities as $activity)
                        <div class="activity-row {{ $loop->last ? 'last-row' : '' }}">
                            <div class="activity-marker {{ $activity['accent'] }}">
                                <i class="{{ $activity['icon'] }}"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-top">
                                    <span class="activity-user">{{ $activity['title'] }}</span>
                                    <span class="activity-time">{{ $activity['time']->diffForHumans() }}</span>
                                </div>
                                <p class="activity-msg">{!! $activity['msg'] !!}</p>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state py-12 text-center">
                            <i class="fas fa-stream text-muted text-4xl mb-4"></i>
                            <p class="text-muted font-bold">No recent system activity recorded.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --maroon: #800000;
            --maroon-dark: #600000;
            --gold: #c9a84c;
            --text-main: #1a202c;
            --text-muted: #718096;
            --bg-body: #f8fafc;
            --success: #16a34a;
        }

        .admin-dashboard-wrapper {
            padding: 0 10px 10px 10px;
        }

        /* MINIMAL HEADER */
        .welcome-header-minimal {
            padding-bottom: 8px;
            border-bottom: 2px solid #edf2f7;
        }

        .page-title {
            font-family: 'Outfit', sans-serif;
            font-size: 26px;
            font-weight: 850;
            color: var(--text-main);
            margin: 0;
            letter-spacing: -0.5px;
        }

        /* STATS GRID - COMPACT 4 IN A ROW */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px; /* Reduced gap between cards */
        }

        @media (max-width: 1024px) {
            .stats-row { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 600px) {
            .stats-row { grid-template-columns: 1fr; }
        }

        /* ADMIN STAT CARD - ULTRA RECTANGULAR COMPACT THEME */
        .admin-stat-card {
            background: white;
            padding: 14px 15px; /* Further reduced vertical padding */
            border-radius: 14px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            border: 1px solid #f1f5f9;
        }

        .admin-stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(128, 0, 0, 0.08);
        }

        .sc-icon-circle {
            width: 38px;
            height: 38px;
            background: rgba(128, 0, 0, 0.05);
            color: var(--maroon);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            margin-bottom: 12px; /* Reduced internal margin */
        }

        .sc-number {
            font-family: 'Outfit', sans-serif;
            font-size: 34px;
            font-weight: 850;
            color: var(--maroon);
            line-height: 1;
            margin-bottom: 4px; /* Reduced internal margin */
        }

        .sc-label {
            font-size: 10px; /* Smaller label */
            font-weight: 800;
            color: var(--text-muted);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* RECENT ACTIVITIES SECTION */
        .activity-section {
            margin-top: 50px;
        }

        .section-header-compact {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #edf2f7;
            padding-bottom: 12px;
        }

        .section-title-premium {
            font-size: 19px;
            font-weight: 850;
            color: var(--text-main);
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .section-title-link {
            text-decoration: none;
            cursor: pointer;
        }

        .activity-container-link {
            display: block;
            text-decoration: none;
            color: inherit;
            cursor: pointer;
        }

        .view-all-link {
            font-size: 12px;
            font-weight: 700;
            color: var(--maroon);
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: color 0.2s;
        }

        .view-all-link:hover { color: var(--gold); }

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

        /* Activity Accents */
        .odometer-accent { background: rgba(128, 0, 0, 0.05); color: var(--maroon); }
        .billing-accent { background: rgba(201, 168, 76, 0.05); color: var(--gold); }
        .driver-accent { background: rgba(31, 41, 55, 0.05); color: var(--text-main); }
        .service-accent { background: rgba(22, 163, 74, 0.05); color: #16a34a; }
        .system-accent { background: rgba(14, 116, 144, 0.08); color: #0e7490; }

        .activity-content { flex: 1; }

        .activity-top {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }

        .activity-user {
            font-size: 14px;
            font-weight: 800;
            color: var(--text-main);
        }

        .activity-time {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 600;
        }

        .activity-msg {
            font-size: 14px;
            color: #4b5563;
            margin: 0;
            line-height: 1.4;
        }

        .activity-msg strong { color: var(--text-main); font-weight: 700; }
        .text-maroon { color: var(--maroon); }

        .activity-action {
            flex-shrink: 0;
        }

        .btn-verify {
            text-decoration: none;
            background: var(--maroon-light, #fff5f5);
            color: var(--maroon);
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.2s;
            border: 1px solid rgba(128, 0, 0, 0.1);
        }

        .btn-verify:hover {
            background: var(--maroon);
            color: white;
            transform: scale(1.05);
        }

        .text-success { color: var(--success); }
        .text-4xl { font-size: 36px; }
        .mb-4 { margin-bottom: 16px; }

        /* SPECIAL STATES */
        .sc-icon-circle.danger-tint {
            background: rgba(220, 38, 38, 0.05);
            color: #dc2626;
        }
        .sc-number.danger-text {
            color: #dc2626;
        }

        /* ALERTS SECTION */
        .alerts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .alert-card {
            background: white;
            border-radius: 18px;
            padding: 18px 22px;
            display: flex;
            gap: 18px;
            align-items: center;
            border: 1px solid #f1f5f9;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            transition: all 0.3s;
        }

        .alert-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.05); }

        .alert-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .alert-expired .alert-icon { background: rgba(220, 38, 38, 0.1); color: #dc2626; }
        .alert-critical .alert-icon { background: rgba(234, 88, 12, 0.1); color: #ea580c; }
        .alert-warning .alert-icon { background: rgba(217, 119, 6, 0.1); color: #d97706; }

        .alert-details { flex: 1; }
        .alert-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; }
        .alert-type { font-size: 10px; font-weight: 850; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); }
        
        .alert-badge {
            font-size: 9px;
            font-weight: 900;
            padding: 2px 8px;
            border-radius: 6px;
            text-transform: uppercase;
        }

        .alert-expired .alert-badge { background: #fee2e2; color: #dc2626; }
        .alert-critical .alert-badge { background: #ffedd5; color: #ea580c; }
        .alert-warning .alert-badge { background: #fef3c7; color: #d97706; }

        .alert-item { font-size: 15px; font-weight: 850; color: var(--text-main); margin-bottom: 2px; }
        .alert-date { font-size: 12px; color: var(--text-muted); font-weight: 600; }
        .alert-countdown { font-weight: 800; }
        .alert-expired .alert-countdown { color: #dc2626; }
        .alert-critical .alert-countdown { color: #ea580c; }
        .alert-warning .alert-countdown { color: #d97706; }

        .fade-up {
            animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ─── MOBILE RESPONSIVENESS ─────────────────────── */
        @media (max-width: 768px) {
            .admin-dashboard-wrapper {
                padding: 0 0 10px 0;
            }

            .stats-row {
                grid-template-columns: 1fr 1fr;
                gap: 12px;
            }

            .admin-stat-card {
                padding: 14px 12px;
            }

            .sc-number {
                font-size: 26px !important;
            }

            .alerts-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .alert-card {
                padding: 14px 16px;
                gap: 12px;
            }

            .alert-header {
                flex-wrap: wrap;
                gap: 6px;
            }

            .section-header-compact {
                flex-wrap: wrap;
                gap: 8px;
            }

            .section-title-premium {
                font-size: 15px;
            }

            .activity-section {
                margin-top: 28px;
            }

            .activity-row {
                padding: 14px 16px;
                gap: 12px;
            }

            .activity-marker {
                width: 36px;
                height: 36px;
                font-size: 14px;
                border-radius: 10px;
                flex-shrink: 0;
            }

            .activity-top {
                flex-direction: column;
                align-items: flex-start;
                gap: 2px;
            }

            .activity-user {
                font-size: 13px;
            }

            .activity-msg {
                font-size: 13px;
            }

            .page-title {
                font-size: 20px;
            }
        }

        @media (max-width: 480px) {
            .stats-row {
                grid-template-columns: 1fr;
            }

            .admin-stat-card {
                flex-direction: row;
                justify-content: flex-start;
                gap: 16px;
                text-align: left;
            }

            .sc-icon-circle {
                margin-bottom: 0;
                flex-shrink: 0;
            }
        }
    </style>
</x-admin-layout>
<x-user-layout>
    <div class="user-dashboard-wrapper fade-up">
        <div class="max-w-7xl mx-auto">
            
            <!-- GREETING BANNER -->
            <div class="welcome-hero-banner">
                <div class="hero-content">
                    <div class="hero-text-side">
                        <span class="hero-badge">Member Portal</span>
                        <h1 class="hero-title">Hello, {{ Auth::user()->name }}!</h1>
                        <p class="hero-subtitle">Ready for your next journey with Mwigito Excel? Your current status is active.</p>
                        
                        <div class="hero-actions">
                            <a href="{{ route('bookings.create') }}" class="btn-hero-primary">
                                <i class="fas fa-bus"></i> Book A Bus
                            </a>
                            <a href="{{ route('bookings.schedule') }}" class="btn-hero-secondary">
                                <i class="fas fa-calendar-alt"></i> View Schedule
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dashboard-grid">
                <!-- LEFT COLUMN: STATS & HISTORY -->
                <div class="dashboard-main-col">
                    <div class="stats-row">
                        <div class="stat-glass-card">
                            <div class="stat-icon-wrap" style="background: rgba(128, 0, 0, 0.1); color: var(--maroon);">
                                <i class="fas fa-route"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-label">Active Trips</div>
                                <div class="stat-value">{{ $activeTripsCount }}</div>
                            </div>
                        </div>

                        <div class="stat-glass-card">
                            <div class="stat-icon-wrap" style="background: rgba(201, 168, 76, 0.1); color: var(--gold);">
                                <i class="fas fa-history"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-label">Total Bookings</div>
                                <div class="stat-value">{{ $totalBookingsCount }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="content-white-card mt-8">
                        <div class="card-header">
                            <h3 class="card-title">Recent Bookings</h3>
                            <a href="{{ route('bookings.index') }}" class="card-link">View All History</a>
                        </div>
                        @if($recentBookings->isEmpty())
                        <div class="empty-state-wrap">
                            <div class="empty-icon">
                                <i class="fas fa-calendar-times"></i>
                            </div>
                            <p class="empty-text">You haven't made any bookings yet. Your future travels will appear here.</p>
                            <a href="{{ route('bookings.create') }}" class="btn-empty-action" style="text-decoration: none; display: inline-block;">Start Planning Now</a>
                        </div>
                        @else
                        <div class="recent-bookings-list" style="margin-top: 15px;">
                            @foreach($recentBookings as $booking)
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px solid #f3f4f6;">
                                <div style="display: flex; gap: 15px; align-items: center;">
                                    <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(128, 0, 0, 0.05); color: var(--maroon); display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-bus"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 800; color: #111827; font-size: 14px;">{{ $booking->pickup_location }} &rarr; {{ $booking->destination }}</div>
                                        <div style="font-size: 12px; color: #6b7280; margin-top: 2px;">{{ $booking->date->format('M d, Y') }}</div>
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <span style="font-size: 10px; font-weight: 800; text-transform: uppercase; padding: 4px 8px; border-radius: 6px; background: #f3f4f6; color: #374151;">{{ $booking->status }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div style="text-align: center; margin-top: 20px;">
                            <a href="{{ route('bookings.index') }}" style="font-size: 13px; font-weight: 700; color: var(--maroon); text-decoration: none;">Proceed to Timeline <i class="fas fa-arrow-right" style="margin-left: 5px;"></i></a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- RIGHT COLUMN: PROFILE & LOYALTY -->
                <div class="dashboard-side-col">
                    <div class="profile-mini-card">
                        <div class="mini-avatar">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <h4 class="mini-name">{{ Auth::user()->name }}</h4>
                        <p class="mini-email">{{ Auth::user()->email }}</p>
                    </div>

                    <div class="loyalty-premium-card">
                        <div class="loyalty-content">
                            <div class="loyalty-header">
                                <span class="loyalty-tier">Mwigito Gold</span>
                                <i class="fas fa-crown text-gold"></i>
                            </div>
                            <h4 class="loyalty-title">Loyalty Points</h4>
                            <div class="loyalty-score">
                                <span class="score-num">150</span>
                                <span class="score-unit">XP</span>
                            </div>
                            <div class="progress-container">
                                <div class="progress-bar" style="width: 35%;"></div>
                            </div>
                            <p class="loyalty-hint">Reach 500 for a free trip!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --maroon: #800000;
            --maroon-dark: #600000;
            --gold: #c9a84c;
            --text-main: #1f2937;
            --text-muted: #6b7280;
            --bg-light: #f9fafb;
        }

        .user-dashboard-wrapper {
            padding: 0 10px 10px 10px;
        }

        /* GREETING BANNER */
        .welcome-hero-banner {
            background: linear-gradient(135deg, rgba(64, 0, 0, 0.95) 0%, rgba(20, 0, 0, 0.98) 100%), 
                        url('https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?q=80&w=2069&auto=format&fit=crop');
            background-blend-mode: overlay;
            background-size: cover;
            background-position: center;
            border-radius: 30px;
            padding: 60px;
            margin-bottom: 40px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .hero-badge {
            display: inline-block;
            padding: 6px 16px;
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 99px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }

        .hero-title {
            font-family: 'Outfit', sans-serif;
            font-size: 48px;
            font-weight: 900;
            margin: 0 0 15px 0;
            text-transform: uppercase;
            letter-spacing: -0.5px;
            line-height: 1;
        }

        .hero-subtitle {
            font-size: 18px;
            opacity: 0.85;
            margin-bottom: 35px;
            max-width: 600px;
        }

        .hero-actions {
            display: flex;
            gap: 15px;
        }

        .btn-hero-primary {
            background: var(--gold);
            color: var(--maroon);
            padding: 14px 28px;
            border-radius: 14px;
            font-weight: 800;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            box-shadow: 0 8px 20px rgba(201, 168, 76, 0.3);
        }

        .btn-hero-primary:hover {
            background: white;
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(255,255,255,0.4);
        }

        .btn-hero-secondary {
            background: rgba(255,255,255,0.1);
            color: white;
            padding: 14px 28px;
            border-radius: 14px;
            font-weight: 800;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255,255,255,0.2);
            transition: all 0.3s;
        }

        .btn-hero-secondary:hover {
            background: white;
            color: var(--maroon);
            transform: translateY(-3px);
        }

        /* GRID SYSTEM */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 30px;
        }

        @media (max-width: 1024px) {
            .dashboard-grid { grid-template-columns: 1fr; }
        }

        /* STATS */
        .stats-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .stat-glass-card {
            background: white;
            padding: 25px;
            border-radius: 24px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            border: 1px solid #f3f4f6;
            transition: transform 0.3s;
        }

        .stat-glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        .stat-icon-wrap {
            width: 60px;
            height: 60px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stat-label {
            font-size: 12px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 900;
            color: var(--text-main);
            line-height: 1;
        }

        /* MAIN CARD */
        .content-white-card {
            background: white;
            border-radius: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            border: 1px solid #f3f4f6;
            overflow: hidden;
            margin-top: 30px;
        }

        .card-header {
            padding: 25px 30px;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--text-main);
            margin: 0;
        }

        .card-link {
            font-size: 14px;
            font-weight: 700;
            color: var(--maroon);
            text-decoration: none;
        }

        .empty-state-wrap {
            padding: 60px 40px;
            text-align: center;
        }

        .empty-icon {
            font-size: 60px;
            color: #e5e7eb;
            margin-bottom: 20px;
        }

        .empty-text {
            color: var(--text-muted);
            margin-bottom: 25px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-empty-action {
            padding: 10px 25px;
            border: 2px solid var(--maroon);
            background: transparent;
            color: var(--maroon);
            border-radius: 12px;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-empty-action:hover {
            background: var(--maroon);
            color: white;
        }

        /* SIDEBAR CARD */
        .profile-mini-card {
            background: white;
            padding: 35px 30px;
            border-radius: 30px;
            text-align: center;
            border: 1px solid #f3f4f6;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            margin-bottom: 30px;
        }

        .mini-avatar {
            width: 80px;
            height: 80px;
            background: var(--bg-light);
            color: var(--maroon);
            font-size: 32px;
            font-weight: 900;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            border: 6px solid #f3f4f6;
        }

        .mini-name {
            font-size: 20px;
            font-weight: 900;
            color: var(--text-main);
            margin: 0 0 5px 0;
        }

        .mini-email {
            font-size: 14px;
            color: var(--text-muted);
            margin-bottom: 25px;
        }

        .btn-mini-profile {
            display: block;
            background: var(--bg-light);
            color: var(--text-main);
            padding: 12px;
            border-radius: 14px;
            font-weight: 800;
            text-decoration: none;
            font-size: 13px;
            transition: all 0.2s;
        }

        .btn-mini-profile:hover {
            background: var(--maroon);
            color: white;
        }

        .loyalty-premium-card {
            background: var(--maroon);
            border-radius: 30px;
            padding: 30px;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(128, 0, 0, 0.2);
        }

        .loyalty-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .loyalty-tier {
            font-size: 12px;
            font-weight: 800;
            color: var(--gold);
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .loyalty-title {
            font-size: 16px;
            font-weight: 700;
            margin: 0 0 10px 0;
        }

        .loyalty-score {
            display: flex;
            align-items: baseline;
            gap: 5px;
            margin-bottom: 15px;
        }

        .score-num {
            font-size: 42px;
            font-weight: 900;
            line-height: 1;
        }

        .score-unit {
            font-size: 14px;
            font-weight: 700;
            color: var(--gold);
        }

        .progress-container {
            height: 8px;
            background: rgba(0,0,0,0.2);
            border-radius: 99px;
            margin-bottom: 15px;
        }

        .progress-bar {
            height: 100%;
            background: var(--gold);
            border-radius: 99px;
        }

        .loyalty-hint {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            color: rgba(255,255,255,0.4);
            letter-spacing: 1px;
        }
        
        .fade-up {
            animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ─── MOBILE RESPONSIVENESS ─────────────────────── */
        @media (max-width: 768px) {
            .user-dashboard-wrapper {
                padding: 0 0 10px 0;
            }

            .welcome-hero-banner {
                padding: 30px 22px;
                margin-bottom: 20px;
                border-radius: 20px;
            }

            .hero-title {
                font-size: 28px;
                letter-spacing: -0.3px;
            }

            .hero-subtitle {
                font-size: 14px;
                margin-bottom: 24px;
            }

            .hero-actions {
                flex-direction: column;
                gap: 10px;
            }

            .btn-hero-primary,
            .btn-hero-secondary {
                width: 100%;
                justify-content: center;
                padding: 14px 20px;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .stats-row {
                grid-template-columns: 1fr 1fr;
                gap: 12px;
            }

            .stat-glass-card {
                padding: 18px 14px;
                gap: 12px;
            }

            .stat-icon-wrap {
                width: 46px;
                height: 46px;
                font-size: 18px;
                border-radius: 14px;
            }

            .stat-value {
                font-size: 24px;
            }

            .content-white-card {
                border-radius: 20px;
                margin-top: 16px;
            }

            .card-header {
                padding: 18px 20px;
                flex-wrap: wrap;
                gap: 8px;
            }

            .empty-state-wrap {
                padding: 40px 20px;
            }

            .profile-mini-card {
                padding: 24px 20px;
                border-radius: 20px;
                margin-top: 16px;
            }

            .loyalty-premium-card {
                border-radius: 20px;
                padding: 24px 20px;
                margin-top: 16px;
            }

            /* Booking list items: stack route & status vertically */
            .recent-bookings-list > div {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 10px !important;
            }
        }

        @media (max-width: 480px) {
            .welcome-hero-banner {
                padding: 24px 16px;
                border-radius: 16px;
            }

            .hero-title {
                font-size: 22px;
            }

            .hero-badge {
                font-size: 10px;
                padding: 4px 12px;
            }

            .stats-row {
                grid-template-columns: 1fr;
            }

            .stat-glass-card {
                flex-direction: row;
            }
        }
    </style>
</x-user-layout>

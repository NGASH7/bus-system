<x-driver-layout>
    <div class="driver-dashboard-wrapper fade-up">
        <div class="max-w-7xl mx-auto">

            <!-- DRIVER GREETING BANNER -->
            <div class="driver-hero-banner">
                <div class="hero-content">
                    <div class="hero-badge">Driver Operations</div>
                    @php
                        $todaysTrips = clone $mySchedule;
                        $todaysTrips = $todaysTrips->filter(function($trip) { return $trip->date->isToday(); });
                        $nextTrip = $todaysTrips->first();
                    @endphp
                    <h1 class="hero-title">Ready for Shift, {{ explode(' ', Auth::user()->name)[0] }}?</h1>
                    <p class="hero-subtitle">You have {{ $todaysTrips->count() }} trips scheduled for today. Safety first, excellence always.</p>

                    <div class="hero-stats-row">
                        <div class="hero-mini-stat">
                            <span class="mini-stat-label">Next Trip</span>
                            <span class="mini-stat-value">{{ $nextTrip ? \Carbon\Carbon::parse($nextTrip->pickup_time)->format('H:i A') : '--:--' }}</span>
                        </div>
                        <div class="hero-mini-stat">
                            <span class="mini-stat-label">Assigned Bus</span>
                            <span class="mini-stat-value">{{ $assignedBus ? $assignedBus->plate_number : 'Not Assigned' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dashboard-grid">
                <!-- MAIN CONTENT AREA -->
                <div class="dashboard-main-col">
                    <div class="stats-row">
                        <div class="stat-glass-card">
                            <div class="stat-icon-wrap" style="background: rgba(128, 0, 0, 0.1); color: var(--maroon);">
                                <i class="fas fa-route"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-label">Today's Trips</div>
                                <div class="stat-value">{{ $todaysTrips->count() }}</div>
                            </div>
                        </div>

                        <div class="stat-glass-card">
                            <div class="stat-icon-wrap"
                                style="background: rgba(201, 168, 76, 0.1); color: var(--gold);">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-label">Total Assigned</div>
                                <div class="stat-value">{{ $mySchedule->count() }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="content-white-card mt-8">
                        <div class="card-header">
                            <h3 class="card-title">Assigned Vehicle Details</h3>
                        </div>
                        <div class="vehicle-detail-body">
                            @if(Auth::user()->bus)
                            <div class="vehicle-info-main">
                                <div class="vehicle-avatar">
                                    <i class="fas fa-bus"></i>
                                </div>
                                <div class="vehicle-meta">
                                    <h4 class="v-name">{{ Auth::user()->bus->plate_number }} ({{ Auth::user()->bus->model }})</h4>
                                    <p class="v-status"><span class="status-dot"></span> {{ Auth::user()->bus->is_active ? 'Active' : 'Maintenance' }}</p>
                                </div>
                            </div>
                            <div class="vehicle-stats-grid">
                                <div class="v-mini-card">
                                    <span class="v-label">Seating</span>
                                    <span class="v-value">{{ Auth::user()->bus->capacity }} Seats</span>
                                </div>
                                <div class="v-mini-card">
                                    <span class="v-label">Insurance Exp.</span>
                                    <span class="v-value">{{ Auth::user()->bus->insurance_expiry ? \Carbon\Carbon::parse(Auth::user()->bus->insurance_expiry)->format('M d, Y') : '—' }}</span>
                                </div>
                                <div class="v-mini-card">
                                    <span class="v-label">My License Exp.</span>
                                    <span class="v-value">{{ Auth::user()->license_expiry ? \Carbon\Carbon::parse(Auth::user()->license_expiry)->format('M d, Y') : '—' }}</span>
                                </div>
                            </div>
                            @else
                            <div class="text-center py-8">
                                <i class="fas fa-bus-slash text-4xl text-gray-200 mb-4 block"></i>
                                <p class="text-gray-400 font-bold">No Vehicle Assigned Yet</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- NEW SCHEDULE BLOCK -->
                    <div class="content-white-card mt-8">
                        <div class="card-header">
                            <h3 class="card-title">My Operations Log</h3>
                        </div>
                        <div class="card-body p-6" style="padding: 24px;">
                            @if($mySchedule->isEmpty())
                                <div style="text-align: center; padding: 40px 20px;">
                                    <i class="fas fa-calendar-check" style="font-size: 30px; color: #d1d5db; margin-bottom: 15px;"></i>
                                    <p style="color: #6b7280; font-weight: 500; font-size: 14px;">No upcoming trips assigned to your vehicle at this moment.</p>
                                </div>
                            @else
                                <div style="display: flex; flex-direction: column; gap: 15px;">
                                    @foreach($mySchedule as $trip)
                                    <div style="display: flex; align-items: stretch; border: 1px solid #f3f4f6; border-radius: 12px; overflow: hidden; background: #fff;">
                                        <div style="width: 80px; background: {{ $trip->date->isToday() ? '#fff0f0' : '#f9fafb' }}; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 15px; border-right: 1px solid #f3f4f6;">
                                            <span style="font-size: 10px; font-weight: 900; color: {{ $trip->date->isToday() ? 'var(--maroon)' : '#9ca3af' }}; text-transform: uppercase;">{{ $trip->date->format('M') }}</span>
                                            <span style="font-size: 20px; font-weight: 900; color: #111827;">{{ $trip->date->format('d') }}</span>
                                        </div>
                                        <div style="flex: 1; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center;">
                                            <div>
                                                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 5px;">
                                                    <span style="font-weight: 900; font-size: 15px; color: #111827;">{{ \Carbon\Carbon::parse($trip->pickup_time)->format('H:i') }}</span>
                                                    @if($trip->date->isToday()) <span style="background: #10b981; color: white; padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: 900; text-transform: uppercase;">Today</span> @endif
                                                </div>
                                                <div style="font-size: 14px; font-weight: 700; color: #4b5563;">{{ $trip->pickup_location }} &rarr; {{ $trip->destination }}</div>
                                                <div style="font-size: 12px; color: #9ca3af; margin-top: 4px;"><i class="fas fa-user-tie"></i> Client: {{ $trip->user->name }} &middot; <i class="fas fa-phone-alt"></i> {{ $trip->user->phone_number ?? 'N/A' }}</div>
                                            </div>
                                            <div style="text-align: center;">
                                                <span style="display: block; font-size: 10px; font-weight: 800; color: #6b7280; text-transform: uppercase; margin-bottom: 4px;">Service</span>
                                                <span style="display: inline-block; padding: 4px 10px; background: #f3f4f6; color: #374151; border-radius: 6px; font-size: 10px; font-weight: 800;">{{ $trip->service_type ?? 'Standard' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- SIDE MODULES -->
                <div class="dashboard-side-col">
                    <!-- PERSONAL DOSSIER CARD -->
                    <div class="unified-status-card mb-6">
                        <div class="card-header-accent" style="background: var(--maroon); color: white; padding: 15px 25px;">
                            <h3 class="status-card-title text-white" style="color: white; font-size: 13px; letter-spacing: 1px;">My Official Data</h3>
                        </div>
                        
                        <div class="p-8 px-12">
                            <div class="data-slot mb-6">
                                <span class="row-label mb-2 block" style="font-size: 9px; opacity: 0.6;">Full Name</span>
                                <div class="font-black text-lg text-gray-900 leading-tight uppercase" style="font-family: 'Outfit', sans-serif;">{{ Auth::user()->name }}</div>
                            </div>
                            
                            <div class="data-slot mb-6">
                                <span class="row-label mb-2 block" style="font-size: 9px; opacity: 0.6;">Phone Number</span>
                                <div class="font-black text-md text-gray-800 tracking-tight">{{ Auth::user()->phone_number ?? 'Not Provided' }}</div>
                            </div>

                            <div class="data-slot last:mb-0">
                                <span class="row-label mb-2 block" style="font-size: 9px; opacity: 0.6;">National ID Number</span>
                                <div class="font-black text-xl text-gray-900 tracking-tighter" style="font-family: 'Outfit', sans-serif; letter-spacing: -0.5px;">{{ Auth::user()->national_id ?? 'Not Provided' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="unified-status-card">
                        <div class="card-header-accent">
                            <h3 class="status-card-title">Compliance & Status</h3>
                        </div>
                        
                        <!-- License Row -->
                        <div class="status-row-item">
                            <div class="row-icon-box" style="background: rgba(128, 0, 0, 0.05); color: var(--maroon);">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div class="row-info">
                                <span class="row-label">License Status</span>
                                <div class="row-flex">
                                    <span class="row-value {{ Auth::user()->license_expiry && \Carbon\Carbon::parse(Auth::user()->license_expiry)->isPast() ? 'text-red' : 'text-success' }}">
                                        {{ Auth::user()->license_expiry && \Carbon\Carbon::parse(Auth::user()->license_expiry)->isPast() ? 'Expired' : 'Valid' }}
                                    </span>
                                    <span class="row-meta">Exp: {{ Auth::user()->license_expiry ? \Carbon\Carbon::parse(Auth::user()->license_expiry)->format('M Y') : '—' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Insurance Row -->
                        <div class="status-row-item">
                            <div class="row-icon-box" style="background: rgba(201, 168, 76, 0.05); color: var(--gold);">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="row-info">
                                <span class="row-label">License No.</span>
                                <div class="row-flex">
                                    <span class="row-value">{{ Auth::user()->license_number ?? 'Pending' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Maintenance Row -->
                        <div class="status-row-item last-item">
                            <div class="row-icon-box" style="background: rgba(31, 41, 55, 0.05); color: var(--text-main);">
                                <i class="fas fa-tools"></i>
                            </div>
                            <div class="row-info">
                                <span class="row-label">Vehicle Docs</span>
                                <div class="row-flex">
                                    <span class="row-value">{{ Auth::user()->bus ? 'Insured' : 'No Bus' }}</span>
                                </div>
                            </div>
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
            --success: #16a34a;
        }

        .driver-dashboard-wrapper {
            padding: 0 10px 10px 10px;
        }

        /* BANNER */
        .driver-hero-banner {
            background: linear-gradient(135deg, rgba(64, 0, 0, 0.95) 0%, rgba(20, 0, 0, 0.98) 100%),
                url('https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?q=80&w=2069&auto=format&fit=crop');
            background-blend-mode: overlay;
            background-size: cover;
            background-position: center;
            border-radius: 30px;
            padding: 50px;
            margin-bottom: 30px;
            color: white;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .hero-badge {
            display: inline-block;
            padding: 5px 14px;
            background: var(--gold);
            color: var(--maroon);
            border-radius: 99px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }

        .hero-title {
            font-family: 'Outfit', sans-serif;
            font-size: 40px;
            font-weight: 900;
            margin: 0 0 10px 0;
            line-height: 1;
        }

        .hero-subtitle {
            font-size: 16px;
            opacity: 0.8;
            margin-bottom: 25px;
            max-width: 500px;
        }

        .hero-stats-row {
            display: flex;
            gap: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .hero-mini-stat {
            display: flex;
            flex-direction: column;
        }

        .mini-stat-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            opacity: 0.6;
            font-weight: 700;
        }

        .mini-stat-value {
            font-size: 20px;
            font-weight: 800;
            color: var(--gold);
        }

        /* GRID */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 30px;
        }

        @media (max-width: 1024px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        /* STATS */
        .stats-row {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-glass-card {
            background: white;
            padding: 16px 20px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            border: 1px solid #f3f4f6;
            transition: transform 0.3s;
            width: 240px; /* FIXED WIDTH TO REDUCE HORIZONTALLY */
            flex-shrink: 0;
        }

        .stat-glass-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .stat-icon-wrap {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .stat-label {
            font-size: 10px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0px;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 900;
            color: var(--text-main);
            line-height: 1.1;
        }

        /* VEHICLE CARD */
        .content-white-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #f3f4f6;
            overflow: hidden;
        }

        .card-header {
            padding: 20px 25px;
            border-bottom: 1px solid #f3f4f6;
        }

        .card-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--text-main);
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .vehicle-detail-body {
            padding: 25px;
        }

        .vehicle-info-main {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 25px;
        }

        .vehicle-avatar {
            width: 64px;
            height: 64px;
            background: var(--bg-light);
            color: var(--maroon);
            border-radius: 20px;
            font-size: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #eee;
        }

        .v-name {
            font-size: 18px;
            font-weight: 800;
            color: var(--text-main);
            margin: 0 0 5px 0;
        }

        .v-status {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background: var(--success);
            border-radius: 50%;
            display: inline-block;
        }

        .vehicle-stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .v-mini-card {
            background: var(--bg-light);
            padding: 15px;
            border-radius: 16px;
            text-align: center;
            border: 1px solid #f1f5f9;
        }

        .v-label {
            display: block;
            font-size: 10px;
            text-transform: uppercase;
            color: var(--text-muted);
            font-weight: 700;
            margin-bottom: 4px;
        }

        .v-value {
            font-size: 15px;
            font-weight: 800;
            color: var(--text-main);
        }

        /* UNIFIED STATUS CARD */
        .unified-status-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #f3f4f6;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        }

        .card-header-accent {
            background: #fafafa;
            padding: 20px 25px;
            border-bottom: 1px solid #f1f5f9;
        }

        .status-card-title {
            font-size: 15px;
            font-weight: 800;
            color: var(--text-main);
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-row-item {
            padding: 20px 25px;
            display: flex;
            gap: 15px;
            align-items: center;
            border-bottom: 1px solid #f9fafb;
            transition: background 0.2s;
        }

        .status-row-item:hover {
            background: #fcfcfc;
        }

        .status-row-item.last-item {
            border-bottom: none;
        }

        .row-icon-box {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .row-info {
            flex: 1;
        }

        .row-label {
            display: block;
            font-size: 10px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 3px;
        }

        .row-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .row-value {
            font-size: 15px;
            font-weight: 800;
            color: var(--text-main);
        }

        .row-meta {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 600;
        }

        .text-success { color: var(--success); }

        .fade-up {
            animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</x-driver-layout>
<x-admin-layout>
    <div class="admin-wrapper fade-up">
        <div class="admin-top-bar">
            <div>
                <h1 class="admin-page-title">FLEET SCHEDULE</h1>
                <p class="admin-page-subtitle">Master calendar of all confirmed operations.</p>
            </div>
            <div class="admin-actions">
                <a href="{{ route('admin.bookings.index') }}" class="btn-outline">
                    <i class="fas fa-tasks"></i> Manage Requests
                </a>
            </div>
        </div>

        <div class="schedule-container">
            @forelse($bookings as $date => $dailyBookings)
            <div class="schedule-block">
                
                <div class="schedule-header">
                    <div class="date-hero">
                        <div class="date-badge">
                            <span class="month">{{ \Carbon\Carbon::parse($date)->format('M') }}</span>
                            <span class="day">{{ \Carbon\Carbon::parse($date)->format('d') }}</span>
                        </div>
                        <div class="date-info">
                            <h2>{{ \Carbon\Carbon::parse($date)->format('l') }}</h2>
                            <p>{{ $dailyBookings->count() }} Dispatch(es) Scheduled</p>
                        </div>
                    </div>
                </div>

                <div class="schedule-body">
                    <div class="dispatch-list">
                        @foreach($dailyBookings as $booking)
                        <div class="dispatch-row">
                            
                            <!-- Time Column -->
                            <div class="time-col">
                                <span class="time-label">DEPART</span>
                                <span class="time-value">{{ \Carbon\Carbon::parse($booking->pickup_time)->format('H:i') }}</span>
                                @if($booking->return_date && $booking->return_time)
                                <div class="return-time">
                                    <i class="fas fa-undo-alt"></i> {{ \Carbon\Carbon::parse($booking->return_time)->format('H:i') }}
                                    @if($booking->return_date->format('Y-m-d') != $date)
                                      <br>(+ days)
                                    @endif
                                </div>
                                @endif
                            </div>

                            <!-- Details Column -->
                            <div class="details-col">
                                <div class="details-top">
                                    <div class="status-confirmed">
                                        <i class="fas fa-check-circle"></i> Confirmed
                                    </div>
                                    <span class="service-tag">{{ $booking->service_type ?? 'Standard Lease' }}</span>
                                </div>
                                
                                <h3 class="route-title">
                                    {{ $booking->pickup_location }} 
                                    <i class="fas fa-long-arrow-alt-right"></i> 
                                    {{ $booking->destination }}
                                </h3>
                                
                                <div class="meta-row">
                                    <div class="meta-item">
                                        <i class="fas fa-bus"></i>
                                        <span>{{ $booking->bus->plate_number ?? 'TBD' }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-user-tie"></i>
                                        <span>{{ $booking->user->name }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-phone-alt"></i>
                                        <span>{{ $booking->user->phone_number ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Column -->
                            <div class="action-col">
                                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn-go">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>

                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-schedule">
                <div class="icon-circle">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <h3>Schedule is Clear</h3>
                <p>There are currently no confirmed bookings in the system. When you accept booking requests, they will automatically appear here on the master schedule.</p>
                <div class="empty-action">
                    <a href="{{ route('admin.bookings.index') }}" class="btn-maroon">
                        Review Pending Requests
                    </a>
                </div>
            </div>
            @endforelse
        </div>
    </div>
    
    <style>
        :root {
            --maroon: #800000;
            --maroon-dark: #600000;
        }

        .admin-wrapper {
            padding: 10px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .admin-top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .admin-page-title {
            font-family: 'Outfit', sans-serif;
            font-size: 36px;
            font-weight: 900;
            color: #111827;
            letter-spacing: -1px;
            margin: 0;
            line-height: 1.1;
        }

        .admin-page-subtitle {
            color: #6b7280;
            margin: 5px 0 0 0;
            font-size: 15px;
        }

        .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 24px;
            background: white;
            border: 1px solid #e5e7eb;
            color: #4b5563;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.2s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        .btn-outline i { color: var(--maroon); }

        .btn-outline:hover {
            background: #f9fafb;
            border-color: #d1d5db;
            color: #111827;
            transform: translateY(-1px);
        }

        .schedule-container {
            display: flex;
            flex-direction: column;
            gap: 40px;
        }

        .schedule-block {
            background: white;
            border-radius: 32px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            border: 1px solid #f3f4f6;
            overflow: hidden;
        }

        .schedule-header {
            background: #111827;
            color: white;
            padding: 24px 32px;
            border-bottom: 1px solid #374151;
        }

        .date-hero {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .date-badge {
            width: 65px;
            height: 65px;
            background: rgba(255,255,255,0.1);
            border-radius: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .date-badge .month {
            font-size: 12px;
            font-weight: 900;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: -2px;
        }

        .date-badge .day {
            font-size: 28px;
            font-weight: 900;
            line-height: 1;
        }

        .date-info h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 24px;
            font-weight: 900;
            margin: 0 0 4px 0;
            letter-spacing: -0.5px;
        }

        .date-info p {
            font-size: 13px;
            color: #9ca3af;
            font-weight: 800;
            margin: 0;
        }

        .schedule-body {
            padding: 32px;
        }

        .dispatch-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .dispatch-row {
            display: flex;
            background: #f9fafb;
            border-radius: 20px;
            border: 1px solid #f3f4f6;
            overflow: hidden;
            transition: all 0.2s;
            align-items: stretch;
        }

        .dispatch-row:hover {
            border-color: #e5e7eb;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.03);
        }

        .time-col {
            width: 140px;
            background: white;
            border-right: 1px solid #f3f4f6;
            padding: 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            flex-shrink: 0;
        }

        .time-label {
            font-size: 10px;
            font-weight: 900;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 4px;
        }

        .time-value {
            font-size: 22px;
            font-weight: 900;
            color: #111827;
        }

        .return-time {
            margin-top: 8px;
            font-size: 12px;
            font-weight: 800;
            color: #6b7280;
        }

        .details-col {
            padding: 24px 32px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .details-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .status-confirmed {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            background: #dcfce7;
            color: #166534;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            gap: 4px;
        }

        .service-tag {
            font-size: 12px;
            font-weight: 800;
            color: #6b7280;
        }

        .route-title {
            font-size: 18px;
            font-weight: 900;
            color: #111827;
            margin: 0 0 16px 0;
            letter-spacing: -0.5px;
        }
        
        .route-title i {
            color: var(--maroon);
            margin: 0 12px;
        }

        .meta-row {
            display: flex;
            align-items: center;
            gap: 32px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }
        
        .meta-item i {
            color: #9ca3af;
        }

        .meta-item span {
            font-weight: 800;
            color: #374151;
        }

        .action-col {
            width: 80px;
            background: white;
            border-left: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .btn-go {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #f9fafb;
            color: #9ca3af;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.2s;
            font-size: 16px;
        }

        .btn-go:hover {
            background: var(--maroon);
            color: white;
        }

        .empty-schedule {
            background: white;
            border-radius: 32px;
            padding: 80px 30px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            border: 1px solid #f3f4f6;
        }

        .icon-circle {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            background: #f9fafb;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px auto;
            color: #d1d5db;
            font-size: 40px;
        }

        .empty-schedule h3 {
            font-family: 'Outfit', sans-serif;
            font-size: 24px;
            font-weight: 900;
            color: #111827;
            margin: 0 0 12px 0;
        }

        .empty-schedule p {
            color: #6b7280;
            max-width: 500px;
            margin: 0 auto 30px auto;
            line-height: 1.6;
            font-size: 15px;
        }

        .btn-maroon {
            display: inline-block;
            padding: 14px 32px;
            background: var(--maroon);
            color: white;
            font-weight: 800;
            border-radius: 14px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-maroon:hover {
            background: var(--maroon-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(128,0,0,0.2);
        }

        .fade-up {
            animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-admin-layout>

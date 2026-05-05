<x-driver-layout>
    <div class="schedule-wrapper fade-up">

        <div class="page-hero">
            <div class="hero-bar"></div>
            <h1 class="page-title">MY SCHEDULE</h1>
            <p class="page-sub">All upcoming confirmed trips assigned to your vehicle.</p>
        </div>

        @if(!$assignedBus)
        <div class="no-bus-card">
            <i class="fas fa-bus-slash"></i>
            <h3>No Vehicle Assigned</h3>
            <p>You do not currently have a bus assigned. Please contact fleet management.</p>
        </div>
        @else

        @php
            $todayTrips = $mySchedule->filter(fn($t) => $t->date->isToday());
            $futureTrips = $mySchedule->filter(fn($t) => !$t->date->isToday());
        @endphp

        <!-- SUMMARY CHIPS -->
        <div class="summary-chips">
            <div class="chip chip-today">
                <i class="fas fa-sun"></i>
                <strong>{{ $todayTrips->count() }}</strong> Today
            </div>
            <div class="chip chip-future">
                <i class="fas fa-calendar-alt"></i>
                <strong>{{ $futureTrips->count() }}</strong> Upcoming
            </div>
            <div class="chip chip-bus">
                <i class="fas fa-bus"></i>
                {{ $assignedBus->plate_number }}
            </div>
        </div>

        @if($mySchedule->isEmpty())
        <div class="empty-state">
            <i class="fas fa-calendar-check"></i>
            <h3>All Clear!</h3>
            <p>No trips are currently scheduled for your vehicle. Enjoy your free time.</p>
        </div>
        @else

        @php
            $grouped = $mySchedule->groupBy(fn($t) => $t->date->format('Y-m-d'));
        @endphp

        <div class="schedule-timeline">
            @foreach($grouped as $date => $trips)
            @php $dateObj = \Carbon\Carbon::parse($date); @endphp
            <div class="timeline-group">
                <div class="date-header {{ $dateObj->isToday() ? 'date-today' : '' }}">
                    <div class="date-block">
                        <span class="date-day">{{ $dateObj->format('D') }}</span>
                        <span class="date-num">{{ $dateObj->format('d') }}</span>
                        <span class="date-mon">{{ $dateObj->format('M Y') }}</span>
                    </div>
                    @if($dateObj->isToday())
                    <span class="today-badge">TODAY</span>
                    @endif
                </div>

                <div class="trips-list">
                    @foreach($trips as $trip)
                    <div class="trip-card {{ $trip->date->isToday() ? 'trip-today' : '' }}">
                        <div class="trip-time-col">
                            <span class="trip-time">{{ \Carbon\Carbon::parse($trip->pickup_time)->format('H:i') }}</span>
                            <span class="trip-ampm">{{ \Carbon\Carbon::parse($trip->pickup_time)->format('A') }}</span>
                        </div>
                        <div class="trip-connector">
                            <div class="connector-dot"></div>
                            <div class="connector-line"></div>
                        </div>
                        <div class="trip-body">
                            <div class="trip-route">
                                <span class="route-from">{{ $trip->pickup_location }}</span>
                                <i class="fas fa-arrow-right route-arrow"></i>
                                <span class="route-to">{{ $trip->destination }}</span>
                            </div>
                            <div class="trip-meta-row">
                                <span class="meta-item"><i class="fas fa-user-tie"></i> {{ $trip->user->name }}</span>
                                @if($trip->user->phone_number)
                                <span class="meta-item"><i class="fas fa-phone"></i> {{ $trip->user->phone_number }}</span>
                                @endif
                                <span class="meta-item service-type"><i class="fas fa-tag"></i> {{ $trip->service_type ?? 'Standard' }}</span>
                            </div>
                            @if($trip->return_date)
                            <div class="return-note">
                                <i class="fas fa-undo-alt"></i> Return: {{ \Carbon\Carbon::parse($trip->return_date)->format('M d, Y') }}
                                @if($trip->return_time)at {{ \Carbon\Carbon::parse($trip->return_time)->format('H:i') }}@endif
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        @endif
        @endif
    </div>

    <style>
        :root { --maroon: #800000; --maroon-dark: #600000; --gold: #c9a84c; }

        .schedule-wrapper { max-width: 850px; margin: 0 auto; padding: 40px 20px; }

        .page-hero { position: relative; padding-left: 18px; margin-bottom: 40px; }
        .hero-bar { position: absolute; left: 0; top: 3px; bottom: 3px; width: 5px;
            background: linear-gradient(to bottom, var(--maroon), var(--gold)); border-radius: 4px; }
        .page-title { font-family: 'Outfit', sans-serif; font-size: 34px; font-weight: 900;
            color: #111827; margin: 0 0 6px 0; letter-spacing: -1px; }
        .page-sub { font-size: 15px; color: #6b7280; margin: 0; }

        .summary-chips { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 32px; }
        .chip { display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px;
            border-radius: 99px; font-size: 14px; font-weight: 800; border: 1px solid; }
        .chip-today { background: #fff0f0; color: var(--maroon); border-color: rgba(128,0,0,0.15); }
        .chip-future { background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe; }
        .chip-bus { background: #f3f4f6; color: #374151; border-color: #e5e7eb; }

        .no-bus-card, .empty-state {
            text-align: center; padding: 80px 30px; background: white;
            border-radius: 24px; border: 2px dashed #e5e7eb; }
        .no-bus-card i, .empty-state i { font-size: 40px; color: #d1d5db; margin-bottom: 20px; display: block; }
        .no-bus-card h3, .empty-state h3 { font-family: 'Outfit', sans-serif; font-size: 24px;
            font-weight: 900; color: #111827; margin: 0 0 10px 0; }
        .no-bus-card p, .empty-state p { color: #6b7280; margin: 0; }

        .schedule-timeline { display: flex; flex-direction: column; gap: 32px; }

        .timeline-group {}

        .date-header { display: flex; align-items: center; gap: 16px; margin-bottom: 16px;
            padding: 16px 20px; background: white; border-radius: 16px;
            border: 1px solid #f3f4f6; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
        .date-header.date-today { background: var(--maroon); border-color: var(--maroon-dark); }
        .date-header.date-today .date-day,
        .date-header.date-today .date-num,
        .date-header.date-today .date-mon { color: white; }

        .date-block { display: flex; align-items: baseline; gap: 8px; }
        .date-day { font-size: 12px; font-weight: 900; text-transform: uppercase;
            color: #9ca3af; letter-spacing: 2px; }
        .date-num { font-family: 'Outfit', sans-serif; font-size: 28px; font-weight: 900; color: #111827; }
        .date-mon { font-size: 12px; font-weight: 700; color: #9ca3af; }

        .today-badge { margin-left: auto; background: rgba(255,255,255,0.2); color: white;
            font-size: 10px; font-weight: 900; padding: 4px 10px; border-radius: 99px;
            letter-spacing: 1.5px; border: 1px solid rgba(255,255,255,0.3); }

        .trips-list { display: flex; flex-direction: column; gap: 12px; padding-left: 16px; }

        .trip-card { display: flex; align-items: flex-start; gap: 0; background: white;
            border-radius: 16px; border: 1px solid #f3f4f6;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02); overflow: hidden;
            transition: all 0.2s; }
        .trip-card:hover { box-shadow: 0 8px 25px rgba(0,0,0,0.06); transform: translateY(-1px); }
        .trip-card.trip-today { border-left: 4px solid #10b981; }

        .trip-time-col { min-width: 75px; padding: 20px 0 20px 20px; display: flex;
            flex-direction: column; align-items: center; justify-content: center; }
        .trip-time { font-family: 'Outfit', sans-serif; font-size: 20px; font-weight: 900; color: #111827; }
        .trip-ampm { font-size: 10px; font-weight: 700; color: #9ca3af; text-transform: uppercase; margin-top: 2px; }

        .trip-connector { display: flex; flex-direction: column; align-items: center;
            padding: 20px 12px; gap: 0; }
        .connector-dot { width: 10px; height: 10px; border-radius: 50%;
            background: var(--maroon); flex-shrink: 0; }
        .connector-line { flex: 1; width: 2px; background: #e5e7eb; min-height: 10px; }

        .trip-body { flex: 1; padding: 18px 20px 18px 8px; }

        .trip-route { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-bottom: 10px; }
        .route-from, .route-to { font-family: 'Outfit', sans-serif; font-weight: 900;
            font-size: 16px; color: #111827; }
        .route-arrow { color: #9ca3af; font-size: 12px; }

        .trip-meta-row { display: flex; gap: 16px; flex-wrap: wrap; }
        .meta-item { font-size: 12px; font-weight: 700; color: #6b7280; display: flex; align-items: center; gap: 5px; }
        .meta-item i { color: #9ca3af; }
        .service-type { background: #f3f4f6; padding: 3px 10px; border-radius: 6px; color: #374151; }

        .return-note { margin-top: 8px; font-size: 12px; font-weight: 700; color: #1d4ed8;
            background: #eff6ff; padding: 5px 10px; border-radius: 8px; display: inline-block; }
        .return-note i { margin-right: 5px; }

        .fade-up { animation: fadeUp 0.5s cubic-bezier(0.16,1,0.3,1) both; }
        @keyframes fadeUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
    </style>
</x-driver-layout>

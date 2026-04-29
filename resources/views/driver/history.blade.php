<x-driver-layout>
    <div class="history-wrapper fade-up">

        <div class="page-hero">
            <div class="hero-bar"></div>
            <h1 class="page-title">TRIP HISTORY</h1>
            <p class="page-sub">All completed operations for your assigned vehicle.</p>
        </div>

        @if(!$assignedBus)
        <div class="empty-state">
            <i class="fas fa-bus-slash"></i>
            <h3>No Vehicle Assigned</h3>
            <p>Contact fleet management to have a vehicle assigned to you.</p>
        </div>
        @elseif($tripHistory->isEmpty())
        <div class="empty-state">
            <i class="fas fa-history"></i>
            <h3>No History Yet</h3>
            <p>Completed trips will automatically appear here the day after they take place.</p>
        </div>
        @else

        <div class="stats-row">
            <div class="stat-pill">
                <i class="fas fa-road"></i>
                <div>
                    <span class="stat-num">{{ $tripHistory->count() }}</span>
                    <span class="stat-lbl">Total Trips Completed</span>
                </div>
            </div>
            <div class="stat-pill">
                <i class="fas fa-bus"></i>
                <div>
                    <span class="stat-num">{{ $assignedBus->plate_number }}</span>
                    <span class="stat-lbl">Assigned Vehicle</span>
                </div>
            </div>
        </div>

        <div class="history-table-card">
            <div class="table-header">
                <div class="th-cell th-date">Date</div>
                <div class="th-cell th-time">Time</div>
                <div class="th-cell th-route">Route</div>
                <div class="th-cell th-client">Client</div>
                <div class="th-cell th-type">Service</div>
            </div>

            @foreach($tripHistory as $trip)
            <div class="table-row">
                <div class="td-cell td-date">
                    <span class="date-badge">{{ $trip->date->format('M d') }}</span>
                    <span class="year-label">{{ $trip->date->format('Y') }}</span>
                </div>
                <div class="td-cell td-time">
                    {{ \Carbon\Carbon::parse($trip->pickup_time)->format('H:i') }}
                </div>
                <div class="td-cell td-route">
                    <span class="from-text">{{ $trip->pickup_location }}</span>
                    <i class="fas fa-arrow-right route-arr"></i>
                    <span class="to-text">{{ $trip->destination }}</span>
                </div>
                <div class="td-cell td-client">
                    <div class="client-name">{{ $trip->user->name }}</div>
                    <div class="client-phone">{{ $trip->user->phone_number ?? '—' }}</div>
                </div>
                <div class="td-cell td-type">
                    <span class="type-badge">{{ $trip->service_type ?? 'Standard' }}</span>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <style>
        :root { --maroon: #800000; --maroon-dark: #600000; --gold: #c9a84c; }

        .history-wrapper { max-width: 950px; margin: 0 auto; padding: 40px 20px; }

        .page-hero { position: relative; padding-left: 18px; margin-bottom: 40px; }
        .hero-bar { position: absolute; left: 0; top: 3px; bottom: 3px; width: 5px;
            background: linear-gradient(to bottom, var(--maroon), var(--gold)); border-radius: 4px; }
        .page-title { font-family: 'Outfit', sans-serif; font-size: 34px; font-weight: 900;
            color: #111827; margin: 0 0 6px 0; letter-spacing: -1px; }
        .page-sub { font-size: 15px; color: #6b7280; margin: 0; }

        .empty-state { text-align: center; padding: 80px 30px; background: white;
            border-radius: 24px; border: 2px dashed #e5e7eb; }
        .empty-state i { font-size: 40px; color: #d1d5db; margin-bottom: 20px; display: block; }
        .empty-state h3 { font-family: 'Outfit', sans-serif; font-size: 24px;
            font-weight: 900; color: #111827; margin: 0 0 10px 0; }
        .empty-state p { color: #6b7280; margin: 0; }

        .stats-row { display: flex; gap: 16px; margin-bottom: 32px; flex-wrap: wrap; }
        .stat-pill { display: flex; align-items: center; gap: 16px; background: white;
            padding: 20px 24px; border-radius: 16px; border: 1px solid #f3f4f6;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
        .stat-pill i { font-size: 24px; color: var(--maroon); opacity: 0.7; }
        .stat-num { display: block; font-family: 'Outfit', sans-serif; font-size: 22px;
            font-weight: 900; color: #111827; line-height: 1; }
        .stat-lbl { font-size: 11px; font-weight: 700; color: #9ca3af;
            text-transform: uppercase; letter-spacing: 1px; }

        .history-table-card { background: white; border-radius: 20px; border: 1px solid #f3f4f6;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03); overflow: hidden; }

        .table-header { display: grid;
            grid-template-columns: 110px 80px 1fr 180px 130px;
            background: #111827; padding: 0; }
        .th-cell { padding: 14px 16px; font-size: 10px; font-weight: 900;
            color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 1.5px; }

        .table-row { display: grid;
            grid-template-columns: 110px 80px 1fr 180px 130px;
            border-bottom: 1px solid #f9fafb; transition: background 0.2s; }
        .table-row:last-child { border-bottom: none; }
        .table-row:hover { background: #fafafa; }

        .td-cell { padding: 16px; display: flex; align-items: center; font-size: 14px; }

        .date-badge { font-family: 'Outfit', sans-serif; font-weight: 900; font-size: 15px; color: #111827; }
        .year-label { font-size: 11px; color: #9ca3af; font-weight: 600; margin-left: 4px; }

        .td-time { font-weight: 800; color: #374151; font-size: 15px; }

        .td-route { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
        .from-text { font-weight: 700; color: #111827; font-size: 13px; }
        .route-arr { color: #d1d5db; font-size: 10px; }
        .to-text { font-weight: 700; color: #374151; font-size: 13px; }

        .client-name { font-weight: 800; color: #111827; font-size: 13px; }
        .client-phone { font-size: 11px; color: #9ca3af; font-weight: 600; }

        .type-badge { display: inline-block; padding: 5px 10px; background: #f3f4f6;
            color: #374151; border-radius: 8px; font-size: 11px; font-weight: 800; }

        @media(max-width: 768px) {
            .table-header, .table-row { grid-template-columns: 1fr; }
            .th-cell, .td-cell { padding: 10px 16px; }
        }

        .fade-up { animation: fadeUp 0.5s cubic-bezier(0.16,1,0.3,1) both; }
        @keyframes fadeUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
    </style>
</x-driver-layout>

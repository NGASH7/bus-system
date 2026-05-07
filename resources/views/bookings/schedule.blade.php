<x-user-layout>
    <div class="schedule-page fade-up">
        <div class="schedule-head">
            <h1>Fleet Schedule & Availability</h1>
            <p>See all accepted bus schedules, expected return times, and which buses are free to book.</p>
        </div>

        <div class="summary-row">
            <div class="summary-card">
                <span class="label">Total Active Buses</span>
                <span class="value">{{ $buses->count() }}</span>
            </div>
            <div class="summary-card">
                <span class="label">Upcoming Confirmed Trips</span>
                <span class="value">{{ $upcomingBookings->count() }}</span>
            </div>
        </div>

        <div class="status-legend">
            <span class="legend-item legend-today">Today</span>
            <span class="legend-item legend-tomorrow">Tomorrow</span>
            <span class="legend-item legend-later">Later</span>
            <span class="legend-item legend-free">Available</span>
        </div>

        <div class="filter-bar">
            <button type="button" class="filter-btn active" data-filter="all">All</button>
            <button type="button" class="filter-btn" data-filter="timing-today">Today</button>
            <button type="button" class="filter-btn" data-filter="timing-tomorrow">Tomorrow</button>
            <button type="button" class="filter-btn" data-filter="timing-later">Later</button>
            <button type="button" class="filter-btn" data-filter="timing-free">Available</button>
        </div>

        <div class="fleet-grid">
            @forelse($buses as $entry)
                @php
                    $bus = $entry['bus'];
                    $next = $entry['next_booking'];
                    $freeAt = $entry['estimated_free_at'];
                    $timingClass = 'timing-free';
                    $timingLabel = 'Available';
                    if ($next) {
                        $daysAway = now()->startOfDay()->diffInDays($next->date->startOfDay(), false);
                        if ($daysAway <= 0) {
                            $timingClass = 'timing-today';
                            $timingLabel = 'Today';
                        } elseif ($daysAway === 1) {
                            $timingClass = 'timing-tomorrow';
                            $timingLabel = 'Tomorrow';
                        } else {
                            $timingClass = 'timing-later';
                            $timingLabel = 'Later';
                        }
                    }
                @endphp
                <div class="fleet-card {{ $timingClass }}" data-filter-class="{{ $timingClass }}">
                    <div class="fleet-card-top">
                        <div>
                            <h3>{{ $bus->plate_number }}</h3>
                            <p>{{ $bus->model }} • {{ $bus->capacity }} seats</p>
                        </div>
                        <span class="status {{ $next ? 'busy' : 'free' }}">{{ $timingLabel }}</span>
                    </div>

                    @if($next)
                        <div class="trip-block">
                            <p><strong>Next Trip:</strong> {{ $next->pickup_location }} -> {{ $next->destination }}</p>
                            <p><strong>Departure:</strong> {{ $next->date->format('D, d M Y') }} {{ $next->pickup_time ? \Carbon\Carbon::parse($next->pickup_time)->format('H:i') : '' }}</p>
                            <p><strong>Expected Return:</strong>
                                @if($next->return_date)
                                    {{ $next->return_date->format('D, d M Y') }} {{ $next->return_time ? \Carbon\Carbon::parse($next->return_time)->format('H:i') : '' }}
                                @elseif($freeAt)
                                    {{ $freeAt->format('D, d M Y H:i') }} <span class="hint">(estimated)</span>
                                @endif
                            </p>
                            @if($freeAt)
                                <p><strong>Likely Free From:</strong> {{ $freeAt->format('D, d M Y H:i') }}</p>
                            @endif
                        </div>
                    @else
                        <div class="trip-block">
                            <p>This bus has no confirmed upcoming trips and is currently open for booking.</p>
                        </div>
                    @endif

                    @if($entry['bookings']->isNotEmpty())
                        <details class="timeline">
                            <summary>View full upcoming timeline ({{ $entry['bookings']->count() }})</summary>
                            <ul>
                                @foreach($entry['bookings'] as $booking)
                                    <li>
                                        <span>{{ $booking->date->format('d M Y') }} {{ $booking->pickup_time ? \Carbon\Carbon::parse($booking->pickup_time)->format('H:i') : '' }}</span>
                                        <span>{{ $booking->pickup_location }} -> {{ $booking->destination }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </details>
                    @endif
                </div>
            @empty
                <div class="empty">
                    No active buses found.
                </div>
            @endforelse
        </div>
    </div>

    <style>
        .schedule-page { max-width: 1100px; margin: 0 auto; padding: 12px 8px 30px; }
        .schedule-head h1 { margin: 0; font-size: 30px; font-weight: 900; color: #111827; }
        .schedule-head p { margin: 8px 0 18px; color: #6b7280; }
        .summary-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 18px; }
        .summary-card { background: #fff; border: 1px solid #f1f5f9; border-radius: 14px; padding: 12px 14px; }
        .summary-card .label { display: block; font-size: 11px; color: #6b7280; font-weight: 700; text-transform: uppercase; }
        .summary-card .value { font-size: 24px; font-weight: 900; color: #111827; }
        .status-legend { display: flex; gap: 8px; align-items: center; margin: 0 0 14px; flex-wrap: wrap; }
        .legend-item { font-size: 10px; font-weight: 900; text-transform: uppercase; padding: 4px 10px; border-radius: 999px; border: 1px solid transparent; }
        .legend-today { background: #fee2e2; color: #b91c1c; border-color: #fecaca; }
        .legend-tomorrow { background: #fef3c7; color: #b45309; border-color: #fde68a; }
        .legend-later { background: #dbeafe; color: #1d4ed8; border-color: #bfdbfe; }
        .legend-free { background: #dcfce7; color: #15803d; border-color: #86efac; }
        .filter-bar { display: flex; flex-wrap: wrap; gap: 8px; margin: 0 0 14px; }
        .filter-btn { border: 1px solid #d1d5db; background: #fff; border-radius: 999px; padding: 7px 12px; font-size: 11px; font-weight: 800; color: #374151; cursor: pointer; }
        .filter-btn.active { background: #111827; color: #fff; border-color: #111827; }
        .fleet-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .fleet-card { background: #fff; border: 1px solid #eef2f7; border-radius: 16px; padding: 16px; border-left-width: 6px; }
        .fleet-card.timing-today { border-left-color: #dc2626; }
        .fleet-card.timing-tomorrow { border-left-color: #d97706; }
        .fleet-card.timing-later { border-left-color: #2563eb; }
        .fleet-card.timing-free { border-left-color: #16a34a; }
        .fleet-card-top { display: flex; justify-content: space-between; gap: 10px; align-items: flex-start; margin-bottom: 10px; }
        .fleet-card-top h3 { margin: 0; font-size: 20px; font-weight: 900; color: #111827; }
        .fleet-card-top p { margin: 3px 0 0; font-size: 12px; color: #6b7280; }
        .status { font-size: 10px; font-weight: 900; text-transform: uppercase; border-radius: 999px; padding: 4px 10px; }
        .status.busy { background: #fef3c7; color: #b45309; }
        .status.free { background: #dcfce7; color: #15803d; }
        .trip-block { background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 12px; padding: 10px 12px; font-size: 12px; color: #334155; }
        .trip-block p { margin: 0 0 6px; }
        .trip-block p:last-child { margin-bottom: 0; }
        .hint { color: #9ca3af; font-size: 11px; }
        .timeline { margin-top: 10px; font-size: 12px; }
        .timeline summary { cursor: pointer; font-weight: 700; color: #800000; }
        .timeline ul { margin: 8px 0 0; padding-left: 18px; color: #475569; display: grid; gap: 4px; }
        @media (max-width: 980px) { .fleet-grid, .summary-row { grid-template-columns: 1fr; } }
    </style>
    <script>
        const filterButtons = document.querySelectorAll('.filter-btn');
        const cards = document.querySelectorAll('.fleet-card');

        filterButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                filterButtons.forEach(function (b) { b.classList.remove('active'); });
                btn.classList.add('active');
                const filter = btn.dataset.filter;

                cards.forEach(function (card) {
                    if (filter === 'all') {
                        card.style.display = '';
                        return;
                    }
                    card.style.display = card.dataset.filterClass === filter ? '' : 'none';
                });
            });
        });
    </script>
</x-user-layout>


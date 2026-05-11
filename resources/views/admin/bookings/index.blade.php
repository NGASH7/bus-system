<x-admin-layout>
    <div class="admin-wrapper fade-up">
        <div class="admin-header-row">
            <div>
                <h1 class="admin-page-title">RENTAL BOOKINGS</h1>
                <p class="admin-page-subtitle">Manage fleet reservations and negotiate offers.</p>
            </div>
            <div class="admin-actions">
                <a href="{{ route('admin.bookings.history') }}" class="btn-outline-gold mr-3">
                    <i class="fas fa-history"></i> Booking History
                </a>
                <a href="{{ route('admin.schedule.index') }}" class="btn-outline-maroon">
                    <i class="fas fa-calendar-alt"></i> View Master Schedule
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="premium-card">
            <div class="premium-table-wrap">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Service & Bus</th>
                            <th>Route & Date</th>
                            <th>Offer</th>
                            <th>Status</th>
                            <th style="text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td>
                                    <div class="client-cell">
                                        <div class="client-avatar">
                                            {{ strtoupper(substr($booking->user->name, 0, 1)) }}
                                        </div>
                                        <div class="client-info">
                                            <div class="client-name">{{ $booking->user->name }}</div>
                                            <div class="client-email">{{ $booking->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="service-badge">
                                        {{ $booking->service_type ?? 'Standard' }}
                                    </div>
                                    <div class="bus-info">
                                        <i class="fas fa-bus"></i> {{ $booking->bus->plate_number ?? 'No Bus Assigned' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="route-info">{{ $booking->pickup_location }} → {{ $booking->destination }}
                                    </div>
                                    <div class="date-info">
                                        <i class="far fa-calendar-alt"></i> {{ $booking->date->format('M d, Y') }} @
                                        {{ \Carbon\Carbon::parse($booking->pickup_time)->format('H:i') }}
                                    </div>
                                </td>
                                <td>
                                    <div class="offer-amount">KES {{ number_format($booking->offered_price, 0) }}</div>
                                    @if($booking->counter_price)
                                        <div class="counter-amount">Counter: KES {{ number_format($booking->counter_price, 0) }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusClass = 'status-' . strtolower($booking->status);
                                        if (!in_array($booking->status, ['pending', 'accepted', 'rejected', 'countered'])) {
                                            $statusClass = 'status-default';
                                        }
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">
                                        {{ $booking->status }}
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn-manage">
                                        <i class="fas fa-eye"></i> Manage
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty-state">
                                    <i class="fas fa-calendar-times"></i>
                                    <p>No booking requests found in the system.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        .admin-wrapper {
            padding: 10px;
        }

        .admin-header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .admin-page-title {
            font-family: 'Outfit', sans-serif;
            font-size: 28px;
            font-weight: 900;
            color: #111827;
            letter-spacing: -0.5px;
            text-transform: uppercase;
            margin: 0 0 5px 0;
        }

        .admin-page-subtitle {
            color: #6b7280;
            margin: 0;
            font-size: 15px;
        }

        .btn-outline-maroon {
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .btn-outline-maroon i {
            color: var(--maroon);
        }

        .btn-outline-maroon:hover {
            background: #f9fafb;
            border-color: #d1d5db;
            color: #111827;
            transform: translateY(-1px);
        }

        .btn-outline-gold {
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .btn-outline-gold i {
            color: var(--gold);
        }

        .btn-outline-gold:hover {
            background: #fcf8eb;
            border-color: var(--gold);
            color: #111827;
            transform: translateY(-1px);
        }

        .mr-3 {
            margin-right: 12px;
        }

        .alert-success {
            background: #f0fdf4;
            border-left: 4px solid #22c55e;
            color: #15803d;
            padding: 16px 20px;
            border-radius: 0 12px 12px 0;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            margin-bottom: 24px;
            animation: pulse-success 2s infinite;
        }

        @keyframes pulse-success {
            0% {
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4);
            }

            70% {
                box-shadow: 0 0 0 6px rgba(34, 197, 94, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
            }
        }

        .premium-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            border: 1px solid #f3f4f6;
            overflow: hidden;
        }

        .premium-table-wrap {
            overflow-x: auto;
        }

        .premium-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .premium-table th {
            padding: 20px 30px;
            background: #f9fafb;
            font-size: 11px;
            font-weight: 900;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            border-bottom: 2px solid #f3f4f6;
        }

        .premium-table td {
            padding: 24px 30px;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
        }

        .premium-table tr:hover td {
            background: rgba(249, 250, 251, 0.5);
        }

        .client-cell {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .client-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #fff0f0;
            color: var(--maroon);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 16px;
        }

        .client-name {
            font-weight: 800;
            color: #111827;
            font-size: 15px;
            margin-bottom: 2px;
        }

        .client-email {
            font-size: 12px;
            color: #6b7280;
        }

        .service-badge {
            display: inline-block;
            padding: 4px 10px;
            background: #f3f4f6;
            color: #4b5563;
            font-size: 10px;
            font-weight: 800;
            border-radius: 6px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .bus-info {
            font-weight: 800;
            color: #1f2937;
            font-size: 14px;
        }

        .bus-info i {
            color: #9ca3af;
            margin-right: 6px;
        }

        .route-info {
            font-size: 14px;
            font-weight: 800;
            color: #1f2937;
            letter-spacing: -0.2px;
            margin-bottom: 4px;
        }

        .date-info {
            font-size: 12px;
            color: #6b7280;
            font-style: italic;
        }

        .date-info i {
            margin-right: 4px;
        }

        .offer-amount {
            font-family: 'Outfit', sans-serif;
            font-weight: 900;
            color: var(--maroon);
            font-size: 18px;
        }

        .counter-amount {
            font-size: 10px;
            font-weight: 800;
            color: var(--gold);
            text-transform: uppercase;
            margin-top: 4px;
        }

        .status-badge {
            padding: 6px 14px;
            border-radius: 99px;
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: inline-block;
        }

        .status-pending {
            background: #fef3c7;
            color: #b45309;
        }

        .status-accepted {
            background: #dcfce7;
            color: #15803d;
        }

        .status-rejected {
            background: #fee2e2;
            color: #b91c1c;
        }

        .status-countered {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .status-default {
            background: #f3f4f6;
            color: #374151;
        }

        .btn-manage {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            background: #111827;
            color: white;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 800;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-manage:hover {
            background: #000;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #9ca3af;
        }

        .empty-state i {
            font-size: 48px;
            opacity: 0.2;
            margin-bottom: 20px;
            display: block;
        }

        .empty-state p {
            font-weight: 600;
            font-size: 15px;
            margin: 0;
        }

        .fade-up {
            animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</x-admin-layout>
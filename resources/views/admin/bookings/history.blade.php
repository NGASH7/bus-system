<x-admin-layout>
    <div class="admin-wrapper fade-up">
        <div class="admin-header-row">
            <div>
                <h1 class="admin-page-title">BOOKING HISTORY</h1>
                <p class="admin-page-subtitle">Archived and finalized trip records.</p>
            </div>
            <div class="admin-actions">
                <a href="{{ route('admin.bookings.index') }}" class="btn-outline-maroon">
                    <i class="fas fa-arrow-left"></i> Back to Active
                </a>
            </div>
        </div>

        <div class="premium-card">
            <div class="premium-table-wrap">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Service & Bus</th>
                            <th>Route & Date</th>
                            <th>Status</th>
                            <th style="text-align: center;">Details</th>
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
                                <div class="route-info">{{ $booking->pickup_location }} → {{ $booking->destination }}</div>
                                <div class="date-info">
                                    <i class="far fa-calendar-alt"></i> {{ $booking->date->format('M d, Y') }}
                                </div>
                            </td>
                            <td>
                                @php
                                    $statusClass = 'status-' . strtolower($booking->status);
                                @endphp
                                <span class="status-badge {{ $statusClass }}">
                                    {{ $booking->status }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn-details">
                                    <i class="fas fa-file-alt"></i> View Record
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="empty-state">
                                <i class="fas fa-folder-open"></i>
                                <p>No archived bookings found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        .admin-wrapper { padding: 10px; }
        .admin-header-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .admin-page-title { font-family: 'Outfit', sans-serif; font-size: 28px; font-weight: 900; color: #111827; letter-spacing: -0.5px; text-transform: uppercase; margin: 0 0 5px 0; }
        .admin-page-subtitle { color: #6b7280; margin: 0; font-size: 15px; }

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
        }
        .btn-outline-maroon i { color: #800000; }
        .btn-outline-maroon:hover { background: #f9fafb; border-color: #d1d5db; color: #111827; }

        .premium-card { background: white; border-radius: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid #f3f4f6; overflow: hidden; }
        .premium-table { width: 100%; border-collapse: collapse; text-align: left; }
        .premium-table th { padding: 20px 30px; background: #f9fafb; font-size: 11px; font-weight: 900; color: #9ca3af; text-transform: uppercase; letter-spacing: 1.5px; border-bottom: 2px solid #f3f4f6; }
        .premium-table td { padding: 24px 30px; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }

        .client-cell { display: flex; align-items: center; gap: 16px; }
        .client-avatar { width: 44px; height: 44px; border-radius: 50%; background: #f3f4f6; color: #4b5563; display: flex; align-items: center; justify-content: center; font-weight: 800; }
        .client-name { font-weight: 800; color: #111827; font-size: 15px; }
        .client-email { font-size: 12px; color: #6b7280; }

        .service-badge { display: inline-block; padding: 4px 10px; background: #f3f4f6; color: #4b5563; font-size: 10px; font-weight: 800; border-radius: 6px; text-transform: uppercase; margin-bottom: 6px; }
        .bus-info { font-weight: 800; color: #1f2937; font-size: 14px; }
        .route-info { font-size: 14px; font-weight: 800; color: #1f2937; margin-bottom: 4px; }
        .date-info { font-size: 12px; color: #6b7280; font-style: italic; }

        .status-badge { padding: 6px 14px; border-radius: 99px; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; display: inline-block; }
        .status-accepted { background: #dcfce7; color: #15803d; }
        .status-rejected { background: #fee2e2; color: #b91c1c; }

        .btn-details {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: #f3f4f6;
            color: #374151;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 750;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-details:hover { background: #e5e7eb; color: #111827; }

        .empty-state { text-align: center; padding: 60px 20px; color: #9ca3af; }
        .empty-state i { font-size: 40px; opacity: 0.2; margin-bottom: 15px; display: block; }

        .fade-up { animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) both; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</x-admin-layout>

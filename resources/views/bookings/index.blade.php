<x-user-layout>
    <div class="user-bookings-wrapper fade-up">

        <div class="bookings-hero">
            <div class="hero-decorative-bar"></div>
            <h1 class="hero-title">MY BOOKINGS</h1>
            <p class="hero-subtitle">Track your reservations, review administrative responses, and manage
                counter-offers.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success mt-2">
                <div class="alert-icon"><i class="fas fa-check"></i></div>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error mt-2">
                <div class="alert-icon"><i class="fas fa-exclamation"></i></div>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="bookings-grid">
            @forelse($bookings as $booking)
                <div class="premium-ticket-card {{ 'border-' . strtolower($booking->status) }}">

                    <!-- Status Bar -->
                    <div class="card-status-bar">
                        @php
                            $statusClass = 'status-' . strtolower($booking->status);
                            if (!in_array($booking->status, ['pending', 'accepted', 'rejected', 'countered'])) {
                                $statusClass = 'status-default';
                            }
                        @endphp
                        <span class="status-badge {{ $statusClass }}">
                            <i class="fas fa-circle"></i> {{ str_replace('_', ' ', $booking->status) }}
                        </span>
                        <span class="booking-date">Requested on {{ $booking->created_at->format('M d, Y') }} | ID
                            #{{ 1000 + $booking->id }}</span>
                    </div>

                    <!-- Main Content -->
                    <div class="card-layout-split">

                        <!-- Left: Route Info -->
                        <div class="route-section">
                            <div class="service-tag">
                                <i class="fas fa-gem"></i> {{ $booking->service_type ?? 'Standard Lease' }}
                            </div>

                            <div class="route-visual-block">
                                <div class="route-point">
                                    <div class="point-icon bg-dark">A</div>
                                    <div class="point-details">
                                        <span class="point-label">From</span>
                                        <strong class="point-name">{{ $booking->pickup_location }}</strong>
                                    </div>
                                </div>
                                <div class="route-connector">
                                    <div class="connector-line"></div>
                                    <div class="connector-icon"><i class="fas fa-bus-alt"></i></div>
                                </div>
                                <div class="route-point pb-0">
                                    <div class="point-icon bg-maroon">B</div>
                                    <div class="point-details">
                                        <span class="point-label">To</span>
                                        <strong class="point-name">{{ $booking->destination }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="trip-time-box">
                                <div class="time-item">
                                    <i class="far fa-calendar-check text-maroon"></i>
                                    <div>
                                        <span class="text-mini">Departing on</span>
                                        <strong>{{ $booking->date->format('l, M d, Y') }}</strong> at
                                        <strong>{{ \Carbon\Carbon::parse($booking->pickup_time)->format('H:i') }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Financial & Actions -->
                        <div class="financial-section">
                            <div class="financial-split mb-4">
                                <div class="offer-box">
                                    <span class="box-label">Your Initial Offer</span>
                                    <div class="amount-value">KES {{ number_format($booking->offered_price, 0) }}</div>
                                </div>

                                @if($booking->counter_price)
                                    <div class="offer-box counter-box">
                                        <span class="box-label text-gold"><i class="fas fa-reply"></i> Admin
                                            Counter-Offer</span>
                                        <div class="amount-value text-gold">KES {{ number_format($booking->counter_price, 0) }}
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- User Actions (If Countered) -->
                            <div class="action-panel-container">
                                @if($booking->status === 'countered')
                                    <div class="action-footer bg-counter">
                                        <div class="action-icon-large text-blue"><i class="fas fa-handshake"></i></div>
                                        <div class="action-content">
                                            <p class="action-note">The fleet management team has reviewed your request and
                                                proposed a counter-offer. You may accept it to lock in your reservation.</p>
                                            <form action="{{ route('bookings.accept-counter', $booking->id) }}" method="POST"
                                                class="mt-3">
                                                @csrf
                                                <button type="submit" class="btn-ticket btn-dark w-100">
                                                    ACCEPT COUNTER-OFFER
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @elseif($booking->status === 'accepted')
                                    <div class="action-footer bg-success">
                                        <div class="action-icon-large text-green"><i class="fas fa-check-shield"></i></div>
                                        <div class="action-content">
                                            <p class="action-note text-green-dark">Your trip is locked in the master schedule.
                                                You have secured the vehicle for this journey.</p>
                                            
                                            @php
                                                $hasReceipt = \App\Models\Receipt::where('booking_id', $booking->id)->exists();
                                            @endphp
                                            
                                            @if($hasReceipt)
                                            <div class="mt-4">
                                                <a href="{{ route('receipts.view', $booking->id) }}" class="btn-view-receipt">
                                                    <i class="fas fa-file-invoice mr-2"></i> View Official Receipt
                                                </a>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @if($booking->bus && $booking->bus->driver)
                                        <div class="driver-contact-pill">
                                            <div class="driver-avatar-sm">
                                                {{ strtoupper(substr($booking->bus->driver->name, 0, 1)) }}</div>
                                            <div class="driver-info">
                                                <span class="driver-lbl">Your Driver</span>
                                                <strong class="driver-name">{{ $booking->bus->driver->name }}</strong>
                                                @if($booking->bus->driver->phone_number)
                                                    <a href="tel:{{ $booking->bus->driver->phone_number }}" class="driver-phone">
                                                        <i class="fas fa-phone-alt"></i> {{ $booking->bus->driver->phone_number }}
                                                    </a>
                                                @else
                                                    <span class="driver-phone" style="opacity:0.5;">Phone not provided</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @elseif($booking->status === 'rejected')
                                    <div class="action-footer bg-danger">
                                        <div class="action-icon-large text-red"><i class="fas fa-ban"></i></div>
                                        <div class="action-content">
                                            <p class="action-title text-red">Request Declined</p>
                                            <p class="action-note text-red-dark">Unfortunately, this requested booking could not
                                                be accommodated by our fleet at the moment.</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="action-footer bg-pending">
                                        <div class="action-icon-large text-orange"><i class="fas fa-hourglass-half"></i></div>
                                        <div class="action-content">
                                            <p class="action-title text-orange">Awaiting Review</p>
                                            <p class="action-note text-orange-dark">Your offer was successfully submitted and is
                                                currently pending review by the administrative team.</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state-premium">
                    <div class="empty-icon-wrap">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <h3>Your Travel Log is Empty</h3>
                    <p>You haven't requested any custom operations yet. We provide premium luxury charter services for any
                        occasion.</p>
                    <a href="{{ route('bookings.create') }}" class="btn-ticket btn-maroon mt-4">
                        PROPOSE A BOOKING <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            @endforelse
        </div>

    </div>

    <style>
        :root {
            --maroon: #800000;
            --maroon-dark: #600000;
            --maroon-light: #fbe5e5;
            --gold: #d4af37;
            --gold-light: #fef7e0;
            --gray-deep: #111827;
            --gray-med: #4b5563;
        }

        .user-bookings-wrapper {
            max-width: 1000px;
            margin: 0 auto;
            padding: 50px 20px;
        }

        .mt-2 {
            margin-top: 1rem;
        }

        .mt-3 {
            margin-top: 1.5rem;
        }

        .mt-4 {
            margin-top: 2rem;
        }

        .w-100 {
            width: 100%;
            display: block;
        }

        .pb-0 {
            padding-bottom: 0 !important;
            border-bottom: none !important;
        }

        .bookings-hero {
            position: relative;
            margin-bottom: 50px;
            padding-left: 20px;
        }

        .hero-decorative-bar {
            position: absolute;
            left: 0;
            top: 5px;
            bottom: 5px;
            width: 6px;
            background: linear-gradient(to bottom, var(--maroon), var(--gold));
            border-radius: 4px;
        }

        .hero-title {
            font-family: 'Outfit', sans-serif;
            font-size: 38px;
            font-weight: 900;
            color: var(--gray-deep);
            letter-spacing: -1px;
            margin: 0 0 10px 0;
            line-height: 1.1;
        }

        .hero-subtitle {
            font-size: 16px;
            color: var(--gray-med);
            margin: 0;
            font-weight: 500;
        }

        /* ALERTS */
        .alert {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            border-radius: 16px;
            font-weight: 800;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
        }

        .alert-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
            flex-shrink: 0;
        }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .alert-success .alert-icon {
            background: #16a34a;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .alert-error .alert-icon {
            background: #dc2626;
        }

        /* THE GRID */
        .bookings-grid {
            display: flex;
            flex-direction: column;
            gap: 40px;
        }

        /* PREMIUM TICKET CARD */
        .premium-ticket-card {
            background: white;
            border-radius: 24px;
            position: relative;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            overflow: hidden;
            border: 2px solid #f3f4f6;
        }

        .premium-ticket-card:hover {
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.08);
            transform: translateY(-4px);
        }

        /* Dynamic Border Coloring */
        .premium-ticket-card.border-pending {
            border-left: 6px solid #f59e0b;
        }

        .premium-ticket-card.border-accepted {
            border-left: 6px solid #10b981;
        }

        .premium-ticket-card.border-rejected {
            border-left: 6px solid #ef4444;
        }

        .premium-ticket-card.border-countered {
            border-left: 6px solid #3b82f6;
        }

        .card-status-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 24px;
            background: #fafafa;
            border-bottom: 1px solid #f3f4f6;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .status-badge i {
            font-size: 8px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 0.4;
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 0.4;
            }
        }

        .status-pending {
            background: #fffbeb;
            color: #b45309;
            border: 1px solid #fde68a;
        }

        .status-accepted {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }

        .status-rejected {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .status-countered {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        .status-default {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #e5e7eb;
        }

        .booking-date {
            font-size: 12px;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .card-layout-split {
            display: grid;
            grid-template-columns: 1fr 1.1fr;
        }

        @media(max-width: 850px) {
            .card-layout-split {
                grid-template-columns: 1fr;
            }
        }

        /* LEFT SECTION: ROUTE VISUAL */
        .route-section {
            padding: 32px;
            border-right: 1px dashed #e5e7eb;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%239C92AC' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        @media(max-width: 850px) {
            .route-section {
                border-right: none;
                border-bottom: 1px dashed #e5e7eb;
            }
        }

        .service-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 800;
            color: var(--maroon);
            background: var(--maroon-light);
            padding: 6px 14px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .route-visual-block {
            margin-bottom: 30px;
            position: relative;
        }

        .route-point {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            padding-bottom: 30px;
        }

        .point-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 900;
            font-size: 14px;
            position: relative;
            z-index: 2;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .bg-dark {
            background: var(--gray-deep);
        }

        .bg-maroon {
            background: var(--maroon);
        }

        .point-details {
            display: flex;
            flex-direction: column;
            padding-top: 2px;
        }

        .point-label {
            font-size: 10px;
            font-weight: 900;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 4px;
        }

        .point-name {
            font-family: 'Outfit', sans-serif;
            font-size: 22px;
            font-weight: 900;
            color: var(--gray-deep);
            letter-spacing: -0.5px;
            line-height: 1.1;
        }

        .route-connector {
            position: absolute;
            left: 20px;
            top: 40px;
            bottom: 40px;
            width: 2px;
            transform: translateX(-50%);
            z-index: 1;
        }

        .connector-line {
            height: 100%;
            border-left: 2px dashed #d1d5db;
        }

        .connector-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 24px;
            height: 24px;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 10px;
        }

        .trip-time-box {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 20px;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.02);
        }

        .time-item {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 14px;
            color: var(--gray-med);
        }

        .time-item i {
            font-size: 28px;
            opacity: 0.8;
        }

        .text-mini {
            display: block;
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            color: #9ca3af;
            letter-spacing: 1px;
            margin-bottom: 2px;
        }

        /* RIGHT SECTION: FINANCIALS & ACTION */
        .financial-section {
            padding: 32px;
            display: flex;
            flex-direction: column;
        }

        .financial-split {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .offer-box {
            background: #f9fafb;
            padding: 24px;
            border-radius: 16px;
            border: 1px solid #f3f4f6;
        }

        .counter-box {
            background: linear-gradient(135deg, var(--gold-light), #fff);
            border-color: #fde68a;
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.1);
        }

        .box-label {
            font-size: 10px;
            font-weight: 900;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            display: block;
            margin-bottom: 8px;
        }

        .text-gold {
            color: #b45309;
        }

        .amount-value {
            font-family: 'Outfit', sans-serif;
            font-size: 26px;
            font-weight: 900;
            color: var(--gray-deep);
            letter-spacing: -1px;
        }

        /* ACTION FOOTERS */
        .action-panel-container {
            margin-top: auto;
        }

        .action-footer {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            padding: 24px;
            border-radius: 20px;
        }

        .action-icon-large {
            font-size: 32px;
            opacity: 0.9;
        }

        .action-content {
            flex: 1;
        }

        .action-title {
            font-family: 'Outfit', sans-serif;
            font-size: 18px;
            font-weight: 900;
            margin: 0 0 6px 0;
            letter-spacing: -0.5px;
        }

        .action-note {
            font-size: 13px;
            font-weight: 600;
            margin: 0;
            line-height: 1.6;
        }

        .bg-counter {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
        }

        .text-blue {
            color: #1d4ed8;
        }

        .bg-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
        }

        .text-green {
            color: #15803d;
        }

        .text-green-dark {
            color: #166534;
        }

        /* DRIVER CONTACT PILL */
        .driver-contact-pill {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-top: 12px;
            padding: 14px 18px;
            background: white;
            border: 1.5px solid #a7f3d0;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.06);
        }

        .driver-avatar-sm {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: #111827;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 16px;
            flex-shrink: 0;
        }

        .driver-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .driver-lbl {
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #9ca3af;
        }

        .driver-name {
            font-size: 14px;
            font-weight: 900;
            color: #111827;
        }

        .driver-phone {
            font-size: 13px;
            font-weight: 700;
            color: #047857;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .driver-phone:hover {
            text-decoration: underline;
        }

        .bg-danger {
            background: #fef2f2;
            border: 1px solid #fecaca;
        }

        .text-red {
            color: #b91c1c;
        }

        .text-red-dark {
            color: #991b1b;
        }

        .bg-pending {
            background: #fffbeb;
            border: 1px solid #fde68a;
        }

        .text-orange {
            color: #b45309;
        }

        .text-orange-dark {
            color: #92400e;
        }

        .btn-ticket {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 16px 30px;
            border: none;
            border-radius: 12px;
            font-weight: 900;
            font-size: 14px;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
        }

        .btn-dark {
            background: var(--gray-deep);
            color: white;
        }

        .btn-dark:hover {
            background: black;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .btn-view-receipt {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            background: #15803d;
            color: white;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 800;
            text-decoration: none;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(21, 128, 61, 0.2);
        }

        .btn-view-receipt:hover {
            background: #166534;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(21, 128, 61, 0.3);
        }

        .btn-maroon {
            background: var(--maroon);
            color: white;
            text-decoration: none;
        }

        .btn-maroon:hover {
            background: var(--maroon-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(128, 0, 0, 0.2);
        }

        .empty-state-premium {
            text-align: center;
            padding: 100px 30px;
            background: white;
            border-radius: 32px;
            border: 2px dashed #e5e7eb;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .empty-icon-wrap {
            width: 100px;
            height: 100px;
            background: #f9fafb;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: #d1d5db;
            margin-bottom: 30px;
        }

        .empty-state-premium h3 {
            font-family: 'Outfit', sans-serif;
            font-size: 28px;
            font-weight: 900;
            color: var(--gray-deep);
            margin: 0 0 15px 0;
            letter-spacing: -0.5px;
        }

        .empty-state-premium p {
            color: var(--gray-med);
            margin: 0 0 30px 0;
            max-width: 500px;
            line-height: 1.6;
            font-size: 16px;
        }

        .fade-up {
            animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
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
</x-user-layout>
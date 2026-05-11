<x-admin-layout>
    <div class="admin-wrapper fade-up">
        
        <div class="admin-top-bar">
            <div>
                <a href="{{ route('admin.bookings.index') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i> Back to All Bookings
                </a>
                <h1 class="admin-page-title">MANAGE REQUEST</h1>
            </div>
            <div>
                @php
                    $statusClass = 'status-' . strtolower($booking->status);
                    if(!in_array($booking->status, ['pending', 'accepted', 'rejected', 'countered'])) {
                        $statusClass = 'status-default';
                    }
                @endphp
                <span class="status-badge large {{ $statusClass }}">
                    {{ $booking->status }}
                </span>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        <div class="split-layout">
            
            <!-- MAIN CONTENT COLUMN -->
            <div class="main-column">
                
                <!-- TRIP INFORMATION MODULE -->
                <div class="premium-card">
                    <div class="card-header border-b">
                        <h3 class="card-heading">Trip Information</h3>
                        <div class="badge-maroon">
                            <i class="fas fa-map-marker-alt"></i> Journey Details
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="route-visualizer">
                            <div class="route-nodes">
                                <div class="node node-start">A</div>
                                <div class="node-line"></div>
                                <div class="node node-end">B</div>
                            </div>
                            <div class="route-details">
                                <div class="route-stop">
                                    <span class="stop-label">Pickup Location</span>
                                    <div class="stop-name">{{ $booking->pickup_location }}</div>
                                    <div class="stop-time">Scheduled for {{ $booking->date->format('l, M d, Y') }} at {{ \Carbon\Carbon::parse($booking->pickup_time)->format('H:i') }}</div>
                                </div>
                                <div class="route-stop mt-loose">
                                    <span class="stop-label">Destination</span>
                                    <div class="stop-name">{{ $booking->destination }}</div>
                                    @if($booking->return_date)
                                    <div class="stop-time">Return scheduled for {{ $booking->return_date->format('M d, Y') }} @ {{ \Carbon\Carbon::parse($booking->return_time)->format('H:i') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="instructions-box">
                            <span class="box-label">Additional Instructions</span>
                            <p class="instructions-text">{{ $booking->details ?? 'No special requests provided.' }}</p>
                        </div>
                    </div>
                </div>

                <!-- NEGOTIATION CENTER -->
                @if($booking->status == 'pending' || $booking->status == 'countered')
                <div class="premium-card center-card p-loose">
                    <h3 class="card-heading mb-loose">Negotiation Center</h3>
                    
                    <div class="action-grid">
                        <form action="{{ route('admin.bookings.accept', $booking->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-action btn-accept">
                                <i class="fas fa-check-double icon-pop"></i>
                                ACCEPT OFFER
                            </button>
                        </form>
                        <form action="{{ route('admin.bookings.reject', $booking->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-action btn-reject">
                                <i class="fas fa-times-circle icon-pop"></i>
                                REJECT BOOKING
                            </button>
                        </form>
                    </div>

                    <div class="counter-section">
                        <span class="box-label block mb-tight">Propose Counter Offer</span>
                        <form action="{{ route('admin.bookings.counter', $booking->id) }}" method="POST" class="counter-form">
                            @csrf
                            <div class="input-wrapper">
                                <span class="currency-tag">KES</span>
                                <input type="number" name="counter_price" class="premium-input text-lg" placeholder="00,000" required>
                            </div>
                            <button type="submit" class="btn-action btn-counter">
                                SEND COUNTER
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>

            <!-- SIDEBAR COLUMN -->
            <div class="side-column">
                
                <!-- CLIENT CARD -->
                <div class="dark-card">
                    <span class="dark-label">The Client</span>
                    <div class="client-profile">
                        <div class="client-avatar-large">
                            {{ strtoupper(substr($booking->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="client-name-large">{{ $booking->user->name }}</div>
                            <div class="client-email-light">{{ $booking->user->email }}</div>
                        </div>
                    </div>
                    <div class="client-stats">
                        <div class="stat-row">
                            <span>Join Date</span>
                            <strong>{{ $booking->user->created_at->format('M Y') }}</strong>
                        </div>
                        <div class="stat-row">
                            <span>Success Rate</span>
                            <strong style="color: #4ade80;">100%</strong>
                        </div>
                    </div>
                </div>

                <!-- FINANCIAL CARD -->
                <div class="premium-card p-loose relative overflow-hidden">
                    <div class="card-accent-corner"></div>
                    <span class="box-label">Financial Offer</span>
                    <div class="finance-amount">KES {{ number_format($booking->offered_price, 0) }}</div>
                    <div class="finance-sub">PROPOSED RATE BY CLIENT</div>
                    
                    @if($booking->counter_price)
                    <div class="counter-display">
                        <span class="gold-label">Your Counter</span>
                        <div class="gold-amount">KES {{ number_format($booking->counter_price, 0) }}</div>
                    </div>
                    @endif
                </div>

                @if($booking->status === 'accepted')
                <div class="premium-card p-loose">
                    <span class="box-label">Payment Verification</span>
                    @php
                        $paymentStatus = $booking->payment_status ?? 'pending';
                    @endphp
                    <div class="payment-state-row">
                        <span class="payment-state-label">Current State</span>
                        <span class="payment-badge payment-{{ strtolower($paymentStatus) }}">{{ str_replace('_', ' ', $paymentStatus) }}</span>
                    </div>
                    <div class="payment-meta">
                        <div><strong>Method:</strong> {{ $booking->payment_method ?? 'Not selected yet' }}</div>
                        <div><strong>Payer Phone:</strong> {{ $booking->payer_phone ?? 'N/A' }}</div>
                        <div><strong>Reference:</strong> {{ $booking->payment_reference ?? 'N/A' }}</div>
                    </div>

                    @if(in_array($paymentStatus, ['pending_confirmation', 'processing', 'failed', 'pending']))
                        <div class="payment-actions">
                            <form method="POST" action="{{ route('admin.bookings.payment.confirm', $booking->id) }}">
                                @csrf
                                <button type="submit" class="btn-action btn-accept btn-payment">
                                    <i class="fas fa-check-circle"></i> Confirm Payment
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.bookings.payment.reject', $booking->id) }}">
                                @csrf
                                <button type="submit" class="btn-action btn-reject btn-payment">
                                    <i class="fas fa-times-circle"></i> Reject Payment
                                </button>
                            </form>
                        </div>
                    @else
                        <p class="payment-locked-note"><i class="fas fa-lock"></i> Payment already confirmed.</p>
                    @endif
                </div>
                @endif

                <!-- ASSET CARD -->
                <div class="premium-card p-loose">
                    <span class="box-label">Assigned Asset</span>
                    <div class="asset-info">
                        <div class="asset-icon">
                            <i class="fas fa-bus-alt"></i>
                        </div>
                        <div>
                            <div class="asset-name">{{ $booking->bus->plate_number ?? 'Awaiting Assign' }}</div>
                            <div class="asset-sub">{{ $booking->bus->brand ?? 'Fleet Unit' }} - {{ $booking->bus->capacity ?? '0' }} Seats</div>
                        </div>
                    </div>
                    <a href="{{ route('admin.buses.show', $booking->bus_id ?? 1) }}" class="btn-outline-block mt-loose">
                        VIEW VEHICLE FILE
                    </a>
                </div>

            </div>
        </div>

    </div>

    <style>
        :root {
            --maroon: #800000;
            --maroon-dark: #600000;
            --gold: #c9a84c;
            --maroon-pale: #fff0f0;
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

        .back-link {
            color: var(--maroon);
            font-weight: 700;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            margin-bottom: 8px;
        }

        .back-link:hover {
            text-decoration: underline;
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
        }

        .status-badge {
            padding: 8px 24px;
            border-radius: 99px;
            font-size: 12px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            display: inline-block;
        }

        .status-pending { background: #fef3c7; color: #b45309; }
        .status-accepted { background: #dcfce7; color: #15803d; }
        .status-rejected { background: #fee2e2; color: #b91c1c; }
        .status-countered { background: #dbeafe; color: #1d4ed8; }
        .status-default { background: #f3f4f6; color: #374151; }

        .split-layout {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 32px;
        }

        @media(max-width: 900px) {
            .split-layout { grid-template-columns: 1fr; }
        }

        .main-column, .side-column {
            display: flex;
            flex-direction: column;
            gap: 32px;
        }

        .premium-card {
            background: white;
            border-radius: 32px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.04);
            border: 1px solid #f3f4f6;
            overflow: hidden;
            position: relative;
        }

        .center-card {
            display: flex;
            flex-direction: column;
        }

        .card-header {
            padding: 24px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .border-b {
            border-bottom: 1px solid #f9fafb;
        }

        .card-heading {
            font-family: 'Outfit', sans-serif;
            font-weight: 900;
            font-size: 20px;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: -0.5px;
            margin: 0;
        }

        .badge-maroon {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--maroon);
            font-weight: 700;
            background: var(--maroon-pale);
            padding: 6px 16px;
            border-radius: 8px;
            font-size: 12px;
        }

        .card-body {
            padding: 40px;
        }

        .p-loose {
            padding: 32px;
        }

        .mb-loose { margin-bottom: 32px; }
        .mt-loose { margin-top: 32px; }
        .mb-tight { margin-bottom: 12px; }

        /* Route Visualizer */
        .route-visualizer {
            display: flex;
            align-items: flex-start;
            gap: 32px;
            margin-bottom: 48px;
        }

        .route-nodes {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            padding-top: 5px;
        }

        .node {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            color: white;
            font-size: 18px;
        }

        .node-start { background: #111827; }
        .node-end { background: var(--maroon); }

        .node-line {
            width: 0;
            height: 60px;
            border-left: 2px dashed #e5e7eb;
        }

        .route-details {
            flex: 1;
        }

        .stop-label {
            font-size: 10px;
            font-weight: 900;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            display: block;
            margin-bottom: 4px;
        }

        .stop-name {
            font-size: 24px;
            font-weight: 900;
            color: #111827;
            letter-spacing: -0.5px;
            margin-bottom: 4px;
        }

        .stop-time {
            font-size: 14px;
            color: #6b7280;
        }

        /* Instructions */
        .instructions-box {
            background: #f9fafb;
            border-radius: 16px;
            padding: 24px;
        }

        .box-label {
            font-size: 10px;
            font-weight: 900;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            display: block;
            margin-bottom: 8px;
        }

        .instructions-text {
            color: #374151;
            font-style: italic;
            border-left: 4px solid #e5e7eb;
            padding-left: 16px;
            margin: 0;
            line-height: 1.6;
        }

        /* Negotiation */
        .action-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .btn-action {
            width: 100%;
            padding: 24px;
            border-radius: 16px;
            font-weight: 900;
            font-size: 16px;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .icon-pop {
            font-size: 24px;
            transition: transform 0.3s;
        }

        .btn-action:hover .icon-pop {
            transform: scale(1.15);
        }

        .btn-accept {
            background: #16a34a;
            color: white;
            box-shadow: 0 4px 15px rgba(22, 163, 74, 0.2);
        }

        .btn-accept:hover {
            background: #15803d;
            box-shadow: 0 8px 25px rgba(22, 163, 74, 0.3);
        }

        .btn-reject {
            background: #fef2f2;
            color: #dc2626;
            border: 2px solid #fee2e2;
        }

        .btn-reject:hover {
            background: #dc2626;
            color: white;
            border-color: #dc2626;
        }

        .counter-section {
            margin-top: 32px;
            padding-top: 32px;
            border-top: 1px solid #f3f4f6;
        }

        .counter-form {
            display: flex;
            gap: 16px;
        }

        .input-wrapper {
            position: relative;
            flex: 1;
        }

        .currency-tag {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-weight: 900;
            color: #9ca3af;
            font-size: 16px;
        }

        .premium-input {
            width: 100%;
            padding: 16px 20px 16px 65px;
            background: #f9fafb;
            border: 2px solid transparent;
            border-radius: 12px;
            font-weight: 900;
            font-size: 18px;
            color: #111827;
            outline: none;
            font-family: inherit;
            transition: all 0.3s;
        }

        .premium-input:focus {
            border-color: var(--maroon);
            background: white;
        }

        .btn-counter {
            padding: 0 32px;
            background: var(--maroon);
            color: white;
            flex-direction: row;
        }

        .btn-counter:hover {
            background: var(--maroon-dark);
            box-shadow: 0 8px 20px rgba(128, 0, 0, 0.2);
        }

        /* Sidebar Styling */
        .dark-card {
            background: #111827;
            border-radius: 32px;
            padding: 32px;
            color: white;
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }

        .dark-label {
            font-size: 10px;
            font-weight: 900;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            display: block;
            margin-bottom: 20px;
        }

        .client-profile {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
        }

        .client-avatar-large {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            background: var(--maroon);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 24px;
        }

        .client-name-large {
            font-family: 'Outfit', sans-serif;
            font-size: 20px;
            font-weight: 900;
            letter-spacing: -0.5px;
        }

        .client-email-light {
            font-size: 12px;
            color: #9ca3af;
        }

        .client-stats {
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .stat-row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin-bottom: 12px;
        }
        .stat-row:last-child { margin-bottom: 0; }
        .stat-row span { color: #9ca3af; }

        .card-accent-corner {
            position: absolute;
            top: -20px;
            right: -20px;
            width: 80px;
            height: 80px;
            background: var(--maroon);
            transform: rotate(45deg);
        }

        .finance-amount {
            font-family: 'Outfit', sans-serif;
            font-size: 32px;
            font-weight: 900;
            color: var(--maroon);
            letter-spacing: -1px;
            margin: 4px 0;
        }

        .finance-sub {
            font-size: 11px;
            font-weight: 800;
            color: #6b7280;
        }

        .counter-display {
            margin-top: 24px;
            padding: 16px;
            background: rgba(201, 168, 76, 0.05);
            border: 1px solid rgba(201, 168, 76, 0.2);
            border-radius: 12px;
        }

        .gold-label {
            font-size: 10px;
            font-weight: 900;
            color: var(--gold);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            display: block;
            margin-bottom: 4px;
        }

        .gold-amount {
            font-size: 24px;
            font-weight: 900;
            color: var(--gold);
        }

        .asset-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .asset-icon {
            width: 48px;
            height: 48px;
            background: #f9fafb;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 20px;
        }

        .asset-name {
            font-weight: 900;
            color: #111827;
            text-transform: uppercase;
            font-size: 16px;
        }

        .asset-sub {
            font-size: 11px;
            font-weight: 800;
            color: #6b7280;
        }

        .btn-outline-block {
            display: block;
            width: 100%;
            padding: 14px;
            text-align: center;
            border: 2px solid #f3f4f6;
            border-radius: 12px;
            font-weight: 900;
            font-size: 12px;
            color: #9ca3af;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-outline-block:hover {
            border-color: var(--maroon);
            color: var(--maroon);
        }

        .payment-state-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .payment-state-label {
            font-size: 11px;
            font-weight: 900;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .payment-badge {
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            border-radius: 999px;
            padding: 4px 10px;
        }

        .payment-pending, .payment-pending_confirmation, .payment-processing {
            background: #fffbeb;
            color: #b45309;
        }

        .payment-paid {
            background: #dcfce7;
            color: #15803d;
        }

        .payment-failed, .payment-cancelled {
            background: #fee2e2;
            color: #b91c1c;
        }

        .payment-meta {
            font-size: 12px;
            color: #4b5563;
            display: grid;
            gap: 6px;
            margin-bottom: 12px;
        }

        .payment-actions {
            display: grid;
            gap: 10px;
        }

        .btn-payment {
            padding: 12px;
            font-size: 13px;
            flex-direction: row;
            justify-content: center;
        }

        .payment-locked-note {
            margin: 0;
            font-size: 12px;
            font-weight: 700;
            color: #15803d;
            display: flex;
            align-items: center;
            gap: 6px;
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

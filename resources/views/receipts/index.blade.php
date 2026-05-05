<x-user-layout>
    <div class="user-receipts-wrapper fade-up">
        
        <div class="receipts-hero">
            <div class="hero-decorative-bar"></div>
            <h1 class="hero-title">MY RECEIPTS</h1>
            <p class="hero-subtitle">Access and download your official payment records for all completed journeys.</p>
        </div>

        <div class="receipts-grid">
            @forelse($receipts as $receipt)
                <div class="premium-receipt-card">
                    <div class="receipt-header">
                        <div class="receipt-id">
                            <span class="label">Receipt No</span>
                            <span class="value">{{ $receipt->receipt_no }}</span>
                        </div>
                        <div class="receipt-date">
                            <span class="label">Issued On</span>
                            <span class="value">{{ $receipt->receipt_date->format('M d, Y') }}</span>
                        </div>
                    </div>

                    <div class="receipt-body">
                        <div class="trip-summary">
                            <div class="summary-icon">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </div>
                            <div class="summary-text">
                                <strong class="route-display">{{ $receipt->trip_route }}</strong>
                                <span class="vehicle-display">Vehicle: {{ $receipt->bus_number }}</span>
                            </div>
                        </div>
                        
                        <div class="amount-display">
                            <span class="currency">KES</span>
                            <span class="price">{{ number_format($receipt->amount, 0) }}</span>
                        </div>
                    </div>

                    <div class="receipt-actions">
                        <a href="{{ route('receipts.view', $receipt->booking_id) }}" class="btn-view-full">
                            <i class="fas fa-eye mr-2"></i> View & Download
                        </a>
                        <div class="payment-tag">
                            <i class="fas fa-check-circle"></i> Paid via {{ $receipt->payment_method }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-receipts">
                    <div class="empty-icon-box">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <h3>No Receipts Found</h3>
                    <p>When your bookings are confirmed and payment is verified, your official receipts will appear here.</p>
                    <a href="{{ route('bookings.index') }}" class="btn-check-bookings">Check Booking Status</a>
                </div>
            @endforelse
        </div>

    </div>

    <style>
        :root {
            --maroon: #800000;
            --maroon-dark: #600000;
            --gold: #c9a84c;
            --text-main: #1f2937;
            --text-muted: #6b7280;
        }

        .user-receipts-wrapper {
            max-width: 1000px;
            margin: 0 auto;
            padding: 50px 20px;
        }

        .receipts-hero {
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
            color: var(--text-main);
            letter-spacing: -1px;
            margin: 0 0 10px 0;
            line-height: 1.1;
        }

        .hero-subtitle {
            font-size: 16px;
            color: var(--text-muted);
            margin: 0;
            font-weight: 500;
        }

        .receipts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(450px, 1fr));
            gap: 30px;
        }

        @media (max-width: 600px) {
            .receipts-grid {
                grid-template-columns: 1fr;
            }
        }

        .premium-receipt-card {
            background: white;
            border-radius: 24px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            border: 1px solid #f3f4f6;
            transition: all 0.3s;
        }

        .premium-receipt-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.06);
            border-color: var(--gold);
        }

        .receipt-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px dashed #e5e7eb;
        }

        .label {
            display: block;
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            color: #9ca3af;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        .value {
            font-family: 'Outfit', sans-serif;
            font-size: 16px;
            font-weight: 900;
            color: var(--text-main);
        }

        .receipt-body {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .trip-summary {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .summary-icon {
            width: 45px;
            height: 45px;
            background: rgba(128, 0, 0, 0.05);
            color: var(--maroon);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .summary-text {
            display: flex;
            flex-direction: column;
        }

        .route-display {
            font-size: 15px;
            font-weight: 800;
            color: var(--text-main);
        }

        .vehicle-display {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .amount-display {
            text-align: right;
        }

        .currency {
            font-size: 12px;
            font-weight: 900;
            color: var(--gold);
            margin-right: 4px;
        }

        .price {
            font-family: 'Outfit', sans-serif;
            font-size: 24px;
            font-weight: 900;
            color: var(--maroon);
        }

        .receipt-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-view-full {
            background: var(--maroon);
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 800;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-view-full:hover {
            background: var(--maroon-dark);
            transform: scale(1.02);
        }

        .payment-tag {
            font-size: 11px;
            font-weight: 800;
            color: #059669;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* EMPTY STATE */
        .empty-receipts {
            grid-column: 1 / -1;
            padding: 80px 40px;
            text-align: center;
            background: white;
            border-radius: 30px;
            border: 2px dashed #e5e7eb;
        }

        .empty-icon-box {
            font-size: 60px;
            color: #e5e7eb;
            margin-bottom: 20px;
        }

        .empty-receipts h3 {
            font-size: 24px;
            font-weight: 900;
            color: var(--text-main);
            margin-bottom: 10px;
        }

        .empty-receipts p {
            color: var(--text-muted);
            max-width: 400px;
            margin: 0 auto 30px;
        }

        .btn-check-bookings {
            display: inline-block;
            background: var(--maroon);
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 800;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-check-bookings:hover {
            background: var(--maroon-dark);
        }

        .fade-up {
            animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-user-layout>

<x-admin-layout>
    <div class="receipt-create-wrapper fade-up">
        <div class="max-w-4xl mx-auto">
            
            <div class="welcome-header-minimal mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="page-title">Generate New Receipt</h1>
                        <p class="text-sm text-gray-500 mt-1">Fill in the details below to create an official payment record.</p>
                    </div>
                    <a href="{{ route('admin.dashboard') }}" class="btn-back">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                    </a>
                </div>
            </div>

            <div class="receipt-form-card">
                <form action="{{ route('admin.receipts.store') }}" method="POST">
                    @csrf
                    @if(isset($prefill['booking_id']))
                        <input type="hidden" name="booking_id" value="{{ $prefill['booking_id'] }}">
                    @endif
                    
                    <div class="form-section-title">
                        <i class="fas fa-user-tag mr-2"></i> Customer Information
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label for="customer_name" class="premium-label">Customer Full Name</label>
                            <input type="text" name="customer_name" id="customer_name" class="premium-input" placeholder="e.g. Michael Kanyingi" value="{{ old('customer_name', $prefill['customer_name'] ?? '') }}" required>
                        </div>
                        <div>
                            <label for="customer_phone" class="premium-label">Phone Number</label>
                            <input type="text" name="customer_phone" id="customer_phone" class="premium-input" placeholder="e.g. +254 712 345 678" value="{{ old('customer_phone', $prefill['customer_phone'] ?? '') }}" required>
                        </div>
                    </div>

                    <div class="form-section-title">
                        <i class="fas fa-bus mr-2"></i> Trip & Payment Details
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label for="bus_number" class="premium-label">Select Bus</label>
                            <select name="bus_number" id="bus_number" class="premium-input" required>
                                <option value="">Choose a Vehicle</option>
                                @foreach($buses as $bus)
                                    <option value="{{ $bus->plate_number }}" {{ (old('bus_number', $prefill['bus_number'] ?? '') == $bus->plate_number) ? 'selected' : '' }}>
                                        {{ $bus->plate_number }} 
                                        @if($bus->model) — {{ $bus->model }} @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="trip_route" class="premium-label">Route / Service Description</label>
                            <input type="text" name="trip_route" id="trip_route" class="premium-input" placeholder="e.g. Nairobi to Mombasa" value="{{ old('trip_route', $prefill['trip_route'] ?? '') }}" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div>
                            <label for="amount" class="premium-label">Amount Paid (KES)</label>
                            <div class="amount-input-wrap">
                                <span class="currency-prefix">KES</span>
                                <input type="number" name="amount" id="amount" class="premium-input has-prefix" placeholder="0.00" step="0.01" value="{{ old('amount', $prefill['amount'] ?? '') }}" required>
                            </div>
                        </div>
                        <div>
                            <label for="payment_method" class="premium-label">Payment Method</label>
                            <select name="payment_method" id="payment_method" class="premium-input" required>
                                <option value="M-Pesa">M-Pesa</option>
                                <option value="Cash">Cash</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Cheque">Cheque</option>
                            </select>
                        </div>
                        <div>
                            <label for="receipt_date" class="premium-label">Date of Payment</label>
                            <input type="date" name="receipt_date" id="receipt_date" class="premium-input" 
                                value="{{ date('Y-m-d') }}" 
                                max="{{ date('Y-m-d') }}" 
                                required>
                        </div>
                    </div>

                    <div class="form-actions mt-10">
                        <button type="submit" class="btn-generate-receipt">
                            <i class="fas fa-file-invoice mr-2"></i> Process & Generate Receipt
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        :root {
            --maroon: #800000;
            --maroon-dark: #600000;
            --gold: #c9a84c;
            --text-main: #1f2937;
        }

        .receipt-create-wrapper {
            padding: 20px 0;
        }

        .welcome-header-minimal {
            border-bottom: 2px solid #edf2f7;
            padding-bottom: 20px;
        }

        .page-title {
            font-family: 'Outfit', sans-serif;
            font-size: 28px;
            font-weight: 850;
            color: var(--text-main);
            margin: 0;
        }

        .btn-back {
            font-size: 13px;
            font-weight: 700;
            color: var(--maroon);
            text-decoration: none;
            padding: 8px 16px;
            border: 2px solid #eee;
            border-radius: 12px;
            transition: all 0.2s;
        }

        .btn-back:hover {
            border-color: var(--maroon);
            background: #fffafa;
        }

        /* FORM CARD */
        .receipt-form-card {
            background: white;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.04);
            border: 1px solid #f1f5f9;
        }

        .form-section-title {
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 900;
            color: var(--maroon);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
        }

        .premium-label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #4b5563;
            margin-bottom: 8px;
        }

        .premium-input {
            width: 100%;
            border-radius: 12px;
            border: 1.5px solid #e5e7eb;
            padding: 12px 16px;
            font-size: 15px;
            transition: all 0.2s;
            outline: none !important;
        }

        .premium-input:focus {
            border-color: var(--maroon);
            box-shadow: 0 0 0 4px rgba(128, 0, 0, 0.05);
        }

        .amount-input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .currency-prefix {
            position: absolute;
            left: 14px;
            font-size: 13px;
            font-weight: 800;
            color: #9ca3af;
        }

        .premium-input.has-prefix {
            padding-left: 50px;
        }

        .btn-generate-receipt {
            width: 100%;
            background: var(--maroon);
            color: white;
            padding: 18px;
            border-radius: 16px;
            font-weight: 850;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 10px 25px rgba(128, 0, 0, 0.2);
        }

        .btn-generate-receipt:hover {
            background: var(--maroon-dark);
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(128, 0, 0, 0.3);
        }

        .fade-up {
            animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-admin-layout>

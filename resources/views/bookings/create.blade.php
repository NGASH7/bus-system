<x-user-layout>
    <div class="booking-wrapper fade-up">
        
        <div class="booking-hero">
            <span class="hero-badge">Premium Charter Service</span>
            <h1 class="hero-title">PROPOSE A BOOKING</h1>
            <p class="hero-subtitle">Fill out the details of your trip to receive a custom offer from our fleet management team. We guarantee luxury and safety for every mile.</p>
        </div>

        <form action="{{ route('bookings.store') }}" method="POST" class="booking-form-card">
            @csrf

            <!-- SECTION 1: Service & Vehicle -->
            <div class="form-section">
                <div class="section-header">
                    <div class="step-number">1</div>
                    <h2>Service Requirements</h2>
                </div>

                <div class="input-grid">
                    <div class="input-group">
                        <label>Type of Service <span class="required">*</span></label>
                        <select name="service_type" required class="premium-input">
                            <option value="">Select an occasion...</option>
                            <option value="School Trip">Educational / School Trip</option>
                            <option value="Wedding Transportation">Wedding Transportation</option>
                            <option value="Corporate Retreat">Corporate Event / Retreat</option>
                            <option value="Funeral / Memorial">Funeral / Memorial</option>
                            <option value="Sports Team Travel">Sports Team Travel</option>
                            <option value="Private Tour">Private Tour Group</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label>Preferred Vehicle (Optional)</label>
                        <select name="bus_id" class="premium-input">
                            <option value="">Any available vehicle</option>
                            @foreach($buses as $bus)
                                <option value="{{ $bus->id }}" {{ $selectedBus && $selectedBus->id == $bus->id ? 'selected' : '' }}>
                                    {{ $bus->plate_number }} ({{ $bus->brand ?? 'Standard' }} - {{ $bus->capacity ?? 'N/A' }} Seats)
                                </option>
                            @endforeach
                        </select>
                        <p class="input-hint">Leave blank and we'll assign the best fit.</p>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: Logistics -->
            <div class="form-section bg-light">
                <div class="section-header">
                    <div class="step-number">2</div>
                    <h2>Route & Timing</h2>
                </div>

                <div class="logistics-wrap">
                    <!-- Locations -->
                    <div class="route-grid">
                        <div class="route-divider hidden-mobile">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                        
                        <div class="input-group">
                            <label><i class="fas fa-map-marker-alt text-maroon"></i> Pickup Location <span class="required">*</span></label>
                            <input type="text" name="pickup_location" required placeholder="E.g., Mwigito Campus Main Gate" class="premium-input border-focus">
                        </div>

                        <div class="input-group">
                            <label><i class="fas fa-flag-checkered text-maroon"></i> Destination <span class="required">*</span></label>
                            <input type="text" name="destination" required placeholder="E.g., National Museum, Nairobi" class="premium-input border-focus">
                        </div>
                    </div>

                    <!-- Scheduling -->
                    <div class="schedule-box">
                        <div class="schedule-grid">
                            <!-- Departure -->
                            <div class="schedule-block">
                                <h3>Outbound Journey</h3>
                                <div class="time-grid">
                                    <div class="input-group">
                                        <label>Date <span class="required">*</span></label>
                                        <input type="date" name="date" required min="{{ date('Y-m-d') }}" class="premium-input bg-gray">
                                    </div>
                                    <div class="input-group">
                                        <label>Time <span class="required">*</span></label>
                                        <input type="time" name="pickup_time" required class="premium-input bg-gray">
                                    </div>
                                </div>
                            </div>
                            <!-- Return -->
                            <div class="schedule-block">
                                <h3>Return Journey (Optional)</h3>
                                <div class="time-grid">
                                    <div class="input-group">
                                        <label>Date</label>
                                        <input type="date" name="return_date" min="{{ date('Y-m-d') }}" class="premium-input bg-gray">
                                    </div>
                                    <div class="input-group">
                                        <label>Time</label>
                                        <input type="time" name="return_time" class="premium-input bg-gray">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: Offer & Final Details -->
            <div class="form-section">
                <div class="section-header">
                    <div class="step-number bg-maroon">3</div>
                    <h2>Your Proposal</h2>
                </div>

                <div class="proposal-grid">
                    <!-- Pricing -->
                    <div class="pricing-col">
                        <div class="pricing-box">
                            <div class="pricing-bg-icon">
                                <i class="fas fa-handshake"></i>
                            </div>
                            <label class="pricing-label">Proposed Budget <span class="required">*</span></label>
                            <div class="currency-input-wrap">
                                <span class="currency-symbol">KES</span>
                                <input type="number" name="offered_price" required min="1000" placeholder="00,000" class="input-currency">
                            </div>
                            <p class="pricing-hint">Propose a fair price for your journey. Our admin will review and can confirm or send a counter-offer.</p>
                        </div>
                    </div>

                    <!-- Additional Details -->
                    <div class="details-col">
                        <div class="input-group" style="height: 100%;">
                            <label>Additional Requirements</label>
                            <textarea name="details" rows="5" placeholder="Do you have special luggage requirements? Need a specific route? Let us know here..." class="premium-input textarea-custom"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit Action -->
                <div class="form-footer">
                    <p class="footer-note">
                        <i class="fas fa-shield-alt"></i> By submitting this offer, you agree to our <a href="#" class="text-maroon">carriage policies</a>. No payment is required until the offer is firmly accepted.
                    </p>
                    <button type="submit" class="btn-submit">
                        SUBMIT OFFER 
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <style>
        :root {
            --maroon: #800000;
            --maroon-dark: #600000;
            --gold: #c9a84c;
            --maroon-pale: #fff0f0;
        }

        .booking-wrapper {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .booking-hero {
            text-align: center;
            margin-bottom: 40px;
        }

        .hero-badge {
            display: inline-block;
            padding: 6px 16px;
            background: var(--maroon-pale);
            color: var(--maroon);
            font-weight: 800;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            border-radius: 99px;
            margin-bottom: 16px;
        }

        .hero-title {
            font-family: 'Outfit', sans-serif;
            font-size: 42px;
            font-weight: 900;
            color: #111827;
            letter-spacing: -1px;
            margin: 0 0 16px 0;
            line-height: 1.1;
        }

        .hero-subtitle {
            font-size: 18px;
            color: #6b7280;
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .booking-form-card {
            background: white;
            border-radius: 32px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.05);
            border: 1px solid #f3f4f6;
            overflow: hidden;
        }

        .form-section {
            padding: 48px;
            border-bottom: 1px solid #f3f4f6;
        }

        .form-section:last-child {
            border-bottom: none;
        }

        .bg-light {
            background: #fdfdfd;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 32px;
        }

        .step-number {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            background: #111827;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 20px;
        }

        .step-number.bg-maroon {
            background: var(--maroon);
        }

        .section-header h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 24px;
            font-weight: 900;
            color: #111827;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .input-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 32px;
        }

        @media(max-width: 768px) {
            .input-grid { grid-template-columns: 1fr; }
        }

        .input-group label {
            display: block;
            font-size: 10px;
            font-weight: 900;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 8px;
        }

        .required { color: #ef4444; }

        .premium-input {
            width: 100%;
            padding: 16px 20px;
            background: #f9fafb;
            border: 2px solid transparent;
            border-radius: 16px;
            font-size: 15px;
            font-weight: 700;
            color: #374151;
            font-family: inherit;
            outline: none;
            transition: all 0.2s;
        }

        .premium-input:focus {
            border-color: var(--maroon);
            background: white;
        }

        .premium-input.border-focus {
            background: white;
            border-color: #e5e7eb;
        }
        .premium-input.border-focus:focus {
            border-color: var(--maroon);
        }

        .input-hint {
            font-size: 11px;
            font-weight: 600;
            color: #6b7280;
            margin-top: 6px;
            margin-left: 8px;
        }

        select.premium-input {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 16px;
            padding-right: 48px;
        }

        /* Route & Logistics */
        .logistics-wrap {
            display: flex;
            flex-direction: column;
            gap: 32px;
        }

        .route-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 32px;
            position: relative;
        }

        @media(max-width: 768px) {
            .route-grid { grid-template-columns: 1fr; }
            .hidden-mobile { display: none !important; }
        }

        .route-divider {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 40px;
            height: 40px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            z-index: 10;
        }

        .text-maroon { color: var(--maroon); }

        .schedule-box {
            background: white;
            padding: 24px;
            border-radius: 24px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 10px rgba(0,0,0,0.01);
        }

        .schedule-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 32px;
        }

        @media(max-width: 768px) {
            .schedule-grid { grid-template-columns: 1fr; }
        }

        .schedule-block h3 {
            font-weight: 900;
            color: #111827;
            font-size: 14px;
            margin: 0 0 16px 0;
            padding-bottom: 8px;
            border-bottom: 1px solid #f3f4f6;
        }

        .time-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        /* Proposal */
        .proposal-grid {
            display: grid;
            grid-template-columns: 2fr 3fr;
            gap: 32px;
        }

        @media(max-width: 768px) {
            .proposal-grid { grid-template-columns: 1fr; }
        }

        .pricing-box {
            background: var(--maroon-pale);
            padding: 24px;
            border-radius: 24px;
            border: 1px solid rgba(128,0,0,0.1);
            position: relative;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .pricing-bg-icon {
            position: absolute;
            right: -20px;
            bottom: -20px;
            font-size: 100px;
            color: var(--maroon);
            opacity: 0.05;
        }

        .pricing-label {
            font-size: 10px;
            font-weight: 900;
            color: var(--maroon);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 12px;
            position: relative;
            z-index: 10;
        }

        .currency-input-wrap {
            position: relative;
            z-index: 10;
        }

        .currency-symbol {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-weight: 900;
            color: #6b7280;
            font-size: 16px;
        }

        .input-currency {
            width: 100%;
            padding: 16px 20px 16px 65px;
            background: white;
            border: none;
            border-radius: 16px;
            font-weight: 900;
            font-size: 24px;
            color: #111827;
            outline: none;
            transition: all 0.2s;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            font-family: inherit;
        }

        .input-currency:focus {
            box-shadow: 0 0 0 2px var(--maroon);
        }

        .pricing-hint {
            font-size: 11px;
            font-weight: 600;
            color: var(--maroon);
            margin: 12px 0 0 0;
            position: relative;
            z-index: 10;
            line-height: 1.5;
        }

        .textarea-custom {
            height: 100%;
            resize: none;
        }

        /* Footer */
        .form-footer {
            margin-top: 48px;
            padding-top: 32px;
            border-top: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        @media(max-width: 768px) {
            .form-footer {
                flex-direction: column;
                text-align: center;
            }
        }

        .footer-note {
            font-size: 12px;
            color: #9ca3af;
            max-width: 400px;
            margin: 0;
            line-height: 1.6;
        }

        .footer-note a {
            text-decoration: underline;
        }

        .footer-note i {
            margin-right: 4px;
        }

        .btn-submit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 20px 40px;
            background: #111827;
            color: white;
            border: none;
            border-radius: 20px;
            font-weight: 900;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-submit:hover {
            background: #000;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .btn-submit i {
            transition: transform 0.2s;
        }

        .btn-submit:hover i {
            transform: translateX(4px);
        }

        .fade-up {
            animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        input[type="date"]::-webkit-calendar-picker-indicator,
        input[type="time"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
            opacity: 0.6;
            transition: 0.2s;
        }
        input[type="date"]::-webkit-calendar-picker-indicator:hover,
        input[type="time"]::-webkit-calendar-picker-indicator:hover {
            opacity: 1;
        }
    </style>
</x-user-layout>

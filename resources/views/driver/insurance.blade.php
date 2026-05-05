<x-driver-layout>
    <div class="insurance-wrapper fade-up">

        {{-- ===== EXPIRY NOTIFICATION BANNER (Insurance Focus) ===== --}}
        @if(isset($insuranceExpiryStatus) && $insuranceExpiryStatus === 'expired')
            <div class="notif-banner notif-expired">
                <div class="notif-icon"><i class="fas fa-skull-crossbones"></i></div>
                <div class="notif-body">
                    <strong>INSURANCE HAS EXPIRED</strong>
                    <p>Your vehicle insurance coverage is no longer active. You are not authorized to operate. Contact fleet
                        management immediately to renew the policy.</p>
                </div>
            </div>
        @elseif(isset($insuranceExpiryStatus) && $insuranceExpiryStatus === 'critical')
            <div class="notif-banner notif-critical">
                <div class="notif-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="notif-body">
                    <strong>URGENT: Insurance expires in {{ abs($insuranceDaysLeft) }}
                        day{{ abs($insuranceDaysLeft) == 1 ? '' : 's' }}!</strong>
                    <p>Your insurance policy is expiring very soon. Notify the fleet administrator immediately to avoid
                        coverage lapse.</p>
                </div>
            </div>
        @elseif(isset($insuranceExpiryStatus) && $insuranceExpiryStatus === 'warning')
            <div class="notif-banner notif-warning">
                <div class="notif-icon"><i class="fas fa-clock"></i></div>
                <div class="notif-body">
                    <strong>Insurance Expiry Notice: {{ $insuranceDaysLeft }} days remaining</strong>
                    <p>Your motor vehicle insurance is approaching its expiry date. Contact your administrator to process
                        renewal.</p>
                </div>
            </div>
        @endif

        {{-- ===== PAGE HERO ===== --}}
        <div class="page-hero">
            <div class="hero-bar"></div>
            <h1 class="page-title">MY INSURANCE</h1>
            <p class="page-sub">Your official vehicle insurance credentials. Coverage is managed by your fleet
                administrator.</p>
        </div>

        <div class="insurance-layout">

            {{-- ===== INSURANCE CARD ===== --}}
            <div class="insurance-card-col">
                <div class="insurance-card {{ $insuranceExpiryStatus ?? 'none' }}">
                    <div class="ic-header">
                        <div class="ic-flag">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <div class="ic-country">REPUBLIC OF KENYA</div>
                            <div class="ic-title">MOTOR INSURANCE</div>
                        </div>
                    </div>

                    <div class="ic-body">
                        <div class="ic-avatar">
                            <i class="fas fa-car-side"></i>
                        </div>
                        <div class="ic-fields">
                            {{-- Insured Name removed as per request --}}
                            <div class="ic-field">
                                <span class="ic-label">Policy Number</span>
                                <span class="ic-value mono">{{ $insurance->policy_number ?? 'N/A' }}</span>
                            </div>
                            <div class="ic-field">
                                <span class="ic-label">Vehicle Reg</span>
                                <span class="ic-value mono">{{ $bus->plate_number ?? 'No Bus Assigned' }}</span>
                            </div>
                            <div class="ic-field">
                                <span class="ic-label">Underwriter</span>
                                <span class="ic-value">{{ $insurance->underwriter ?? 'N/A' }}</span>
                            </div>
                            <div class="ic-field">
                                <span class="ic-label">Expiry Date</span>
                                <span
                                    class="ic-value {{ ($insuranceExpiryStatus ?? 'none') === 'expired' ? 'text-expired' : '' }}">
                                    {{ isset($insurance->expiry_date) && $insurance->expiry_date ? \Carbon\Carbon::parse($insurance->expiry_date)->format('d M Y') : '— Not Set —' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="ic-footer">
                        @php
                            $status = $insuranceExpiryStatus ?? 'none';
                        @endphp
                        @if($status === 'expired')
                            <span class="ic-status-badge ic-expired"><i class="fas fa-times-circle"></i> EXPIRED</span>
                        @elseif($status === 'critical')
                            <span class="ic-status-badge ic-critical"><i class="fas fa-exclamation-circle"></i> EXPIRING
                                SOON</span>
                        @elseif($status === 'warning')
                            <span class="ic-status-badge ic-warning"><i class="fas fa-clock"></i> RENEWAL DUE</span>
                        @elseif($status === 'ok')
                            <span class="ic-status-badge ic-ok"><i class="fas fa-check-circle"></i> ACTIVE</span>
                        @else
                            <span class="ic-status-badge ic-none"><i class="fas fa-question-circle"></i> UNVERIFIED</span>
                        @endif
                        <span class="ic-issuer">Mwigito Excel Fleet Management</span>
                    </div>
                </div>

                <div class="readonly-notice">
                    <i class="fas fa-lock"></i>
                    <span>Insurance details are <strong>managed by your fleet administrator.</strong> Contact them to
                        request any updates or certificate changes.</span>
                </div>
            </div>

            {{-- ===== COUNTDOWN + COVERAGE DETAILS ===== --}}
            <div class="insurance-info-col">

                {{-- Countdown Block --}}
                @if(isset($insurance->expiry_date) && $insurance->expiry_date)
                    <div class="countdown-card {{ $insuranceExpiryStatus ?? 'none' }}">
                        @if(($insuranceExpiryStatus ?? 'none') === 'expired')
                            <div class="countdown-icon expired-icon"><i class="fas fa-times-circle"></i></div>
                            <div class="countdown-label">Days Since Expiry</div>
                            <div class="countdown-number expired-num">{{ abs($insuranceDaysLeft) }}</div>
                            <div class="countdown-sub">This policy is no longer valid. Vehicle cannot operate.</div>
                        @else
                            <div class="countdown-icon {{ $insuranceExpiryStatus ?? 'none' }}-icon"><i
                                    class="fas fa-hourglass-half"></i></div>
                            <div class="countdown-label">Days Until Insurance Expiry</div>
                            <div class="countdown-number {{ $insuranceExpiryStatus ?? 'none' }}-num">
                                {{ $insuranceDaysLeft ?? 0 }}
                            </div>
                            <div class="countdown-sub">Expires on
                                {{ \Carbon\Carbon::parse($insurance->expiry_date)->format('l, d M Y') }}
                            </div>

                            @php
                                $totalDays = 365;
                                $daysElapsed = $totalDays - ($insuranceDaysLeft ?? 0);
                                $pct = min(100, max(0, round(($daysElapsed / $totalDays) * 100)));
                            @endphp
                            <div class="progress-track">
                                <div class="progress-fill {{ $insuranceExpiryStatus ?? 'none' }}-fill"
                                    style="width: {{ $pct }}%"></div>
                            </div>
                            <div class="progress-legend">
                                <span>Policy start</span>
                                <span>Expiry</span>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="countdown-card none">
                        <div class="countdown-icon"><i class="fas fa-question-circle"></i></div>
                        <div class="countdown-label">Expiry Date Unknown</div>
                        <div class="countdown-number" style="font-size: 40px; color: #9ca3af;">—</div>
                        <div class="countdown-sub">No insurance expiry date has been recorded. Contact your administrator
                            urgently.</div>
                    </div>
                @endif

                {{-- Insurance Coverage Details --}}
                <div class="stat-detail-cards">
                    <div class="sdc-item">
                        <div class="sdc-icon" style="background: #f3f4f6; color: #374151;"><i class="fas fa-bus"></i>
                        </div>
                        <div class="sdc-body">
                            <span class="sdc-label">Assigned Vehicle</span>
                            <strong class="sdc-val">{{ $bus->model ?? 'No Vehicle' }}
                                ({{ $bus->plate_number ?? 'N/A' }})</strong>
                        </div>
                    </div>
                    <div class="sdc-item">
                        <div class="sdc-icon" style="background: #fff0f0; color: #800000;"><i
                                class="fas fa-file-invoice"></i></div>
                        <div class="sdc-body">
                            <span class="sdc-label">Coverage Type</span>
                            <strong class="sdc-val">{{ $insurance->coverage_type ?? 'Comprehensive (PSV)' }}</strong>
                        </div>
                    </div>
                    <div class="sdc-item">
                        <div class="sdc-icon" style="background: #f0fdf4; color: #15803d;"><i
                                class="fas fa-phone-alt"></i></div>
                        <div class="sdc-body">
                            <span class="sdc-label">Emergency Contact</span>
                            <strong class="sdc-val">{{ $insurance->emergency_number ?? '0700 123 456' }}</strong>
                        </div>
                    </div>
                </div>

                {{-- Regulatory Note --}}
                <div class="regulatory-note">
                    <i class="fas fa-gavel"></i>
                    <span>Under Kenya Traffic Act (Cap 403), operating a Public Service Vehicle without valid insurance
                        is an offense. Ensure your insurance is always active.</span>
                </div>
            </div>
        </div>
    </div>
</x-driver-layout>

<style>
    .insurance-wrapper {
        max-width: 1000px;
        margin: 0 auto;
        padding: 30px 20px 60px;
    }

    .notif-banner {
        display: flex;
        align-items: flex-start;
        gap: 20px;
        padding: 20px 24px;
        border-radius: 16px;
        margin-bottom: 28px;
        border: 1.5px solid;
    }

    .notif-icon {
        font-size: 28px;
        flex-shrink: 0;
    }

    .notif-body strong {
        font-size: 15px;
        font-weight: 900;
        display: block;
        margin-bottom: 4px;
    }

    .notif-body p {
        font-size: 13px;
        margin: 0;
        line-height: 1.5;
    }

    .notif-expired {
        background: #fef2f2;
        border-color: #fecaca;
        color: #991b1b;
    }

    .notif-critical {
        background: #fff1f2;
        border-color: #fda4af;
        color: #9f1239;
    }

    .notif-warning {
        background: #fffbeb;
        border-color: #fde68a;
        color: #92400e;
    }

    .page-hero {
        position: relative;
        padding-left: 18px;
        margin-bottom: 36px;
    }

    .hero-bar {
        position: absolute;
        left: 0;
        top: 3px;
        bottom: 3px;
        width: 5px;
        background: linear-gradient(to bottom, #800000, #c9a84c);
        border-radius: 4px;
    }

    .page-title {
        font-size: 32px;
        font-weight: 900;
        color: #111827;
        margin: 0 0 6px 0;
    }

    .page-sub {
        font-size: 14px;
        color: #6b7280;
        margin: 0;
    }

    .insurance-layout {
        display: grid;
        grid-template-columns: 1fr 1.2fr;
        gap: 32px;
    }

    @media(max-width: 768px) {
        .insurance-layout {
            grid-template-columns: 1fr;
        }
    }

    .insurance-card {
        border-radius: 22px;
        overflow: hidden;
        background: linear-gradient(145deg, #0f2c3d 0%, #1a3a4f 60%, #1e4a62 100%);
        color: white;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
    }

    .insurance-card::before {
        content: '';
        display: block;
        height: 6px;
        background: #c9a84c;
    }

    .insurance-card.expired::before {
        background: #dc2626;
    }

    .insurance-card.critical::before {
        background: #e11d48;
    }

    .insurance-card.warning::before {
        background: #f59e0b;
    }

    .insurance-card.ok::before {
        background: #10b981;
    }

    .ic-header {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px 24px 16px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .ic-flag {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.12);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #c9a84c;
    }

    .ic-country {
        font-size: 10px;
        letter-spacing: 2px;
        opacity: 0.6;
        font-weight: 800;
        text-transform: uppercase;
    }

    .ic-title {
        font-size: 18px;
        font-weight: 900;
        letter-spacing: 1px;
    }

    .ic-body {
        display: flex;
        gap: 20px;
        padding: 24px;
        align-items: flex-start;
    }

    .ic-avatar {
        width: 70px;
        height: 70px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: #c9a84c;
    }

    .ic-fields {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .ic-field {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .ic-label {
        font-size: 9px;
        font-weight: 900;
        letter-spacing: 1.5px;
        opacity: 0.5;
        text-transform: uppercase;
    }

    .ic-value {
        font-size: 14px;
        font-weight: 800;
    }

    .ic-value.mono {
        font-family: monospace;
        letter-spacing: 1px;
    }

    .text-expired {
        color: #fca5a5;
    }

    .ic-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 24px;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        background: rgba(0, 0, 0, 0.2);
    }

    .ic-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 5px 12px;
        border-radius: 99px;
    }

    .ic-ok {
        background: rgba(16, 185, 129, 0.2);
        color: #34d399;
    }

    .ic-warning {
        background: rgba(245, 158, 11, 0.2);
        color: #fbbf24;
    }

    .ic-critical {
        background: rgba(225, 29, 72, 0.2);
        color: #fb7185;
    }

    .ic-expired {
        background: rgba(220, 38, 38, 0.2);
        color: #f87171;
    }

    .readonly-notice {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 14px 18px;
        font-size: 13px;
        color: #6b7280;
    }

    .countdown-card {
        background: white;
        border-radius: 22px;
        padding: 32px;
        border: 1.5px solid #f3f4f6;
        text-align: center;
        margin-bottom: 20px;
    }

    .countdown-icon {
        font-size: 36px;
        margin-bottom: 12px;
    }

    .countdown-label {
        font-size: 11px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: #9ca3af;
        margin-bottom: 8px;
    }

    .countdown-number {
        font-size: 72px;
        font-weight: 900;
        line-height: 1;
        letter-spacing: -3px;
        margin-bottom: 8px;
    }

    .expired-num {
        color: #dc2626;
    }

    .critical-num {
        color: #e11d48;
    }

    .warning-num {
        color: #d97706;
    }

    .ok-num {
        color: #059669;
    }

    .progress-track {
        width: 100%;
        height: 8px;
        background: #f3f4f6;
        border-radius: 99px;
        overflow: hidden;
        margin-bottom: 6px;
    }

    .progress-fill {
        height: 100%;
        border-radius: 99px;
        transition: width 1s ease;
    }

    .ok-fill {
        background: linear-gradient(to right, #10b981, #34d399);
    }

    .warning-fill {
        background: linear-gradient(to right, #f59e0b, #fbbf24);
    }

    .critical-fill {
        background: linear-gradient(to right, #e11d48, #fb7185);
    }

    .stat-detail-cards {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 16px;
    }

    .sdc-item {
        display: flex;
        align-items: center;
        gap: 16px;
        background: white;
        border-radius: 16px;
        padding: 16px 20px;
        border: 1px solid #f3f4f6;
    }

    .sdc-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .sdc-label {
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #9ca3af;
        display: block;
        margin-bottom: 2px;
    }

    .sdc-val {
        font-size: 14px;
        font-weight: 800;
        color: #111827;
    }

    .regulatory-note {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #fefce8;
        border-left: 4px solid #eab308;
        border-radius: 14px;
        padding: 14px 18px;
        font-size: 12px;
        color: #854d0e;
    }

    .fade-up {
        animation: fadeUp 0.5s ease both;
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
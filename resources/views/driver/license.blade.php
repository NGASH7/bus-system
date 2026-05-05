<x-driver-layout>
    <div class="license-wrapper fade-up">

        {{-- ===== EXPIRY NOTIFICATION BANNER ===== --}}
        @if($expiryStatus === 'expired')
        <div class="notif-banner notif-expired">
            <div class="notif-icon"><i class="fas fa-skull-crossbones"></i></div>
            <div class="notif-body">
                <strong>Your driving license has EXPIRED</strong>
                <p>You are currently not authorized to operate. Please contact fleet management immediately to renew your license records.</p>
            </div>
        </div>
        @elseif($expiryStatus === 'critical')
        <div class="notif-banner notif-critical">
            <div class="notif-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="notif-body">
                <strong>URGENT: License expires in {{ $daysToExpiry }} day{{ $daysToExpiry == 1 ? '' : 's' }}!</strong>
                <p>Your license is expiring very soon. Please notify the fleet administrator immediately so they can update your records.</p>
            </div>
        </div>
        @elseif($expiryStatus === 'warning')
        <div class="notif-banner notif-warning">
            <div class="notif-icon"><i class="fas fa-clock"></i></div>
            <div class="notif-body">
                <strong>License Expiry Notice: {{ $daysToExpiry }} days remaining</strong>
                <p>Your driving license is approaching its expiry date. Inform your fleet administrator to begin the renewal process.</p>
            </div>
        </div>
        @endif

        {{-- ===== PAGE HERO ===== --}}
        <div class="page-hero">
            <div class="hero-bar"></div>
            <h1 class="page-title">MY LICENSE</h1>
            <p class="page-sub">Your official driving credentials and compliance record. Contact the admin to request updates.</p>
        </div>

        <div class="license-layout">

            {{-- ===== LICENSE CARD (Visual ID-Style) ===== --}}
            <div class="license-card-col">
                <div class="license-card {{ $expiryStatus }}">
                    <div class="lc-header">
                        <div class="lc-flag">
                            <i class="fas fa-road"></i>
                        </div>
                        <div>
                            <div class="lc-country">REPUBLIC OF KENYA</div>
                            <div class="lc-title">DRIVING LICENCE</div>
                        </div>
                    </div>

                    <div class="lc-body">
                        <div class="lc-avatar">
                            {{ strtoupper(substr($driver->name, 0, 2)) }}
                        </div>
                        <div class="lc-fields">
                            <div class="lc-field">
                                <span class="lc-label">Full Name</span>
                                <span class="lc-value">{{ $driver->name }}</span>
                            </div>
                            <div class="lc-field">
                                <span class="lc-label">License No.</span>
                                <span class="lc-value mono">{{ $driver->license_number ?? '— Not Provided —' }}</span>
                            </div>
                            <div class="lc-field">
                                <span class="lc-label">National ID</span>
                                <span class="lc-value mono">{{ $driver->national_id ?? '— Not Provided —' }}</span>
                            </div>
                            <div class="lc-field">
                                <span class="lc-label">Date of Expiry</span>
                                <span class="lc-value {{ $expiryStatus === 'expired' ? 'text-expired' : '' }}">
                                    {{ $driver->license_expiry ? \Carbon\Carbon::parse($driver->license_expiry)->format('d M Y') : '— Not Provided —' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="lc-footer">
                        @if($expiryStatus === 'expired')
                            <span class="lc-status-badge lc-expired"><i class="fas fa-times-circle"></i> EXPIRED</span>
                        @elseif($expiryStatus === 'critical')
                            <span class="lc-status-badge lc-critical"><i class="fas fa-exclamation-circle"></i> EXPIRING SOON</span>
                        @elseif($expiryStatus === 'warning')
                            <span class="lc-status-badge lc-warning"><i class="fas fa-clock"></i> RENEWAL DUE</span>
                        @elseif($expiryStatus === 'ok')
                            <span class="lc-status-badge lc-ok"><i class="fas fa-check-circle"></i> VALID</span>
                        @else
                            <span class="lc-status-badge lc-none"><i class="fas fa-question-circle"></i> UNVERIFIED</span>
                        @endif
                        <span class="lc-issuer">Mwigito Excel Fleet Management</span>
                    </div>
                </div>

                <div class="readonly-notice">
                    <i class="fas fa-lock"></i>
                    <span>License details are <strong>managed by your fleet administrator.</strong> Contact them to request any updates.</span>
                </div>
            </div>

            {{-- ===== STATS + COUNTDOWN ===== --}}
            <div class="license-info-col">

                {{-- Countdown Block --}}
                @if($driver->license_expiry)
                <div class="countdown-card {{ $expiryStatus }}">
                    @if($expiryStatus === 'expired')
                    <div class="countdown-icon expired-icon"><i class="fas fa-times-circle"></i></div>
                    <div class="countdown-label">Days Since Expiry</div>
                    <div class="countdown-number expired-num">{{ abs($daysToExpiry) }}</div>
                    <div class="countdown-sub">This license is no longer valid.</div>
                    @else
                    <div class="countdown-icon {{ $expiryStatus }}-icon"><i class="fas fa-hourglass-half"></i></div>
                    <div class="countdown-label">Days Until Expiry</div>
                    <div class="countdown-number {{ $expiryStatus }}-num">{{ $daysToExpiry }}</div>
                    <div class="countdown-sub">Expires on {{ \Carbon\Carbon::parse($driver->license_expiry)->format('l, d M Y') }}</div>

                    {{-- Progress bar --}}
                    @php
                        $totalWarningDays = 365;
                        $pct = min(100, max(0, round(($daysToExpiry / $totalWarningDays) * 100)));
                    @endphp
                    <div class="progress-track">
                        <div class="progress-fill {{ $expiryStatus }}-fill" style="width: {{ $pct }}%"></div>
                    </div>
                    <div class="progress-legend">
                        <span>0 days</span>
                        <span>365 days</span>
                    </div>
                    @endif
                </div>
                @else
                <div class="countdown-card none">
                    <div class="countdown-icon"><i class="fas fa-question-circle"></i></div>
                    <div class="countdown-label">Expiry Date Unknown</div>
                    <div class="countdown-number" style="font-size: 40px; color: #9ca3af;">—</div>
                    <div class="countdown-sub">No license expiry date has been recorded. Contact your administrator.</div>
                </div>
                @endif

                {{-- Detail stats --}}
                <div class="stat-detail-cards">
                    <div class="sdc-item">
                        <div class="sdc-icon" style="background: #f3f4f6; color: #374151;"><i class="fas fa-user"></i></div>
                        <div class="sdc-body">
                            <span class="sdc-label">Registered Under</span>
                            <strong class="sdc-val">{{ $driver->name }}</strong>
                        </div>
                    </div>
                    <div class="sdc-item">
                        <div class="sdc-icon" style="background: #fff0f0; color: var(--maroon);"><i class="fas fa-envelope"></i></div>
                        <div class="sdc-body">
                            <span class="sdc-label">Email on File</span>
                            <strong class="sdc-val">{{ $driver->email }}</strong>
                        </div>
                    </div>
                    <div class="sdc-item">
                        <div class="sdc-icon" style="background: #f0fdf4; color: #15803d;"><i class="fas fa-phone"></i></div>
                        <div class="sdc-body">
                            <span class="sdc-label">Phone Number</span>
                            <strong class="sdc-val">{{ $driver->phone_number ?? '—' }}</strong>
                        </div>
                    </div>
                    <div class="sdc-item">
                        <div class="sdc-icon" style="background: #eff6ff; color: #1d4ed8;"><i class="fas fa-calendar-check"></i></div>
                        <div class="sdc-body">
                            <span class="sdc-label">Account Registered</span>
                            <strong class="sdc-val">{{ $driver->created_at->format('d M Y') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --maroon: #800000;
            --maroon-dark: #600000;
            --gold: #c9a84c;
        }

        .license-wrapper { max-width: 1000px; margin: 0 auto; padding: 30px 20px 60px; }

        /* ===== NOTIFICATION BANNERS ===== */
        .notif-banner {
            display: flex; align-items: flex-start; gap: 20px;
            padding: 20px 24px; border-radius: 16px;
            margin-bottom: 28px; border: 1.5px solid;
        }

        .notif-icon { font-size: 28px; flex-shrink: 0; padding-top: 2px; }

        .notif-body strong { font-size: 15px; font-weight: 900; display: block; margin-bottom: 4px; }
        .notif-body p { font-size: 13px; font-weight: 600; margin: 0; line-height: 1.5; opacity: 0.85; }

        .notif-expired { background: #fef2f2; border-color: #fecaca; color: #991b1b; }
        .notif-expired .notif-icon { color: #dc2626; }

        .notif-critical { background: #fff1f2; border-color: #fda4af; color: #9f1239; }
        .notif-critical .notif-icon { color: #e11d48; }

        .notif-warning { background: #fffbeb; border-color: #fde68a; color: #92400e; }
        .notif-warning .notif-icon { color: #d97706; }

        /* ===== PAGE HERO ===== */
        .page-hero { position: relative; padding-left: 18px; margin-bottom: 36px; }
        .hero-bar { position: absolute; left: 0; top: 3px; bottom: 3px; width: 5px;
            background: linear-gradient(to bottom, var(--maroon), var(--gold)); border-radius: 4px; }
        .page-title { font-family: 'Outfit', sans-serif; font-size: 32px; font-weight: 900;
            color: #111827; margin: 0 0 6px 0; letter-spacing: -1px; }
        .page-sub { font-size: 14px; color: #6b7280; margin: 0; }

        /* ===== LAYOUT ===== */
        .license-layout { display: grid; grid-template-columns: 1fr 1.2fr; gap: 32px; }

        @media(max-width: 768px) { .license-layout { grid-template-columns: 1fr; } }

        /* ===== LICENSE CARD ===== */
        .license-card-col { display: flex; flex-direction: column; gap: 16px; }

        .license-card {
            border-radius: 22px; overflow: hidden;
            background: linear-gradient(145deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
            color: white; box-shadow: 0 20px 50px rgba(0,0,0,0.2);
            position: relative;
        }

        /* Status color accent on top */
        .license-card::before {
            content: ''; display: block; height: 6px;
            background: var(--gold);
        }
        .license-card.expired::before { background: #dc2626; }
        .license-card.critical::before { background: #e11d48; }
        .license-card.warning::before { background: #f59e0b; }
        .license-card.ok::before { background: #10b981; }
        .license-card.none::before { background: #6b7280; }

        .lc-header {
            display: flex; align-items: center; gap: 16px;
            padding: 20px 24px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .lc-flag {
            width: 48px; height: 48px; border-radius: 12px;
            background: rgba(255,255,255,0.12);
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; color: var(--gold);
        }

        .lc-country { font-size: 10px; letter-spacing: 2px; opacity: 0.6; font-weight: 800; text-transform: uppercase; }
        .lc-title { font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 900; letter-spacing: 1px; }

        .lc-body { display: flex; gap: 20px; padding: 24px; align-items: flex-start; }

        .lc-avatar {
            width: 70px; height: 70px; border-radius: 14px;
            background: rgba(255,255,255,0.1); border: 2px solid rgba(255,255,255,0.2);
            display: flex; align-items: center; justify-content: center;
            font-weight: 900; font-size: 24px; flex-shrink: 0;
            color: var(--gold);
        }

        .lc-fields { flex: 1; display: flex; flex-direction: column; gap: 10px; }

        .lc-field { display: flex; flex-direction: column; gap: 2px; }
        .lc-label { font-size: 9px; font-weight: 900; letter-spacing: 1.5px; opacity: 0.5; text-transform: uppercase; }
        .lc-value { font-size: 14px; font-weight: 800; }
        .lc-value.mono { font-family: 'Space Mono', monospace, sans-serif; letter-spacing: 1px; }
        .lc-value.text-expired { color: #fca5a5; }

        .lc-footer {
            display: flex; justify-content: space-between; align-items: center;
            padding: 14px 24px;
            border-top: 1px solid rgba(255,255,255,0.08);
            background: rgba(0,0,0,0.2);
        }

        .lc-status-badge {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 10px; font-weight: 900; text-transform: uppercase;
            letter-spacing: 1px; padding: 5px 12px; border-radius: 99px;
        }

        .lc-ok   { background: rgba(16,185,129,0.2); color: #34d399; }
        .lc-warning { background: rgba(245,158,11,0.2); color: #fbbf24; }
        .lc-critical { background: rgba(225,29,72,0.2); color: #fb7185; }
        .lc-expired { background: rgba(220,38,38,0.2); color: #f87171; }
        .lc-none { background: rgba(107,114,128,0.2); color: #9ca3af; }

        .lc-issuer { font-size: 10px; opacity: 0.4; font-weight: 700; }

        .readonly-notice {
            display: flex; align-items: center; gap: 12px;
            background: #f9fafb; border: 1px solid #e5e7eb;
            border-radius: 14px; padding: 14px 18px;
            font-size: 13px; color: #6b7280;
        }
        .readonly-notice i { color: #9ca3af; font-size: 16px; flex-shrink: 0; }

        /* ===== COUNTDOWN CARD ===== */
        .countdown-card {
            background: white; border-radius: 22px; padding: 32px;
            border: 1.5px solid #f3f4f6;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            text-align: center; margin-bottom: 20px;
        }

        .countdown-icon { font-size: 36px; margin-bottom: 12px; }
        .countdown-label { font-size: 11px; font-weight: 900; text-transform: uppercase;
            letter-spacing: 1.5px; color: #9ca3af; margin-bottom: 8px; }

        .countdown-number { font-family: 'Outfit', sans-serif; font-size: 72px; font-weight: 900;
            line-height: 1; letter-spacing: -3px; margin-bottom: 8px; }
        .countdown-sub { font-size: 13px; font-weight: 600; color: #6b7280; margin-bottom: 24px; }

        /* countdown number colours */
        .expired-icon { color: #dc2626; }
        .expired-num { color: #dc2626; }

        .critical-icon { color: #e11d48; }
        .critical-num { color: #e11d48; }

        .warning-icon { color: #d97706; }
        .warning-num { color: #d97706; }

        .ok-icon { color: #059669; }
        .ok-num { color: #059669; }

        /* Progress bar */
        .progress-track {
            width: 100%; height: 8px; background: #f3f4f6;
            border-radius: 99px; overflow: hidden; margin-bottom: 6px;
        }

        .progress-fill { height: 100%; border-radius: 99px; transition: width 1s ease; }
        .ok-fill { background: linear-gradient(to right, #10b981, #34d399); }
        .warning-fill { background: linear-gradient(to right, #f59e0b, #fbbf24); }
        .critical-fill { background: linear-gradient(to right, #e11d48, #fb7185); }

        .progress-legend { display: flex; justify-content: space-between;
            font-size: 10px; font-weight: 700; color: #d1d5db; }

        /* ===== STAT DETAIL CARDS ===== */
        .stat-detail-cards { display: flex; flex-direction: column; gap: 12px; }

        .sdc-item {
            display: flex; align-items: center; gap: 16px;
            background: white; border-radius: 16px; padding: 16px 20px;
            border: 1px solid #f3f4f6;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }

        .sdc-icon {
            width: 42px; height: 42px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; flex-shrink: 0;
        }

        .sdc-label { font-size: 10px; font-weight: 900; text-transform: uppercase;
            letter-spacing: 1px; color: #9ca3af; display: block; margin-bottom: 2px; }
        .sdc-val { font-size: 14px; font-weight: 800; color: #111827; }

        /* ===== ANIMATIONS ===== */
        .fade-up { animation: fadeUp 0.5s cubic-bezier(0.16,1,0.3,1) both; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        /* Pulse animation on expired icon */
        .notif-expired .notif-icon, .notif-critical .notif-icon {
            animation: pulse-icon 1.5s infinite;
        }
        @keyframes pulse-icon {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.15); }
        }
    </style>
</x-driver-layout>

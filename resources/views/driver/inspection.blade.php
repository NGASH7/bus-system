<x-driver-layout>
    <div class="inspection-wrapper fade-up">

        {{-- ===== EXPIRY NOTIFICATION BANNER (Inspection Focus) ===== --}}
        @if(isset($inspectionExpiryStatus) && $inspectionExpiryStatus === 'expired')
            <div class="notif-banner notif-expired">
                <div class="notif-icon"><i class="fas fa-skull-crossbones"></i></div>
                <div class="notif-body">
                    <strong>INSPECTION HAS EXPIRED</strong>
                    <p>Your vehicle safety inspection is no longer valid. Operating without valid inspection is illegal and unsafe. Contact fleet management immediately.</p>
                </div>
            </div>
        @elseif(isset($inspectionExpiryStatus) && $inspectionExpiryStatus === 'critical')
            <div class="notif-banner notif-critical">
                <div class="notif-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="notif-body">
                    <strong>URGENT: Inspection expires in {{ abs($inspectionDaysLeft) }} day{{ abs($inspectionDaysLeft) == 1 ? '' : 's' }}!</strong>
                    <p>Your vehicle's safety inspection is expiring very soon. Notify the fleet administrator immediately to schedule a re-inspection.</p>
                </div>
            </div>
        @elseif(isset($inspectionExpiryStatus) && $inspectionExpiryStatus === 'warning')
            <div class="notif-banner notif-warning">
                <div class="notif-icon"><i class="fas fa-clock"></i></div>
                <div class="notif-body">
                    <strong>Inspection Expiry Notice: {{ $inspectionDaysLeft }} days remaining</strong>
                    <p>Your vehicle safety inspection certificate is approaching its expiry date. Plan for renewal soon.</p>
                </div>
            </div>
        @endif

        {{-- ===== PAGE HERO ===== --}}
        <div class="page-hero">
            <div class="hero-bar"></div>
            <h1 class="page-title">VEHICLE INSPECTION</h1>
            <p class="page-sub">Your assigned vehicle's safety and regulatory inspection credentials.</p>
        </div>

        <div class="inspection-layout">

            {{-- ===== INSPECTION CARD ===== --}}
            <div class="inspection-card-col">
                <div class="inspection-card {{ $inspectionExpiryStatus ?? 'none' }}">
                    <div class="ic-header">
                        <div class="ic-flag">
                            <i class="fas fa-check-double"></i>
                        </div>
                        <div>
                            <div class="ic-country">REPUBLIC OF KENYA</div>
                            <div class="ic-title">INSPECTION CERTIFICATE</div>
                        </div>
                    </div>

                    <div class="ic-body">
                        <div class="ic-avatar">
                            <i class="fas fa-tools"></i>
                        </div>
                        <div class="ic-fields">
                            <div class="ic-field">
                                <span class="ic-label">Certificate Number</span>
                                <span class="ic-value mono">{{ $bus->inspection_certificate ?? 'N/A' }}</span>
                            </div>
                            <div class="ic-field">
                                <span class="ic-label">Vehicle Reg</span>
                                <span class="ic-value mono">{{ $bus->plate_number ?? 'No Bus Assigned' }}</span>
                            </div>
                            <div class="ic-field">
                                <span class="ic-label">Inspection Date</span>
                                <span class="ic-value">{{ $bus->inspection_expiry ? $bus->inspection_expiry->subYear()->format('d M Y') : 'N/A' }}</span>
                            </div>
                            <div class="ic-field">
                                <span class="ic-label">Expiry Date</span>
                                <span class="ic-value {{ ($inspectionExpiryStatus ?? 'none') === 'expired' ? 'text-expired' : '' }}">
                                    {{ $bus->inspection_expiry ? $bus->inspection_expiry->format('d M Y') : '— Not Set —' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="ic-footer">
                        @php $status = $inspectionExpiryStatus ?? 'none'; @endphp
                        @if($status === 'expired')
                            <span class="ic-status-badge ic-expired"><i class="fas fa-times-circle"></i> EXPIRED</span>
                        @elseif($status === 'critical')
                            <span class="ic-status-badge ic-critical"><i class="fas fa-exclamation-circle"></i> URGENT RENEWAL</span>
                        @elseif($status === 'warning')
                            <span class="ic-status-badge ic-warning"><i class="fas fa-clock"></i> EXPIRING SOON</span>
                        @elseif($status === 'ok')
                            <span class="ic-status-badge ic-ok"><i class="fas fa-check-circle"></i> VALID</span>
                        @else
                            <span class="ic-status-badge ic-none"><i class="fas fa-question-circle"></i> NO RECORD</span>
                        @endif
                        <span class="ic-issuer">Mwigito Excel Safety Unit</span>
                    </div>
                </div>

                <div class="readonly-notice">
                    <i class="fas fa-lock"></i>
                    <span>Inspection details are <strong>managed by your fleet administrator.</strong> Ensure your vehicle is ready for the scheduled inspection.</span>
                </div>
            </div>

            {{-- ===== COUNTDOWN + DETAILS ===== --}}
            <div class="inspection-info-col">

                {{-- Countdown Block --}}
                @if($bus && $bus->inspection_expiry)
                    <div class="countdown-card {{ $inspectionExpiryStatus ?? 'none' }}">
                        @if(($inspectionExpiryStatus ?? 'none') === 'expired')
                            <div class="countdown-icon expired-icon"><i class="fas fa-times-circle"></i></div>
                            <div class="countdown-label">Days Since Expiry</div>
                            <div class="countdown-number expired-num">{{ abs($inspectionDaysLeft) }}</div>
                            <div class="countdown-sub">Safety certification has lapsed. Operating is prohibited.</div>
                        @else
                            <div class="countdown-icon {{ $inspectionExpiryStatus ?? 'none' }}-icon"><i class="fas fa-history"></i></div>
                            <div class="countdown-label">Days Until Inspection Expiry</div>
                            <div class="countdown-number {{ $inspectionExpiryStatus ?? 'none' }}-num">{{ $inspectionDaysLeft ?? 0 }}</div>
                            <div class="countdown-sub">Expires on {{ $bus->inspection_expiry->format('l, d M Y') }}</div>

                            @php
                                $totalDays = 365;
                                $daysElapsed = $totalDays - ($inspectionDaysLeft ?? 0);
                                $pct = min(100, max(0, round(($daysElapsed / $totalDays) * 100)));
                            @endphp
                            <div class="progress-track">
                                <div class="progress-fill {{ $inspectionExpiryStatus ?? 'none' }}-fill" style="width: {{ $pct }}%"></div>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="countdown-card none">
                        <div class="countdown-icon"><i class="fas fa-question-circle"></i></div>
                        <div class="countdown-label">Expiry Date Unknown</div>
                        <div class="countdown-number" style="font-size: 40px; color: #9ca3af;">—</div>
                        <div class="countdown-sub">No inspection expiry date has been recorded for this bus.</div>
                    </div>
                @endif

                {{-- Inspection Details --}}
                <div class="stat-detail-cards">
                    <div class="sdc-item">
                        <div class="sdc-icon" style="background: #f3f4f6; color: #374151;"><i class="fas fa-bus"></i></div>
                        <div class="sdc-body">
                            <span class="sdc-label">Vehicle Info</span>
                            <strong class="sdc-val">{{ $bus->model ?? 'No Vehicle' }} ({{ $bus->plate_number ?? 'N/A' }})</strong>
                        </div>
                    </div>
                    <div class="sdc-item">
                        <div class="sdc-icon" style="background: #fff0f0; color: #800000;"><i class="fas fa-clipboard-check"></i></div>
                        <div class="sdc-body">
                            <span class="sdc-label">Status</span>
                            <strong class="sdc-val">{{ ucfirst($inspectionExpiryStatus ?? 'Unknown') }}</strong>
                        </div>
                    </div>
                </div>

                {{-- Regulatory Note --}}
                <div class="regulatory-note">
                    <i class="fas fa-info-circle"></i>
                    <span>All PSV vehicles must undergo annual safety inspection by NTSA or authorized inspection centers.</span>
                </div>
            </div>
        </div>
    </div>

    <style>
        .inspection-wrapper { max-width: 1000px; margin: 0 auto; padding: 30px 20px 60px; }
        .notif-banner { display: flex; align-items: flex-start; gap: 20px; padding: 20px 24px; border-radius: 16px; margin-bottom: 28px; border: 1.5px solid; }
        .notif-icon { font-size: 28px; flex-shrink: 0; }
        .notif-body strong { font-size: 15px; font-weight: 900; display: block; margin-bottom: 4px; }
        .notif-body p { font-size: 13px; margin: 0; line-height: 1.5; }
        .notif-expired { background: #fef2f2; border-color: #fecaca; color: #991b1b; }
        .notif-critical { background: #fff1f2; border-color: #fda4af; color: #9f1239; }
        .notif-warning { background: #fffbeb; border-color: #fde68a; color: #92400e; }
        .page-hero { position: relative; padding-left: 18px; margin-bottom: 36px; }
        .hero-bar { position: absolute; left: 0; top: 3px; bottom: 3px; width: 5px; background: linear-gradient(to bottom, #800000, #c9a84c); border-radius: 4px; }
        .page-title { font-size: 32px; font-weight: 900; color: #111827; margin: 0 0 6px 0; }
        .page-sub { font-size: 14px; color: #6b7280; margin: 0; }
        .inspection-layout { display: grid; grid-template-columns: 1fr 1.2fr; gap: 32px; }
        @media(max-width: 768px) { .inspection-layout { grid-template-columns: 1fr; } }
        .inspection-card { border-radius: 22px; overflow: hidden; background: linear-gradient(145deg, #1e1e2d 0%, #2d2d44 100%); color: white; box-shadow: 0 20px 50px rgba(0,0,0,0.2); }
        .inspection-card::before { content: ''; display: block; height: 6px; background: #c9a84c; }
        .inspection-card.expired::before { background: #dc2626; }
        .inspection-card.critical::before { background: #e11d48; }
        .inspection-card.warning::before { background: #f59e0b; }
        .inspection-card.ok::before { background: #10b981; }
        .ic-header { display: flex; align-items: center; gap: 16px; padding: 20px 24px 16px; border-bottom: 1px solid rgba(255,255,255,0.08); }
        .ic-flag { width: 48px; height: 48px; border-radius: 12px; background: rgba(255,255,255,0.12); display: flex; align-items: center; justify-content: center; font-size: 20px; color: #c9a84c; }
        .ic-country { font-size: 10px; letter-spacing: 2px; opacity: 0.6; font-weight: 800; text-transform: uppercase; }
        .ic-title { font-size: 18px; font-weight: 900; letter-spacing: 1px; }
        .ic-body { display: flex; gap: 20px; padding: 24px; align-items: flex-start; }
        .ic-avatar { width: 70px; height: 70px; border-radius: 14px; background: rgba(255,255,255,0.1); border: 2px solid rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 28px; color: #c9a84c; }
        .ic-fields { flex: 1; display: flex; flex-direction: column; gap: 10px; }
        .ic-field { display: flex; flex-direction: column; gap: 2px; }
        .ic-label { font-size: 9px; font-weight: 900; letter-spacing: 1.5px; opacity: 0.5; text-transform: uppercase; }
        .ic-value { font-size: 14px; font-weight: 800; }
        .ic-value.mono { font-family: monospace; letter-spacing: 1px; }
        .ic-footer { display: flex; justify-content: space-between; align-items: center; padding: 14px 24px; border-top: 1px solid rgba(255,255,255,0.08); background: rgba(0,0,0,0.2); }
        .ic-status-badge { display: inline-flex; align-items: center; gap: 6px; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; padding: 5px 12px; border-radius: 99px; }
        .ic-ok { background: rgba(16,185,129,0.2); color: #34d399; }
        .ic-warning { background: rgba(245,158,11,0.2); color: #fbbf24; }
        .ic-critical { background: rgba(225,29,72,0.2); color: #fb7185; }
        .ic-expired { background: rgba(220,38,38,0.2); color: #f87171; }
        .readonly-notice { display: flex; align-items: center; gap: 12px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 14px; padding: 14px 18px; font-size: 13px; color: #6b7280; margin-top: 20px; }
        .countdown-card { background: white; border-radius: 22px; padding: 32px; border: 1.5px solid #f3f4f6; text-align: center; margin-bottom: 20px; }
        .countdown-icon { font-size: 36px; margin-bottom: 12px; }
        .countdown-label { font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 1.5px; color: #9ca3af; margin-bottom: 8px; }
        .countdown-number { font-size: 72px; font-weight: 900; line-height: 1; letter-spacing: -3px; margin-bottom: 8px; }
        .expired-num { color: #dc2626; }
        .critical-num { color: #e11d48; }
        .warning-num { color: #d97706; }
        .ok-num { color: #059669; }
        .progress-track { width: 100%; height: 8px; background: #f3f4f6; border-radius: 99px; overflow: hidden; margin-bottom: 6px; }
        .progress-fill { height: 100%; border-radius: 99px; transition: width 1s ease; }
        .ok-fill { background: linear-gradient(to right, #10b981, #34d399); }
        .warning-fill { background: linear-gradient(to right, #f59e0b, #fbbf24); }
        .critical-fill { background: linear-gradient(to right, #e11d48, #fb7185); }
        .stat-detail-cards { display: flex; flex-direction: column; gap: 12px; margin-bottom: 16px; }
        .sdc-item { display: flex; align-items: center; gap: 16px; background: white; border-radius: 16px; padding: 16px 20px; border: 1px solid #f3f4f6; }
        .sdc-icon { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 16px; }
        .sdc-label { font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; color: #9ca3af; display: block; margin-bottom: 2px; }
        .sdc-val { font-size: 14px; font-weight: 800; color: #111827; }
        .regulatory-note { display: flex; align-items: center; gap: 12px; background: #fefce8; border-left: 4px solid #eab308; border-radius: 14px; padding: 14px 18px; font-size: 12px; color: #854d0e; }
        .fade-up { animation: fadeUp 0.5s ease both; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</x-driver-layout>

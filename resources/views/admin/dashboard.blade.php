<x-admin-layout>
    <div class="admin-dashboard-wrapper fade-up">
        <div class="max-w-[1440px] mx-auto">
            
            <!-- MINIMAL HEADER -->
            <div class="welcome-header-minimal mb-8">
                <h1 class="page-title">Admin Dashboard</h1>
            </div>

            <div class="stats-row">
                <!-- Fleet Card -->
                <div class="admin-stat-card">
                    <div class="sc-icon-circle"><i class="fas fa-bus"></i></div>
                    <div class="sc-number">{{ $stats['buses'] }}</div>
                    <div class="sc-label">ACTIVE FLEET</div>
                </div>

                <!-- Drivers Card -->
                <div class="admin-stat-card">
                    <div class="sc-icon-circle"><i class="fas fa-id-card"></i></div>
                    <div class="sc-number">{{ $stats['drivers'] }}</div>
                    <div class="sc-label">TOTAL DRIVERS</div>
                </div>

                <!-- Billing Card -->
                <div class="admin-stat-card">
                    <div class="sc-icon-circle danger-tint"><i class="fas fa-file-invoice-dollar"></i></div>
                    <div class="sc-number danger-text">{{ $stats['billings'] }}</div>
                    <div class="sc-label">PENDING BILLING</div>
                </div>

                <!-- Receipts Card -->
                <div class="admin-stat-card">
                    <div class="sc-icon-circle"><i class="fas fa-receipt"></i></div>
                    <div class="sc-number">{{ $stats['receipts'] }}</div>
                    <div class="sc-label">TOTAL RECEIPTS</div>
                </div>
            </div>

            <!-- RECENT SYSTEM ACTIVITIES -->
            <div class="activity-section fade-up" style="animation-delay: 0.2s;">
                <div class="section-header-compact mb-6">
                    <h3 class="section-title-premium"><i class="fas fa-history mr-2"></i> System Activity Log</h3>
                    <a href="#" class="view-all-link">View Everything</a>
                </div>

                <div class="activity-container">
                    <!-- Activity Item 1 -->
                    <div class="activity-row">
                        <div class="activity-marker odometer-accent">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-top">
                                <span class="activity-user">Driver John Doe</span>
                                <span class="activity-time">14 mins ago</span>
                            </div>
                            <p class="activity-msg">Clocked new odometer reading <strong>24,500 KM</strong> for Bus <strong>KDP 923K</strong></p>
                        </div>
                    </div>

                    <!-- Activity Item 2 -->
                    <div class="activity-row">
                        <div class="activity-marker billing-accent">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-top">
                                <span class="activity-user">System Automated</span>
                                <span class="activity-time">2 hours ago</span>
                            </div>
                            <p class="activity-msg">Generated monthly billing record for <strong>Mwigito School Route A</strong></p>
                        </div>
                    </div>

                    <!-- Activity Item 3 -->
                    <div class="activity-row">
                        <div class="activity-marker driver-accent">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-top">
                                <span class="activity-user">Admin Sarah</span>
                                <span class="activity-time">5 hours ago</span>
                            </div>
                            <p class="activity-msg">Verified new driver documentation for <strong>Michael Kanyingi</strong></p>
                        </div>
                    </div>

                    <!-- Activity Item 4 -->
                    <div class="activity-row last-row">
                        <div class="activity-marker service-accent">
                            <i class="fas fa-tools"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-top">
                                <span class="activity-user">Fleet Manager</span>
                                <span class="activity-time">Yesterday</span>
                            </div>
                            <p class="activity-msg">Maintenance scheduled for <strong>Bus KDP 112L</strong> (Engine Diagnostic)</p>
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
            --text-main: #1a202c;
            --text-muted: #718096;
            --bg-body: #f8fafc;
            --success: #16a34a;
        }

        .admin-dashboard-wrapper {
            padding: 0 10px 10px 10px;
        }

        /* MINIMAL HEADER */
        .welcome-header-minimal {
            padding-bottom: 8px;
            border-bottom: 2px solid #edf2f7;
        }

        .page-title {
            font-family: 'Outfit', sans-serif;
            font-size: 26px;
            font-weight: 850;
            color: var(--text-main);
            margin: 0;
            letter-spacing: -0.5px;
        }

        /* STATS GRID - COMPACT 4 IN A ROW */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px; /* Reduced gap between cards */
        }

        @media (max-width: 1024px) {
            .stats-row { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 600px) {
            .stats-row { grid-template-columns: 1fr; }
        }

        /* ADMIN STAT CARD - ULTRA RECTANGULAR COMPACT THEME */
        .admin-stat-card {
            background: white;
            padding: 14px 15px; /* Further reduced vertical padding */
            border-radius: 14px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            border: 1px solid #f1f5f9;
        }

        .admin-stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(128, 0, 0, 0.08);
        }

        .sc-icon-circle {
            width: 38px;
            height: 38px;
            background: rgba(128, 0, 0, 0.05);
            color: var(--maroon);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            margin-bottom: 12px; /* Reduced internal margin */
        }

        .sc-number {
            font-family: 'Outfit', sans-serif;
            font-size: 34px;
            font-weight: 850;
            color: var(--maroon);
            line-height: 1;
            margin-bottom: 4px; /* Reduced internal margin */
        }

        .sc-label {
            font-size: 10px; /* Smaller label */
            font-weight: 800;
            color: var(--text-muted);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* RECENT ACTIVITIES SECTION */
        .activity-section {
            margin-top: 50px;
        }

        .section-header-compact {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #edf2f7;
            padding-bottom: 12px;
        }

        .section-title-premium {
            font-size: 19px;
            font-weight: 850;
            color: var(--text-main);
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .view-all-link {
            font-size: 12px;
            font-weight: 700;
            color: var(--maroon);
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: color 0.2s;
        }

        .view-all-link:hover { color: var(--gold); }

        .activity-container {
            background: white;
            border-radius: 20px;
            border: 1px solid #f1f5f9;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        }

        .activity-row {
            padding: 20px 30px;
            display: flex;
            gap: 20px;
            align-items: center;
            border-bottom: 1px solid #f8fafc;
            transition: background 0.2s;
        }

        .activity-row:hover { background: #fcfcfc; }
        .activity-row.last-row { border-bottom: none; }

        .activity-marker {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        /* Activity Accents */
        .odometer-accent { background: rgba(128, 0, 0, 0.05); color: var(--maroon); }
        .billing-accent { background: rgba(201, 168, 76, 0.05); color: var(--gold); }
        .driver-accent { background: rgba(31, 41, 55, 0.05); color: var(--text-main); }
        .service-accent { background: rgba(22, 163, 74, 0.05); color: #16a34a; }

        .activity-content { flex: 1; }

        .activity-top {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }

        .activity-user {
            font-size: 14px;
            font-weight: 800;
            color: var(--text-main);
        }

        .activity-time {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 600;
        }

        .activity-msg {
            font-size: 14px;
            color: #4b5563;
            margin: 0;
            line-height: 1.4;
        }

        .activity-msg strong { color: var(--text-main); font-weight: 700; }

        /* SPECIAL STATES */
        .sc-icon-circle.danger-tint {
            background: rgba(220, 38, 38, 0.05);
            color: #dc2626;
        }
        .sc-number.danger-text {
            color: #dc2626;
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
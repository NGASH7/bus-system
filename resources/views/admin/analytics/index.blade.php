<x-admin-layout>
    <div class="analytics-page-wrapper fade-up">
        <div class="analytics-container">
            <!-- PAGE HEADER & CUSTOM FILTERS -->
            <div class="page-header-premium no-print">
                <div class="header-main">
                    <h1 class="premium-title">System Analytics</h1>
                    <div class="header-actions">
                        <form action="{{ route('admin.analytics.index') }}" method="GET" class="date-range-form">
                            <input type="hidden" name="filter" value="{{ $filter }}">
                            <div class="range-inputs">
                                <div class="input-group">
                                    <label>From Date</label>
                                    <input type="date" name="from_date" value="{{ $fromDate }}" class="modern-input">
                                </div>
                                <div class="input-group">
                                    <label>To Date</label>
                                    <input type="date" name="to_date" value="{{ $toDate }}" class="modern-input">
                                </div>
                                <button type="submit" class="btn-filter" title="Apply Custom Range">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </form>

                        <div class="filter-tabs">
                            <a href="{{ route('admin.analytics.index', ['filter' => 'daily']) }}" class="filter-tab {{ $filter == 'daily' ? 'active' : '' }}">Daily</a>
                            <a href="{{ route('admin.analytics.index', ['filter' => 'weekly']) }}" class="filter-tab {{ $filter == 'weekly' ? 'active' : '' }}">Weekly</a>
                            <a href="{{ route('admin.analytics.index', ['filter' => 'monthly']) }}" class="filter-tab {{ $filter == 'monthly' ? 'active' : '' }}">Monthly</a>
                        </div>

                        <button onclick="window.print()" class="btn-print" title="Print Analytics Report">
                            <i class="fas fa-print"></i>
                        </button>
                    </div>
                </div>
                <div class="header-info">
                    <p class="premium-subtitle">
                        Reporting Period: <span class="active-period">
                            {{ $filterStartDate->format('d M Y') }} — {{ $filterEndDate->format('d M Y') }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- PRINT HEADER -->
            <div class="print-header only-print">
                <div class="print-logo">
                    <img src="{{ asset('Images/image.png') }}" alt="Logo">
                    <h2>Mwigito Excel Bus Management System</h2>
                </div>
                <div class="print-meta">
                    <p><strong>Report Type:</strong> Analytics Performance Report</p>
                    <p><strong>Period:</strong> {{ $filterStartDate->format('d M Y') }} to {{ $filterEndDate->format('d M Y') }}</p>
                    <p><strong>Date Generated:</strong> {{ date('F d, Y H:i') }}</p>
                </div>
            </div>

            <!-- TOP STATS CARDS -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon bg-blue-light"><i class="fas fa-bus"></i></div>
                    <div class="stat-info">
                        <span class="stat-label">Total Fleet</span>
                        <h2 class="stat-value">{{ $fleetStats['total'] }}</h2>
                        <span class="stat-trend text-blue">{{ $fleetStats['active'] }} Active</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-green-light"><i class="fas fa-hand-holding-usd"></i></div>
                    <div class="stat-info">
                        <span class="stat-label">Period Revenue</span>
                        <h2 class="stat-value">KES {{ number_format($financials['revenue'], 0) }}</h2>
                        <span class="stat-trend text-green">
                            @if($financials['revenue'] > 0)
                                +{{ number_format(($financials['profit'] / $financials['revenue']) * 100, 1) }}% Profit
                            @else
                                0% Margin
                            @endif
                        </span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-maroon-light"><i class="fas fa-tools"></i></div>
                    <div class="stat-info">
                        <span class="stat-label">Period Expenses</span>
                        <h2 class="stat-value">KES {{ number_format($financials['expenses'], 0) }}</h2>
                        <span class="stat-trend text-maroon">{{ count($bookingData) }} States</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-gold-light"><i class="fas fa-users"></i></div>
                    <div class="stat-info">
                        <span class="stat-label">System Users</span>
                        <h2 class="stat-value">{{ $driverCount + $userCount }}</h2>
                        <span class="stat-trend text-gold">{{ $driverCount }} Drivers</span>
                    </div>
                </div>
            </div>

            <!-- CHARTS GRID -->
            <div class="charts-grid mt-8">
                <!-- DUAL LINE TREND -->
                <div class="premium-card chart-card lg:col-span-2">
                    <div class="chart-header">
                        <div class="title-with-legend">
                            <div>
                                <h3 class="chart-title">Income vs Expenditure Trend</h3>
                                <p class="chart-subtitle">Historical financial flow comparison</p>
                            </div>
                            <div class="chart-legend-custom">
                                <div class="legend-item"><span class="dot blue"></span> Income</div>
                                <div class="legend-item"><span class="dot red"></span> Expenditure</div>
                            </div>
                        </div>
                    </div>
                    <div class="chart-body">
                        <canvas id="revenueLineChart"></canvas>
                    </div>
                </div>

                <!-- BOOKING MIX PIE -->
                <div class="premium-card chart-card">
                    <div class="chart-header">
                        <h3 class="chart-title">Booking Mix</h3>
                        <p class="chart-subtitle">Status distribution</p>
                    </div>
                    <div class="chart-body pie-container">
                        <canvas id="bookingPieChart"></canvas>
                    </div>
                </div>

                <!-- PROFIT BAR CHART -->
                <div class="premium-card chart-card lg:col-span-2">
                    <div class="chart-header">
                        <h3 class="chart-title">Periodic Profit Analysis</h3>
                        <p class="chart-subtitle">Net profit generated in each {{ $filter == 'monthly' ? 'month' : ($filter == 'weekly' ? 'week' : 'day') }}</p>
                    </div>
                    <div class="chart-body">
                        <canvas id="profitBarChart"></canvas>
                    </div>
                </div>

                <!-- FINANCIAL SUMMARY BAR -->
                <div class="premium-card chart-card">
                    <div class="chart-header">
                        <h3 class="chart-title">Total Financial Mix</h3>
                        <p class="chart-subtitle">Aggregate performance</p>
                    </div>
                    <div class="chart-body">
                        <canvas id="financialChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="print-footer only-print">
                <p>&copy; {{ date('Y') }} Mwigito Excel Bus Management System. Confidential Report.</p>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Chart.defaults.font.family = "'Inter', sans-serif";
            
            // 1. DUAL LINE CHART
            const trendCtx = document.getElementById('revenueLineChart').getContext('2d');
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode(array_column($finalTrend, 'label')) !!},
                    datasets: [
                        {
                            label: 'Income',
                            data: {!! json_encode(array_column($finalTrend, 'revenue')) !!},
                            borderColor: '#2563eb',
                            backgroundColor: 'rgba(37, 99, 235, 0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3,
                            pointRadius: 4,
                            pointBackgroundColor: '#2563eb'
                        },
                        {
                            label: 'Expenditure',
                            data: {!! json_encode(array_column($finalTrend, 'expenses')) !!},
                            borderColor: '#dc2626',
                            backgroundColor: 'rgba(220, 38, 38, 0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3,
                            pointRadius: 4,
                            pointBackgroundColor: '#dc2626'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false } },
                    scales: { 
                        y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, 
                        x: { grid: { display: false } } 
                    }
                }
            });

            // 2. PROFIT BAR CHART (NEW)
            const profitCtx = document.getElementById('profitBarChart').getContext('2d');
            new Chart(profitCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_column($finalTrend, 'label')) !!},
                    datasets: [{
                        label: 'Net Profit',
                        data: {!! json_encode(array_column($finalTrend, 'profit')) !!},
                        backgroundColor: function(context) {
                            const index = context.dataIndex;
                            const value = context.dataset.data[index];
                            return value >= 0 ? 'rgba(201, 168, 76, 0.8)' : 'rgba(220, 38, 38, 0.8)';
                        },
                        borderRadius: 10,
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Profit: KES ' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: { 
                        y: { 
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            ticks: { callback: function(value) { return 'KES ' + value.toLocaleString(); } }
                        }, 
                        x: { grid: { display: false } } 
                    }
                }
            });

            // 3. Booking Pie Chart
            const bookingCtx = document.getElementById('bookingPieChart').getContext('2d');
            new Chart(bookingCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode(array_keys($bookingData)) !!},
                    datasets: [{
                        data: {!! json_encode(array_values($bookingData)) !!},
                        backgroundColor: ['#800000', '#c9a84c', '#16a34a', '#2563eb', '#d97706'],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 15 } } }
                }
            });

            // 4. Financial Summary Bar
            const financialCtx = document.getElementById('financialChart').getContext('2d');
            new Chart(financialCtx, {
                type: 'bar',
                data: {
                    labels: ['Income', 'Exp.', 'Profit'],
                    datasets: [{
                        data: [{{ $financials['revenue'] }}, {{ $financials['expenses'] }}, {{ $financials['profit'] }}],
                        backgroundColor: ['#2563eb', '#dc2626', '#c9a84c'],
                        borderRadius: 10,
                        barThickness: 40
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, display: false }, x: { grid: { display: false } } }
                }
            });
        });
    </script>

    <style>
        :root {
            --maroon: #800000;
            --maroon-dark: #600000;
            --gold: #c9a84c;
            --text-dark: #1a202c;
            --text-muted: #718096;
            --white: #ffffff;
            --radius: 24px;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .analytics-page-wrapper { padding: 10px 20px 40px; font-family: 'Inter', sans-serif; }
        .analytics-container { max-width: 1440px; margin: 0 auto; }

        /* HEADER */
        .page-header-premium { margin-bottom: 40px; }
        .header-main { display: flex; align-items: center; justify-content: space-between; gap: 20px; margin-bottom: 5px; }
        .premium-title { font-family: 'Outfit', sans-serif; font-size: 32px; font-weight: 900; color: var(--text-dark); text-transform: uppercase; margin: 0; }
        .header-actions { display: flex; align-items: center; gap: 15px; }

        /* DATE RANGE FORM */
        .date-range-form { background: #fff; padding: 8px 15px; border: 1px solid #edf2f7; border-radius: 12px; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
        .range-inputs { display: flex; align-items: center; gap: 12px; }
        .input-group { display: flex; flex-direction: column; }
        .input-group label { font-size: 9px; font-weight: 900; text-transform: uppercase; color: var(--text-muted); margin-bottom: 2px; }
        .modern-input { border: none; background: #f8fafc; font-size: 11px; font-weight: 800; color: var(--text-dark); padding: 4px 8px; border-radius: 6px; outline: none; }
        .btn-filter { background: #f1f5f9; color: var(--maroon); border: none; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; }
        .btn-filter:hover { background: var(--maroon); color: white; transform: rotate(180deg); }

        .filter-tabs { display: flex; background: #f1f5f9; padding: 4px; border-radius: 12px; }
        .filter-tab { padding: 8px 16px; font-size: 12px; font-weight: 800; color: var(--text-muted); text-decoration: none; border-radius: 8px; transition: all 0.2s; }
        .filter-tab.active { background: var(--white); color: var(--maroon); box-shadow: 0 2px 8px rgba(0,0,0,0.05); }

        .btn-print { background: var(--maroon); color: white; border: none; width: 40px; height: 40px; border-radius: 12px; font-size: 14px; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; }
        .btn-print:hover { background: var(--maroon-dark); transform: scale(1.05); }

        .active-period { color: var(--maroon); font-weight: 800; }
        .premium-subtitle { color: var(--text-muted); font-size: 14px; font-weight: 500; }

        /* STATS */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; }
        .stat-card { background: var(--white); padding: 25px; border-radius: var(--radius); box-shadow: var(--shadow); display: flex; align-items: center; gap: 20px; border: 1px solid #f1f5f9; }
        .stat-icon { width: 60px; height: 60px; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 24px; }
        .bg-blue-light { background: #eff6ff; color: #2563eb; }
        .bg-green-light { background: #f0fdf4; color: #16a34a; }
        .bg-maroon-light { background: #fff5f5; color: #800000; }
        .bg-gold-light { background: #fefce8; color: #c9a84c; }
        .stat-label { font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-value { font-family: 'Outfit', sans-serif; font-size: 24px; font-weight: 900; color: var(--text-dark); margin: 4px 0; }

        /* CHARTS */
        .charts-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; }
        @media (max-width: 1200px) { .charts-grid { grid-template-columns: 1fr; } .lg\:col-span-2 { grid-column: span 1 / span 1; } }
        .premium-card { background: var(--white); border-radius: var(--radius); border: 1px solid #f1f5f9; box-shadow: var(--shadow); }
        .chart-card { padding: 30px; }
        .chart-header { margin-bottom: 25px; }
        .title-with-legend { display: flex; justify-content: space-between; align-items: flex-start; }
        .chart-title { font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 800; color: var(--text-dark); margin: 0; }
        .chart-subtitle { font-size: 12px; color: var(--text-muted); margin-top: 5px; }
        
        .chart-legend-custom { display: flex; gap: 15px; }
        .legend-item { display: flex; align-items: center; gap: 6px; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; }
        .dot { width: 8px; height: 8px; border-radius: 50%; }
        .dot.blue { background: #2563eb; }
        .dot.red { background: #dc2626; }

        .chart-body { height: 320px; position: relative; }
        .pie-container { display: flex; align-items: center; justify-content: center; }

        .lg\:col-span-2 { grid-column: span 2 / span 2; }
        .mt-8 { margin-top: 32px; }

        /* PRINT */
        .only-print { display: none; }
        @media print {
            .no-print, .sidebar-wrapper, .header-wrapper, .sidebar-nav, .sidebar-footer { display: none !important; }
            .analytics-page-wrapper { padding: 0; background: white; }
            .analytics-container { max-width: 100%; margin: 0; padding: 20px; }
            .premium-card { box-shadow: none; border: 1px solid #eee; }
            .only-print { display: block; }
            .print-header { margin-bottom: 40px; display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #800000; padding-bottom: 20px; }
            .print-logo img { height: 50px; }
            .print-logo h2 { font-family: 'Outfit', sans-serif; font-size: 20px; font-weight: 900; color: #800000; margin: 0; }
            .print-meta p { margin: 5px 0; font-size: 12px; font-weight: 700; color: #555; }
            .print-footer { margin-top: 50px; text-align: center; font-size: 10px; color: #888; border-top: 1px solid #eee; padding-top: 10px; }
            canvas { max-height: 200px !important; }
            .charts-grid { display: block; }
            .chart-card { margin-bottom: 20px; page-break-inside: avoid; }
        }

        .fade-up { animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</x-admin-layout>

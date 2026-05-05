<x-admin-layout>
    <div class="logs-page-wrapper fade-up">
        <div class="logs-container">
            <!-- PAGE HEADER -->
            <div class="page-header-premium">
                <div class="header-main">
                    <h1 class="premium-title">System Activity Log</h1>
                    <div class="header-line"></div>
                </div>
                <p class="premium-subtitle">Full historical record of system events, maintenance, and revenue.</p>
            </div>

            <!-- LOGS FEED -->
            <div class="premium-card mt-8">
                <div class="card-header-flex">
                    <h3 class="card-title">Detailed System Activity</h3>
                </div>
                
                <div class="activity-container">
                    @forelse($activities as $activity)
                        <div class="activity-row {{ $loop->last ? 'last-row' : '' }}">
                            <div class="activity-marker {{ $activity['accent'] }}">
                                <i class="{{ $activity['icon'] }}"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-top">
                                    <div class="flex items-center gap-3">
                                        <span class="activity-user">{{ $activity['title'] }}</span>
                                        <span class="type-tag {{ $activity['type'] }}">{{ strtoupper($activity['type']) }}</span>
                                    </div>
                                    <span class="activity-time">{{ $activity['time']->format('d M Y, H:i') }} ({{ $activity['time']->diffForHumans() }})</span>
                                </div>
                                <p class="activity-msg">{!! $activity['msg'] !!}</p>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state py-20 text-center">
                            <i class="fas fa-history text-muted text-5xl mb-4"></i>
                            <p class="text-muted font-bold text-lg">No system activities recorded yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --maroon: #800000;
            --maroon-light: rgba(128, 0, 0, 0.05);
            --gold: #c9a84c;
            --text-dark: #1a202c;
            --text-muted: #718096;
            --white: #ffffff;
            --radius: 24px;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .logs-page-wrapper { padding: 20px 40px; }
        .logs-container { max-width: 1200px; margin: 0 auto; }

        .page-header-premium { margin-bottom: 30px; }
        .header-main { display: flex; align-items: center; gap: 20px; margin-bottom: 10px; }
        .premium-title { font-family: 'Outfit', sans-serif; font-size: 28px; font-weight: 900; color: var(--text-dark); text-transform: uppercase; margin: 0; }
        .header-line { flex: 1; height: 2px; background: linear-gradient(to right, var(--maroon), transparent); opacity: 0.1; }
        .premium-subtitle { color: var(--text-muted); font-size: 15px; }

        .premium-card { background: var(--white); border-radius: var(--radius); border: 1px solid #f1f5f9; box-shadow: var(--shadow); overflow: hidden; }
        .card-header-flex { padding: 25px 40px; border-bottom: 1px solid #f8fafc; background: #fbfcfd; }
        .card-title { font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 800; color: var(--text-dark); margin: 0; }

        .activity-container { padding: 10px 0; }
        .activity-row { padding: 25px 40px; display: flex; gap: 25px; align-items: flex-start; border-bottom: 1px solid #f8fafc; transition: background 0.2s; }
        .activity-row:hover { background: #fcfcfc; }
        .activity-row.last-row { border-bottom: none; }

        .activity-marker { width: 50px; height: 50px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
        
        /* Accents */
        .odometer-accent { background: rgba(128, 0, 0, 0.05); color: var(--maroon); }
        .billing-accent { background: rgba(201, 168, 76, 0.05); color: var(--gold); }
        .driver-accent { background: rgba(31, 41, 55, 0.05); color: var(--text-dark); }
        .service-accent { background: rgba(22, 163, 74, 0.05); color: #16a34a; }

        .activity-content { flex: 1; }
        .activity-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
        .activity-user { font-size: 15px; font-weight: 800; color: var(--text-dark); }
        .activity-time { font-size: 13px; color: var(--text-muted); font-weight: 600; }
        .activity-msg { font-size: 15px; color: #4b5563; margin: 0; line-height: 1.6; }

        .type-tag { font-size: 9px; font-weight: 900; padding: 2px 8px; border-radius: 6px; letter-spacing: 0.5px; }
        .type-tag.maintenance { background: #f0fdf4; color: #16a34a; }
        .type-tag.revenue { background: #fffbeb; color: #d97706; }
        .type-tag.driver { background: #f1f5f9; color: #475569; }

        .fade-up { animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) both; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        
        .mt-8 { margin-top: 32px; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .gap-3 { gap: 12px; }
    </style>
</x-admin-layout>

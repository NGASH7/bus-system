<x-admin-layout>
    <div class="inspection-page-wrapper fade-up">
        <div class="inspection-container">
            <!-- PAGE HEADER -->
            <div class="page-header-premium">
                <div class="header-main">
                    <h1 class="premium-title">Fleet Inspection Management</h1>
                    <div class="header-line"></div>
                </div>
                <p class="premium-subtitle">Monitor and update safety inspection status for the entire fleet.</p>
            </div>

            <!-- STATS OVERVIEW -->
            <div class="stats-row mt-6">
                <div class="mini-stat-card">
                    <span class="mini-label">Total Buses</span>
                    <span class="mini-value">{{ $buses->count() }}</span>
                </div>
                <div class="mini-stat-card">
                    <span class="mini-label">Valid Inspections</span>
                    <span class="mini-value text-green">{{ $buses->where('inspection_status', 'ok')->count() }}</span>
                </div>
                <div class="mini-stat-card">
                    <span class="mini-label">Expiring Soon</span>
                    <span class="mini-value text-gold">{{ $buses->whereIn('inspection_status', ['warning', 'critical'])->count() }}</span>
                </div>
                <div class="mini-stat-card">
                    <span class="mini-label">Expired</span>
                    <span class="mini-value text-red">{{ $buses->where('inspection_status', 'expired')->count() }}</span>
                </div>
            </div>

            <!-- INSPECTION TABLE -->
            <div class="premium-card mt-8">
                <div class="card-header-flex">
                    <h3 class="card-title">Fleet Inspection Status</h3>
                </div>
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Bus Plate</th>
                                <th>Assigned Driver</th>
                                <th>Certificate No.</th>
                                <th>Expiry Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($buses as $bus)
                                <tr>
                                    <td>
                                        <div class="plate-cell">
                                            <i class="fas fa-bus"></i>
                                            <strong>{{ $bus->plate_number }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ $bus->driver->name ?? 'Unassigned' }}</td>
                                    <td><code class="certificate-code">{{ $bus->inspection_certificate ?? 'N/A' }}</code></td>
                                    <td>
                                        <span class="{{ $bus->inspection_status == 'expired' ? 'text-red font-bold' : ($bus->inspection_status == 'critical' ? 'text-orange font-bold' : '') }}">
                                            {{ $bus->inspection_expiry ? $bus->inspection_expiry->format('d M, Y') : 'Not Set' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $bus->inspection_status }}">
                                            {{ ucfirst($bus->inspection_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.inspection.edit', $bus->id) }}" class="btn-action edit" title="Update Inspection">
                                            <i class="fas fa-edit"></i> Update
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-8">No buses found in the system.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --maroon: #800000;
            --maroon-light: rgba(128, 0, 0, 0.05);
            --gold: #c9a84c;
            --gold-light: rgba(201, 168, 76, 0.1);
            --text-dark: #1a202c;
            --text-muted: #718096;
            --white: #ffffff;
            --radius: 20px;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .inspection-page-wrapper { padding: 10px 20px 40px; }
        .inspection-container { max-width: 1400px; margin: 0 auto; }

        /* HEADER */
        .page-header-premium { margin-bottom: 30px; }
        .header-main { display: flex; align-items: center; gap: 20px; margin-bottom: 10px; }
        .premium-title { font-family: 'Outfit', sans-serif; font-size: 28px; font-weight: 900; color: var(--text-dark); text-transform: uppercase; margin: 0; }
        .header-line { flex: 1; height: 2px; background: linear-gradient(to right, var(--maroon), transparent); opacity: 0.1; }
        .premium-subtitle { color: var(--text-muted); font-size: 15px; }

        /* MINI STATS */
        .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
        .mini-stat-card { background: var(--white); padding: 20px; border-radius: 16px; border: 1px solid #f1f5f9; display: flex; flex-direction: column; gap: 5px; }
        .mini-label { font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }
        .mini-value { font-family: 'Outfit', sans-serif; font-size: 24px; font-weight: 900; color: var(--text-dark); }
        
        /* TABLE */
        .premium-card { background: var(--white); border-radius: var(--radius); border: 1px solid #f1f5f9; box-shadow: var(--shadow); overflow: hidden; }
        .card-header-flex { padding: 25px 30px; border-bottom: 1px solid #f8fafc; }
        .card-title { font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 800; color: var(--text-dark); margin: 0; }
        
        .modern-table { width: 100%; border-collapse: collapse; }
        .modern-table th { background: #f8fafc; padding: 15px 30px; text-align: left; font-size: 12px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; }
        .modern-table td { padding: 18px 30px; border-bottom: 1px solid #f8fafc; font-size: 14px; color: var(--text-dark); }
        
        .plate-cell { display: flex; align-items: center; gap: 12px; }
        .plate-cell i { color: var(--maroon); font-size: 16px; }
        
        .certificate-code { background: #f1f5f9; padding: 4px 8px; border-radius: 6px; font-family: 'Monaco', monospace; font-size: 12px; color: var(--maroon); }
        
        /* STATUS BADGES */
        .status-badge { padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 800; text-transform: uppercase; }
        .status-badge.ok { background: #f0fdf4; color: #16a34a; }
        .status-badge.warning { background: #fffbeb; color: #d97706; }
        .status-badge.critical { background: #fff7ed; color: #ea580c; }
        .status-badge.expired { background: #fef2f2; color: #dc2626; }
        .status-badge.none { background: #f1f5f9; color: #64748b; }

        /* ACTIONS */
        .btn-action { text-decoration: none; padding: 8px 16px; border-radius: 10px; font-size: 12px; font-weight: 700; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px; }
        .btn-action.edit { background: var(--maroon-light); color: var(--maroon); }
        .btn-action.edit:hover { background: var(--maroon); color: white; }

        .text-green { color: #16a34a; }
        .text-gold { color: #d97706; }
        .text-red { color: #dc2626; }
        .text-orange { color: #ea580c; }
        .font-bold { font-weight: 800; }

        .fade-up { animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) both; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        
        .mt-6 { margin-top: 24px; }
        .mt-8 { margin-top: 32px; }
    </style>
</x-admin-layout>

<x-admin-layout>
    <div class="driver-index-wrapper fade-up">
        <div class="max-w-[1440px] mx-auto">
            
            <div class="welcome-header-minimal mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="page-title">Fleet Operators</h1>
                        <p class="text-sm text-gray-500 mt-1">Manage your professional drivers and their system access.</p>
                    </div>
                    <a href="{{ route('admin.drivers.create') }}" class="btn-add-driver">
                        <i class="fas fa-user-plus mr-2"></i> Register New Driver
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-100 text-green-700 rounded-xl flex items-center shadow-sm">
                    <i class="fas fa-check-circle mr-3"></i>
                    {{ session('success') }}
                </div>
            @endif

            <div class="drivers-table-card">
                <div class="table-responsive">
                    <table class="premium-table">
                        <thead>
                            <tr>
                                <th>Driver Name</th>
                                <th>Email Address</th>
                                <th>Account Status</th>
                                <th>ID Number</th>
                                <th>Joined Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($drivers as $driver)
                            <tr>
                                <td>
                                    <div class="flex items-center">
                                        <div class="driver-avatar">
                                            {{ substr($driver->name, 0, 1) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="driver-name-text">{{ $driver->name }}</div>
                                            <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">CERTIFIED OPERATOR</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="email-text">{{ $driver->email }}</div>
                                </td>
                                <td>
                                    @if($driver->must_change_password)
                                        <span class="status-badge pending">
                                            <i class="fas fa-clock mr-1 text-[10px]"></i> Pending Reset
                                        </span>
                                    @else
                                        <span class="status-badge active">
                                            <i class="fas fa-check-circle mr-1 text-[10px]"></i> Active
                                        </span>
                                    @endif
                                </td>
                                <td><span class="id-number">{{ $driver->national_id ?? 'ID-' . (1000 + $driver->id) }}</span></td>
                                <td><span class="date-text">{{ $driver->created_at->format('d M, Y') }}</span></td>
                                <td class="text-center">
                                    <div class="actions-flex">
                                        <a href="{{ route('admin.drivers.show', $driver->id) }}" class="btn-action view" title="View Dossier">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.drivers.edit', $driver->id) }}" class="btn-action edit" title="Edit Profile">
                                            <i class="fas fa-pen-nib"></i>
                                        </a>
                                        <form action="{{ route('admin.drivers.destroy', $driver->id) }}" method="POST" onsubmit="return confirm('Archive this driver? They will lose all system access.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action delete" title="Remove Driver">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="empty-state-row">
                                    <div class="empty-icon"><i class="fas fa-users-slash"></i></div>
                                    <p>No active drivers found in the system fleet.</p>
                                </td>
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
            --maroon-dark: #600000;
            --gold: #c9a84c;
            --text-main: #1a202c;
            --text-muted: #718096;
        }

        .driver-index-wrapper { padding: 0 10px; }

        .page-title {
            font-family: 'Outfit', sans-serif;
            font-size: 28px;
            font-weight: 850;
            color: var(--text-main);
            letter-spacing: -0.5px;
        }

        .btn-add-driver {
            background: var(--maroon);
            color: white;
            padding: 12px 24px;
            border-radius: 14px;
            font-weight: 850;
            text-decoration: none;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            box-shadow: 0 10px 20px rgba(128, 0, 0, 0.15);
        }

        .btn-add-driver:hover {
            background: var(--maroon-dark);
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(128, 0, 0, 0.25);
        }

        /* TABLE STYLING */
        .drivers-table-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            overflow: hidden;
        }

        .premium-table { width: 100%; border-collapse: collapse; }
        .premium-table th { text-align: left; padding: 20px; font-size: 11px; font-weight: 900; text-transform: uppercase; color: var(--text-muted); letter-spacing: 1px; border-bottom: 1px solid #f1f5f9; background: #fafafa; }
        .premium-table td { padding: 20px; border-bottom: 1px solid #f8fafc; vertical-align: middle; }

        .driver-avatar {
            width: 40px;
            height: 40px;
            background: rgba(128, 0, 0, 0.08);
            color: var(--maroon);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 18px;
            text-transform: uppercase;
        }

        .driver-name-text { font-family: 'Outfit', sans-serif; font-size: 15px; font-weight: 850; color: var(--text-main); line-height: 1.2; }
        .email-text { font-size: 14px; color: #4b5563; font-weight: 600; }
        .id-number { font-family: 'Space Mono', monospace; font-size: 12px; font-weight: 700; color: var(--text-muted); }
        .date-text { font-size: 13px; font-weight: 600; color: #64748b; }

        /* STATUS BADGES */
        .status-badge {
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 850;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
        }
        .status-badge.active { background: #f0fdf4; color: #16a34a; }
        .status-badge.pending { background: #fffbeb; color: #d97706; }

        /* ACTIONS */
        .actions-flex { display: flex; align-items: center; justify-content: center; gap: 8px; }
        .actions-flex form { display: inline-block; margin: 0; }

        .btn-action {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-action.view { background: rgba(16, 185, 129, 0.05); color: #10b981; }
        .btn-action.view:hover { background: #10b981; color: white; transform: rotate(-5deg); }
        .btn-action.edit { background: rgba(59, 130, 246, 0.05); color: #3b82f6; }
        .btn-action.edit:hover { background: #3b82f6; color: white; transform: rotate(-5deg); }
        .btn-action.delete { background: rgba(220, 38, 38, 0.05); color: #dc2626; }
        .btn-action.delete:hover { background: #dc2626; color: white; transform: rotate(5deg); }

        .empty-state-row { padding: 80px 20px !important; text-align: center; }
        .empty-icon { font-size: 50px; color: #e2e8f0; margin-bottom: 20px; }

        .fade-up { animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-admin-layout>

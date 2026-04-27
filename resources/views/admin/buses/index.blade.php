<x-admin-layout>
    <div class="fade-up">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
            <h1 style="font-family:'Outfit',sans-serif; font-size:28px; font-weight:800; color:#1f2937;">Bus Fleet</h1>
            <a href="{{ route('admin.buses.create') }}" class="btn-add">
                <i class="fas fa-plus"></i> Add New Bus
            </a>
        </div>

        @if(session('success'))
            <div class="alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Plate Number</th>
                        <th>Model</th>
                        <th>Capacity</th>
                        <th>Insurance Expiry</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($buses as $i => $bus)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><strong>{{ $bus->plate_number }}</strong></td>
                            <td>{{ $bus->model }}</td>
                            <td>{{ $bus->capacity }} seats</td>
                            <td>
                                @if($bus->insurance_expiry)
                                    <span class="{{ \Carbon\Carbon::parse($bus->insurance_expiry)->isPast() ? 'text-red' : 'text-green' }}">
                                        {{ \Carbon\Carbon::parse($bus->insurance_expiry)->format('M d, Y') }}
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($bus->is_active)
                                    <span class="badge active">Active</span>
                                @else
                                    <span class="badge inactive">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('admin.buses.edit', $bus) }}" class="btn-edit" title="Edit">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <a href="{{ route('admin.buses.show', $bus) }}" class="btn-view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.buses.destroy', $bus) }}" method="POST" onsubmit="return confirm('Delete {{ $bus->plate_number }}? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-state">
                                <i class="fas fa-bus" style="font-size:32px; color:#d1d5db; margin-bottom:12px; display:block;"></i>
                                No buses added yet. Click "Add New Bus" to get started.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .btn-add {
            background: #800000; color: white; padding: 10px 20px; border-radius: 10px;
            text-decoration: none; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px;
            transition: all 0.2s; box-shadow: 0 4px 14px rgba(128,0,0,0.25);
        }
        .btn-add:hover { background: #600000; transform: translateY(-2px); }

        .alert-success {
            background: #ecfdf5; color: #065f46; padding: 14px 20px; border-radius: 10px;
            margin-bottom: 20px; font-size: 14px; display: flex; align-items: center; gap: 10px;
            border: 1px solid #a7f3d0;
        }

        .table-wrap {
            background: white; border-radius: 16px; overflow: hidden;
            border: 1px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        }

        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th {
            background: #fafafa; padding: 14px 20px; text-align: left; font-size: 12px;
            font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;
            border-bottom: 1px solid #e5e7eb;
        }
        .data-table td {
            padding: 16px 20px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #374151;
        }
        .data-table tbody tr:hover { background: #fffafa; }
        .data-table tbody tr:last-child td { border-bottom: none; }

        .badge {
            padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;
        }
        .badge.active { background: #ecfdf5; color: #065f46; }
        .badge.inactive { background: #fef2f2; color: #991b1b; }

        .text-red { color: #dc2626; font-weight: 600; }
        .text-green { color: #16a34a; font-weight: 600; }
        .text-muted { color: #9ca3af; }

        .actions { display: flex; gap: 8px; align-items: center; }
        .btn-edit, .btn-view, .btn-delete {
            width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center;
            justify-content: center; font-size: 13px; border: none; cursor: pointer; transition: all 0.2s;
        }
        .btn-edit { background: #f5f3ff; color: #6d28d9; text-decoration: none; }
        .btn-edit:hover { background: #6d28d9; color: white; }
        .btn-view { background: #eff6ff; color: #2563eb; text-decoration: none; }
        .btn-view:hover { background: #2563eb; color: white; }
        .btn-delete { background: #fef2f2; color: #dc2626; }
        .btn-delete:hover { background: #dc2626; color: white; }

        .empty-state { text-align: center; padding: 48px 20px !important; color: #9ca3af; font-size: 14px; }

        @keyframes fadeUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        .fade-up { animation: fadeUp 0.5s ease-out forwards; }
    </style>
</x-admin-layout>

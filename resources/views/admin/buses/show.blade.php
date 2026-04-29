<x-admin-layout>
    <div class="fade-up">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:24px;">
            <div style="display:flex; align-items:center; gap:12px;">
                <a href="{{ route('admin.buses.index') }}" class="btn-back"><i class="fas fa-arrow-left"></i></a>
                <h1 style="font-family:'Outfit',sans-serif; font-size:28px; font-weight:800; color:#1f2937;">Bus Details</h1>
            </div>
            <form action="{{ route('admin.buses.destroy', $bus) }}" method="POST" onsubmit="return confirm('Delete {{ $bus->plate_number }}? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete-main">
                    <i class="fas fa-trash"></i> Delete Bus
                </button>
            </form>
        </div>

        <div class="details-wrap">
            <div class="photo-card">
                @if($bus->photo_path)
                    <img src="{{ asset('storage/' . $bus->photo_path) }}" alt="Bus photo" class="bus-photo">
                @else
                    <div class="photo-placeholder">
                        <i class="fas fa-bus"></i>
                        <span>No photo uploaded</span>
                    </div>
                @endif
            </div>

            <div class="info-card">
                <div class="info-row">
                    <span class="label">Plate Number</span>
                    <span class="value strong">{{ $bus->plate_number }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Model</span>
                    <span class="value">{{ $bus->model }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Capacity</span>
                    <span class="value">{{ $bus->capacity }} seats</span>
                </div>
                <div class="info-row">
                    <span class="label">Insurance Expiry</span>
                    <span class="value">
                        {{ $bus->insurance_expiry ? \Carbon\Carbon::parse($bus->insurance_expiry)->format('M d, Y') : '—' }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="label">Status</span>
                    <span class="value">
                        <span class="badge {{ $bus->is_active ? 'active' : 'inactive' }}">
                            {{ $bus->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </span>
                </div>
            </div>
        </div>

        @if($bus->driver)
        <!-- OPERATOR ASSIGNMENT CARD -->
        <div class="operator-card mt-6">
            <div class="card-header-inner mb-6">
                <h3 class="section-title"><i class="fas fa-id-card-clip mr-2 text-maroon"></i> Primary Operator (Driver)</h3>
            </div>
            
            <div class="operator-grid">
                <div class="operator-profile">
                    <div class="operator-avatar-large">
                        {{ substr($bus->driver->name, 0, 1) }}
                    </div>
                    <div class="ml-4">
                        <h4 class="op-name">{{ $bus->driver->name }}</h4>
                        <p class="op-email">{{ $bus->driver->email }}</p>
                    </div>
                </div>

                <div class="op-details-list">
                    <div class="op-detail-item">
                        <span class="op-label">Phone Contact</span>
                        <span class="op-value">{{ $bus->driver->phone_number ?? 'Not Provided' }}</span>
                    </div>
                    <div class="op-detail-item">
                        <span class="op-label">License Number</span>
                        <span class="op-value">{{ $bus->driver->license_number ?? 'Not Recorded' }}</span>
                    </div>
                    <div class="op-detail-item">
                        <span class="op-label">License Expiry</span>
                        <span class="op-value {{ $bus->driver->license_expiry && \Carbon\Carbon::parse($bus->driver->license_expiry)->isPast() ? 'text-red font-bold' : '' }}">
                            {{ $bus->driver->license_expiry ? \Carbon\Carbon::parse($bus->driver->license_expiry)->format('M d, Y') : '—' }}
                        </span>
                    </div>
                    <div class="op-detail-item">
                        <span class="op-label">National ID</span>
                        <span class="op-value">{{ $bus->driver->national_id ?? '—' }}</span>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="unassigned-banner mt-6">
            <div class="flex items-center">
                <i class="fas fa-user-slash mr-4 text-2xl opacity-50"></i>
                <div>
                    <h4 class="font-bold">No Driver Assigned</h4>
                    <p class="text-sm opacity-70">This vehicle currently has no primary operator assigned. Please update the bus settings to assign a driver.</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <style>
        .details-wrap {
            display: grid;
            grid-template-columns: minmax(280px, 360px) 1fr;
            gap: 20px;
        }

        .photo-card,
        .info-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            padding: 20px;
        }

        .bus-photo {
            width: 100%;
            height: 260px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
        }

        .photo-placeholder {
            height: 260px;
            border-radius: 10px;
            border: 1px dashed #d1d5db;
            color: #9ca3af;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .photo-placeholder i {
            font-size: 28px;
            color: #cbd5e1;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .label {
            color: #6b7280;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .value {
            color: #111827;
            font-size: 15px;
        }

        .value.strong {
            font-weight: 700;
        }

        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge.active {
            background: #ecfdf5;
            color: #065f46;
        }

        .badge.inactive {
            background: #fef2f2;
            color: #991b1b;
        }

        .btn-back {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #fff;
            border: 1px solid #e5e7eb;
            color: #6b7280;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .btn-back:hover {
            color: #800000;
            border-color: #800000;
            background: #fffafa;
        }

        .btn-delete-main {
            background: #dc2626;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-delete-main:hover {
            background: #b91c1c;
        }

        @media (max-width: 960px) {
            .details-wrap {
                grid-template-columns: 1fr;
            }
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-up {
            animation: fadeUp 0.5s ease-out forwards;
        }

        .operator-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 30px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .section-title { font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 800; color: #111827; text-transform: uppercase; letter-spacing: 0.5px; }
        .text-maroon { color: #800000; }
        .text-red { color: #dc2626; }

        .operator-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 40px;
            align-items: center;
        }

        .operator-profile { display: flex; align-items: center; border-right: 1px solid #f3f4f6; padding-right: 40px; }
        .operator-avatar-large {
            width: 64px; height: 64px; background: rgba(128, 0, 0, 0.08); color: #800000;
            border-radius: 16px; display: flex; align-items: center; justify-content: center;
            font-weight: 900; font-size: 24px; text-transform: uppercase;
        }
        .op-name { font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 800; color: #111827; }
        .op-email { font-size: 13px; color: #6b7280; font-weight: 500; }

        .op-details-list { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .op-detail-item { display: flex; flex-direction: column; gap: 4px; }
        .op-label { font-size: 10px; font-weight: 900; color: #9ca3af; text-transform: uppercase; letter-spacing: 1px; }
        .op-value { font-size: 14px; font-weight: 700; color: #374151; }

        .unassigned-banner {
            background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 14px; padding: 25px; color: #64748b;
        }

        @media (max-width: 768px) {
            .operator-grid { grid-template-columns: 1fr; }
            .operator-profile { border-right: none; padding-right: 0; padding-bottom: 20px; border-bottom: 1px solid #f3f4f6; }
            .op-details-list { grid-template-columns: 1fr; }
        }
    </style>
</x-admin-layout>

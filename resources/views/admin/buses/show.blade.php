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
    </style>
</x-admin-layout>

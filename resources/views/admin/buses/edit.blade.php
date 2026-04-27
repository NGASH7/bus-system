<x-admin-layout>
    <div class="fade-up">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:24px;">
            <a href="{{ route('admin.buses.index') }}" class="btn-back"><i class="fas fa-arrow-left"></i></a>
            <h1 style="font-family:'Outfit',sans-serif; font-size:28px; font-weight:800; color:#1f2937;">Edit Bus</h1>
        </div>

        <div class="form-card">
            <form action="{{ route('admin.buses.update', $bus) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div class="form-group">
                        <label>Plate Number <span class="req">*</span></label>
                        <input type="text" name="plate_number" value="{{ old('plate_number', $bus->plate_number) }}" required>
                        @error('plate_number') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Bus Model <span class="req">*</span></label>
                        <input type="text" name="model" value="{{ old('model', $bus->model) }}" required>
                        @error('model') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Seating Capacity <span class="req">*</span></label>
                        <input type="number" name="capacity" value="{{ old('capacity', $bus->capacity) }}" min="1" required>
                        @error('capacity') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="is_active">
                            <option value="1" {{ old('is_active', $bus->is_active) ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !old('is_active', $bus->is_active) ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Insurance Expiry</label>
                        <input type="date" name="insurance_expiry" value="{{ old('insurance_expiry', $bus->insurance_expiry) }}">
                    </div>
                    <div class="form-group">
                        <label>Bus Photo</label>
                        <input type="file" name="photo" accept="image/*">
                        @error('photo') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>

                @if($bus->photo_path)
                    <div class="current-photo">
                        <span class="photo-label">Current Photo</span>
                        <img src="{{ asset('storage/' . $bus->photo_path) }}" alt="Bus photo" class="photo-preview">
                    </div>
                @endif

                <div class="form-actions">
                    <a href="{{ route('admin.buses.index') }}" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-save"><i class="fas fa-check"></i> Update Bus</button>
                </div>
            </form>
        </div>
    </div>

    @include('admin.partials.form-styles')

    <style>
        .current-photo {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #f3f4f6;
        }

        .photo-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 8px;
        }

        .photo-preview {
            width: 220px;
            max-width: 100%;
            height: 140px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
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

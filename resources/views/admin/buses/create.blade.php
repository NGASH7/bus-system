<x-admin-layout>
    <div class="fade-up">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:24px;">
            <a href="{{ route('admin.buses.index') }}" class="btn-back"><i class="fas fa-arrow-left"></i></a>
            <h1 style="font-family:'Outfit',sans-serif; font-size:28px; font-weight:800; color:#1f2937;">Add New Bus</h1>
        </div>

        <div class="form-card">
            <form action="{{ route('admin.buses.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label>Plate Number <span class="req">*</span></label>
                        <input type="text" name="plate_number" value="{{ old('plate_number') }}" placeholder="e.g. KDA 123A" required>
                        @error('plate_number') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Bus Model <span class="req">*</span></label>
                        <input type="text" name="model" value="{{ old('model') }}" placeholder="e.g. Toyota Coaster" required>
                        @error('model') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Seating Capacity <span class="req">*</span></label>
                        <input type="number" name="capacity" value="{{ old('capacity') }}" placeholder="e.g. 42" min="1" required>
                        @error('capacity') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="is_active">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Insurance Expiry</label>
                        <input type="date" name="insurance_expiry" value="{{ old('insurance_expiry') }}">
                    </div>
                    <div class="form-group">
                        <label>Primary Operator (Driver)</label>
                        <select name="driver_id">
                            <option value="">No Driver Assigned</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                    {{ $driver->name }} ({{ $driver->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('driver_id') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Bus Photo</label>
                        <input type="file" name="photo" accept="image/*">
                        @error('photo') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="form-actions">
                    <a href="{{ route('admin.buses.index') }}" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-save"><i class="fas fa-check"></i> Save Bus</button>
                </div>
            </form>
        </div>
    </div>

    @include('admin.partials.form-styles')
</x-admin-layout>

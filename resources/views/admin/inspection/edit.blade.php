<x-admin-layout>
    <div class="inspection-page-wrapper fade-up">
        <div class="inspection-container max-w-2xl">
            <!-- PAGE HEADER -->
            <div class="page-header-premium">
                <div class="header-main">
                    <h1 class="premium-title">Update Inspection</h1>
                    <div class="header-line"></div>
                </div>
                <p class="premium-subtitle">Updating safety inspection for bus: <strong>{{ $bus->plate_number }}</strong></p>
            </div>

            <div class="premium-card">
                <form action="{{ route('admin.inspection.update', $bus->id) }}" method="POST" class="premium-form">
                    @csrf
                    @method('PATCH')

                    <div class="form-grid">
                        <!-- EXPIRY DATE -->
                        <div class="form-group">
                            <label for="inspection_expiry">Inspection Expiry Date</label>
                            <div class="input-wrapper">
                                <i class="fas fa-calendar-check"></i>
                                <input type="date" name="inspection_expiry" id="inspection_expiry" 
                                    value="{{ old('inspection_expiry', $bus->inspection_expiry ? $bus->inspection_expiry->format('Y-m-d') : '') }}" 
                                    required>
                            </div>
                            @error('inspection_expiry') <span class="error">{{ $message }}</span> @enderror
                        </div>

                        <!-- CERTIFICATE NUMBER -->
                        <div class="form-group">
                            <label for="inspection_certificate">Certificate Number</label>
                            <div class="input-wrapper">
                                <i class="fas fa-certificate"></i>
                                <input type="text" name="inspection_certificate" id="inspection_certificate" 
                                    value="{{ old('inspection_certificate', $bus->inspection_certificate) }}" 
                                    placeholder="e.g. INSP-78421-2025" required>
                            </div>
                            @error('inspection_certificate') <span class="error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-actions mt-8">
                        <a href="{{ route('admin.inspection.index') }}" class="btn-cancel">Cancel</a>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> Save Inspection
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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

        .inspection-page-wrapper { padding: 40px 20px; }
        .max-w-2xl { max-width: 700px; margin: 0 auto; }

        .page-header-premium { margin-bottom: 30px; }
        .header-main { display: flex; align-items: center; gap: 20px; margin-bottom: 10px; }
        .premium-title { font-family: 'Outfit', sans-serif; font-size: 28px; font-weight: 900; color: var(--text-dark); text-transform: uppercase; margin: 0; }
        .header-line { flex: 1; height: 2px; background: linear-gradient(to right, var(--maroon), transparent); opacity: 0.1; }
        .premium-subtitle { color: var(--text-muted); font-size: 15px; }

        .premium-card { background: var(--white); border-radius: var(--radius); border: 1px solid #f1f5f9; box-shadow: var(--shadow); padding: 40px; }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; }
        @media (max-width: 640px) { .form-grid { grid-template-columns: 1fr; } }

        .form-group { display: flex; flex-direction: column; gap: 8px; }
        .form-group label { font-size: 12px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }

        .input-wrapper { position: relative; display: flex; align-items: center; }
        .input-wrapper i { position: absolute; left: 18px; color: var(--maroon); font-size: 14px; pointer-events: none; }
        .input-wrapper input { width: 100%; padding: 14px 14px 14px 45px; border-radius: 12px; border: 1.5px solid #e2e8f0; font-size: 14px; font-weight: 600; color: var(--text-dark); transition: all 0.2s; outline: none; }
        .input-wrapper input:focus { border-color: var(--maroon); box-shadow: 0 0 0 4px rgba(128, 0, 0, 0.05); }

        .form-actions { display: flex; align-items: center; justify-content: flex-end; gap: 15px; }
        .btn-cancel { text-decoration: none; padding: 12px 25px; border-radius: 12px; font-size: 14px; font-weight: 700; color: var(--text-muted); transition: all 0.2s; }
        .btn-cancel:hover { background: #f1f5f9; color: var(--text-dark); }

        .btn-submit { background: var(--maroon); color: white; border: none; padding: 12px 30px; border-radius: 12px; font-size: 14px; font-weight: 800; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 10px; }
        .btn-submit:hover { background: var(--maroon-dark); transform: translateY(-2px); box-shadow: 0 5px 15px rgba(128, 0, 0, 0.2); }

        .error { font-size: 11px; color: #dc2626; font-weight: 700; margin-top: 4px; }
        .mt-8 { margin-top: 32px; }

        .fade-up { animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) both; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</x-admin-layout>

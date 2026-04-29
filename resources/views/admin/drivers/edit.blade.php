<x-admin-layout>
    <div class="driver-create-wrapper fade-up">
        <div class="max-w-4xl mx-auto">
            
            <div class="welcome-header-minimal mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="page-title">Edit Operator Profile</h1>
                        <p class="text-sm text-gray-500 mt-1">Modify account details and manage system credentials for {{ $driver->name }}.</p>
                    </div>
                    <a href="{{ route('admin.drivers.index') }}" class="btn-back">
                        <i class="fas fa-arrow-left mr-2"></i> Fleet Overview
                    </a>
                </div>
            </div>

            <div class="registration-card">
                <form action="{{ route('admin.drivers.update', $driver->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-section-title">
                        <i class="fas fa-id-badge mr-2"></i> Profile Information
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                        <div>
                            <label for="name" class="premium-label">Driver Full Name</label>
                            <input type="text" name="name" id="name" class="premium-input @error('name') border-red-500 @enderror" value="{{ old('name', $driver->name) }}" required>
                            @error('name') <p class="text-xs text-red-500 mt-1 font-bold italic">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="phone_number" class="premium-label">Phone Number</label>
                            <input type="text" name="phone_number" id="phone_number" class="premium-input @error('phone_number') border-red-500 @enderror" value="{{ old('phone_number', $driver->phone_number) }}" placeholder="e.g. 0712345678">
                            @error('phone_number') <p class="text-xs text-red-500 mt-1 font-bold italic">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-1 gap-6 mb-10">
                        <div>
                            <label for="email" class="premium-label">Email Address (Login ID)</label>
                            <input type="email" name="email" id="email" class="premium-input @error('email') border-red-500 @enderror" value="{{ old('email', $driver->email) }}" required>
                            @error('email') <p class="text-xs text-red-500 mt-1 font-bold italic">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="form-section-title">
                        <i class="fas fa-shield-check mr-2"></i> Regulatory Compliance
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                        <div>
                            <label for="national_id" class="premium-label">National ID Number</label>
                            <input type="text" name="national_id" id="national_id" class="premium-input @error('national_id') border-red-500 @enderror" value="{{ old('national_id', $driver->national_id) }}" placeholder="ID Number">
                            @error('national_id') <p class="text-xs text-red-500 mt-1 font-bold italic">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="license_number" class="premium-label">Driving License No.</label>
                            <input type="text" name="license_number" id="license_number" class="premium-input @error('license_number') border-red-500 @enderror" value="{{ old('license_number', $driver->license_number) }}" placeholder="License Number">
                            @error('license_number') <p class="text-xs text-red-500 mt-1 font-bold italic">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="license_expiry" class="premium-label">License Expiry Date</label>
                            <input type="date" name="license_expiry" id="license_expiry" class="premium-input @error('license_expiry') border-red-500 @enderror" value="{{ old('license_expiry', $driver->license_expiry ? \Carbon\Carbon::parse($driver->license_expiry)->format('Y-m-d') : '') }}">
                            @error('license_expiry') <p class="text-xs text-red-500 mt-1 font-bold italic">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="form-section-title">
                        <i class="fas fa-key mr-2"></i> Credential Management
                    </div>

                    <div class="credential-management-box p-6 bg-gray-50 border border-gray-100 rounded-2xl mb-10">
                        <div class="flex items-center justify-between">
                            <div>
                                <h5 class="text-sm font-black text-gray-900 uppercase">Emergency Password Reset</h5>
                                <p class="text-xs text-gray-500 mt-1">Resets the user's password back to 'driver123' and forces a change on next login.</p>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="reset_password" value="1" id="reset_password" class="rounded border-gray-300 text-maroon focus:ring-maroon h-5 w-5 mr-3">
                                <label for="reset_password" class="text-xs font-bold text-maroon uppercase">Reset Now</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions mt-12">
                        <button type="submit" class="btn-register-action">
                            <i class="fas fa-save mr-2"></i> Update Operator Data
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
            --text-main: #1f2937;
        }

        .driver-create-wrapper { padding: 20px 0; }

        .page-title {
            font-family: 'Outfit', sans-serif;
            font-size: 28px;
            font-weight: 850;
            color: var(--text-main);
        }

        .btn-back {
            font-size: 13px;
            font-weight: 700;
            color: var(--maroon);
            text-decoration: none;
            padding: 8px 16px;
            border: 2px solid #eee;
            border-radius: 12px;
            transition: all 0.2s;
        }

        .btn-back:hover { border-color: var(--maroon); background: #fffafa; }

        .registration-card {
            background: white;
            border-radius: 28px;
            padding: 45px;
            box-shadow: 0 15px 45px rgba(0,0,0,0.03);
            border: 1px solid #f1f5f9;
        }

        .form-section-title {
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 900;
            color: var(--maroon);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            border-left: 3px solid var(--maroon);
            padding-left: 15px;
        }

        .premium-label { display: block; font-size: 13px; font-weight: 700; color: #4b5563; margin-bottom: 10px; }
        .premium-input {
            width: 100%;
            border-radius: 14px;
            border: 1.5px solid #e2e8f0;
            padding: 14px 18px;
            font-size: 15px;
            transition: all 0.2s;
            outline: none !important;
        }

        .premium-input:focus {
            border-color: var(--maroon);
            box-shadow: 0 0 0 4px rgba(128, 0, 0, 0.04);
        }

        .btn-register-action {
            width: 100%;
            background: #1f2937;
            color: white;
            padding: 20px;
            border-radius: 18px;
            font-weight: 900;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
        }

        .btn-register-action:hover {
            background: #000;
            transform: translateY(-4px);
            box-shadow: 0 18px 35px rgba(0, 0, 0, 0.2);
        }

        .fade-up { animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-admin-layout>

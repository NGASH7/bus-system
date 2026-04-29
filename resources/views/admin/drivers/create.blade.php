<x-admin-layout>
    <div class="driver-create-wrapper fade-up">
        <div class="max-w-4xl mx-auto">
            
            <div class="welcome-header-minimal mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="page-title">Register Fleet Driver</h1>
                        <p class="text-sm text-gray-500 mt-1">Onboard a new operator and provision their system credentials.</p>
                    </div>
                    <a href="{{ route('admin.drivers.index') }}" class="btn-back">
                        <i class="fas fa-arrow-left mr-2"></i> Fleet Overview
                    </a>
                </div>
            </div>

            <div class="registration-card">
                <div class="security-banner mb-10">
                    <div class="flex items-start">
                        <div class="banner-icon-box">
                            <i class="fas fa-shield-halved"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="banner-title">Automated Provisioning</h4>
                            <p class="banner-text">
                                System will automatically create a driver account. Default password is <strong class="text-white">driver123</strong>. 
                                The user will be required to update this upon their first login for security.
                            </p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.drivers.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-section-title">
                        <i class="fas fa-id-badge mr-2"></i> Personal Details
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                        <div>
                            <label for="name" class="premium-label">Driver Full Name</label>
                            <input type="text" name="name" id="name" class="premium-input @error('name') border-red-500 @enderror" value="{{ old('name') }}" placeholder="e.g. Michael Kanyingi" required>
                            @error('name') <p class="text-xs text-red-500 mt-1 font-bold italic">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="phone_number" class="premium-label">Phone Number</label>
                            <input type="text" name="phone_number" id="phone_number" class="premium-input @error('phone_number') border-red-500 @enderror" value="{{ old('phone_number') }}" placeholder="e.g. 0712345678">
                            @error('phone_number') <p class="text-xs text-red-500 mt-1 font-bold italic">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="form-section-title">
                        <i class="fas fa-shield-check mr-2"></i> Regulatory Compliance
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                        <div>
                            <label for="national_id" class="premium-label">National ID Number</label>
                            <input type="text" name="national_id" id="national_id" class="premium-input @error('national_id') border-red-500 @enderror" value="{{ old('national_id') }}" placeholder="ID Number">
                            @error('national_id') <p class="text-xs text-red-500 mt-1 font-bold italic">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="license_number" class="premium-label">Driving License No.</label>
                            <input type="text" name="license_number" id="license_number" class="premium-input @error('license_number') border-red-500 @enderror" value="{{ old('license_number') }}" placeholder="License Number">
                            @error('license_number') <p class="text-xs text-red-500 mt-1 font-bold italic">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="license_expiry" class="premium-label">License Expiry Date</label>
                            <input type="date" name="license_expiry" id="license_expiry" class="premium-input @error('license_expiry') border-red-500 @enderror" value="{{ old('license_expiry') }}">
                            @error('license_expiry') <p class="text-xs text-red-500 mt-1 font-bold italic">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="form-section-title">
                        <i class="fas fa-envelope-open-text mr-2"></i> System Access (Login)
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-1 gap-6 mb-10">
                        <div>
                            <label for="email" class="premium-label">Professional Email Address</label>
                            <input type="email" name="email" id="email" class="premium-input @error('email') border-red-500 @enderror" value="{{ old('email') }}" placeholder="e.g. driver.name@mwigito.co.ke" required>
                            <p class="text-[10px] text-gray-400 mt-2 font-black uppercase tracking-widest">This will be used for system login and high-priority notifications.</p>
                            @error('email') <p class="text-xs text-red-500 mt-1 font-bold italic">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="form-actions mt-12">
                        <button type="submit" class="btn-register-action">
                            <i class="fas fa-user-check mr-2"></i> Provision Account & Save Driver
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

        /* FORM CARD */
        .registration-card {
            background: white;
            border-radius: 28px;
            padding: 45px;
            box-shadow: 0 15px 45px rgba(0,0,0,0.03);
            border: 1px solid #f1f5f9;
        }

        .security-banner {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            padding: 24px;
            border-radius: 18px;
            color: rgba(255,255,255,0.9);
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.15);
        }

        .banner-icon-box {
            width: 48px;
            height: 48px;
            background: rgba(255,255,255,0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: var(--gold);
            flex-shrink: 0;
        }

        .banner-title { font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 900; color: white; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
        .banner-text { font-size: 13px; line-height: 1.5; opacity: 0.7; }

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
            box-shadow: 0 2px 4px rgba(0,0,0,0.01);
        }

        .premium-input:focus {
            border-color: var(--maroon);
            box-shadow: 0 0 0 4px rgba(128, 0, 0, 0.04);
            background: #fff;
        }

        .btn-register-action {
            width: 100%;
            background: var(--maroon);
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
            box-shadow: 0 12px 25px rgba(128, 0, 0, 0.2);
        }

        .btn-register-action:hover {
            background: var(--maroon-dark);
            transform: translateY(-4px);
            box-shadow: 0 18px 35px rgba(128, 0, 0, 0.3);
        }

        .fade-up { animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-admin-layout>

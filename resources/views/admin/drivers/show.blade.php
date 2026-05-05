<x-admin-layout>
    <div class="driver-details-wrapper fade-up">
        <div class="max-w-[1200px] mx-auto">
            
            <div class="header-section mb-10">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('admin.drivers.index') }}" class="btn-back-square">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div>
                            <h1 class="page-title">{{ $driver->name }}</h1>
                            <p class="text-sm text-gray-500 font-medium">Fleet Operator Profile & Compliance Data</p>
                        </div>
                    </div>
                    <div class="header-actions">
                        <a href="{{ route('admin.drivers.edit', $driver->id) }}" class="btn-edit-premium">
                            <i class="fas fa-user-edit mr-2"></i> Edit Profile
                        </a>
                    </div>
                </div>
            </div>

            <div class="details-grid">
                <!-- PRIMARY CARD -->
                <div class="primary-profile-card">
                    <div class="profile-header-strip">
                        <div class="avatar-giant">
                            {{ substr($driver->name, 0, 2) }}
                        </div>
                        <h2 class="op-full-name mt-4">{{ $driver->name }}</h2>
                        <span class="role-badge">CERTIFIED DRIVER</span>
                        
                        <div class="contact-links-grid mt-8">
                            <div class="contact-card">
                                <i class="fas fa-envelope"></i>
                                <span>{{ $driver->email }}</span>
                            </div>
                            <div class="contact-card">
                                <i class="fas fa-phone"></i>
                                <span>{{ $driver->phone_number ?? 'Not provided' }}</span>
                            </div>
                        </div>
                    </div>
                    
                    @if($driver->bus)
                    <div class="assignment-box mt-10">
                        <h4 class="box-label">Assigned Vehicle</h4>
                        <div class="assigned-bus-link">
                            <i class="fas fa-bus-simple mr-3"></i>
                            <div>
                                <div class="bus-plate">{{ $driver->bus->plate_number }}</div>
                                <div class="bus-model-small">{{ $driver->bus->model }}</div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="unassigned-notice mt-10">
                        <i class="fas fa-triangle-exclamation mr-2"></i> No vehicle currently assigned
                    </div>
                    @endif
                </div>

                <!-- COMPLIANCE DATA -->
                <div class="compliance-data-section">
                    <h3 class="section-heading-row">
                        <i class="fas fa-shield-check mr-2"></i> Regulatory & Personal Data
                    </h3>

                    <div class="data-grid-container">
                        <div class="data-card-item">
                            <label>National ID Number</label>
                            <div class="value-text">{{ $driver->national_id ?? 'Pending document upload' }}</div>
                        </div>
                        
                        <div class="data-card-item">
                            <label>Driving License No.</label>
                            <div class="value-text">{{ $driver->license_number ?? 'Pending document upload' }}</div>
                        </div>

                        <div class="data-card-item">
                            <label>License Expiry Date</label>
                            <div class="value-text {{ $driver->license_expiry && \Carbon\Carbon::parse($driver->license_expiry)->isPast() ? 'expired' : 'valid' }}">
                                <i class="fas fa-calendar-day mr-2"></i>
                                {{ $driver->license_expiry ? \Carbon\Carbon::parse($driver->license_expiry)->format('d M, Y') : 'Not Provided' }}
                                @if($driver->license_expiry && \Carbon\Carbon::parse($driver->license_expiry)->isPast())
                                    <span class="expiry-tag">EXPIRED</span>
                                @endif
                            </div>
                        </div>

                        <div class="data-card-item">
                            <label>Account Status</label>
                            <div class="value-text">
                                @if($driver->must_change_password)
                                    <span class="status-pill warning">Pending First Login Reset</span>
                                @else
                                    <span class="status-pill success">Verified & Active</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="data-card-item">
                            <label>Registration Date</label>
                            <div class="value-text">{{ $driver->created_at->format('d F Y, H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --maroon: #800000;
            --maroon-dark: #600000;
            --text-main: #111827;
            --text-muted: #6b7280;
        }

        .driver-details-wrapper { padding: 40px 20px; }

        .btn-back-square {
            width: 44px; height: 44px; background: white; border: 1px solid #e5e7eb;
            border-radius: 12px; display: flex; align-items: center; justify-content: center;
            color: var(--text-muted); transition: all 0.2s;
        }
        .btn-back-square:hover { border-color: var(--maroon); color: var(--maroon); background: #fffafa; }

        .page-title { font-family: 'Outfit', sans-serif; font-size: 32px; font-weight: 900; color: var(--text-main); letter-spacing: -1px; }

        .btn-edit-premium {
            background: #111827; color: white; padding: 12px 24px; border-radius: 14px;
            font-weight: 800; font-size: 13px; text-transform: uppercase; text-decoration: none;
            transition: all 0.3s; box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .btn-edit-premium:hover { background: #000; transform: translateY(-2px); box-shadow: 0 15px 30px rgba(0,0,0,0.15); }

        .details-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 30px; }

        /* PRIMARY CARD */
        .primary-profile-card {
            background: white; border-radius: 28px; padding: 40px; border: 1px solid #f1f5f9;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02); text-align: center;
        }
        .avatar-giant {
            width: 120px; height: 120px; background: rgba(128, 0, 0, 0.08); color: var(--maroon);
            border-radius: 35px; display: flex; align-items: center; justify-content: center;
            margin: 0 auto; font-size: 42px; font-weight: 950; text-transform: uppercase;
            border: 4px solid white; box-shadow: 0 10px 30px rgba(128,0,0,0.1);
        }
        .op-full-name { font-family: 'Outfit', sans-serif; font-size: 24px; font-weight: 900; color: var(--text-main); }
        .role-badge { 
            display: inline-block; padding: 4px 14px; background: #fef2f2; color: #dc2626;
            border-radius: 20px; font-size: 10px; font-weight: 900; letter-spacing: 1px; margin-top: 8px;
        }

        .contact-card {
            background: #f8fafc; padding: 12px; border-radius: 14px; display: flex;
            align-items: center; gap: 12px; margin-bottom: 10px; text-align: left;
            font-size: 13px; font-weight: 700; color: #4b5563; border: 1px solid #f1f5f9;
        }
        .contact-card i { color: var(--maroon); font-size: 16px; }

        /* ASSIGNMENT BOX */
        .box-label { text-align: left; font-size: 11px; font-weight: 900; text-transform: uppercase; color: var(--text-muted); letter-spacing: 1px; margin-bottom: 12px; }
        .assigned-bus-link {
            background: linear-gradient(135deg, #800000 0%, #600000 100%);
            padding: 20px; border-radius: 20px; display: flex; align-items: center;
            color: white; text-align: left; box-shadow: 0 10px 25px rgba(128,0,0,0.2);
        }
        .bus-plate { font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 900; }
        .bus-model-small { font-size: 12px; opacity: 0.8; }
        .unassigned-notice { padding: 20px; background: #fffbeb; color: #b45309; border-radius: 20px; font-weight: 800; font-size: 13px; }

        /* COMPLIANCE DATA */
        .compliance-data-section { background: white; border-radius: 28px; padding: 40px; border: 1px solid #f1f5f9; box-shadow: 0 4px 20px rgba(0,0,0,0.02); }
        .section-heading-row { font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 900; color: var(--text-main); margin-bottom: 30px; }
        
        .data-grid-container { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
        .data-card-item { padding-bottom: 20px; border-bottom: 1px solid #f8fafc; }
        .data-card-item label { display: block; font-size: 10px; font-weight: 950; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 8px; }
        .value-text { font-size: 16px; font-weight: 700; color: #374151; display: flex; align-items: center; }
        
        .value-text.expired { color: #dc2626; }
        .expiry-tag { margin-left: 10px; padding: 2px 8px; background: #dc2626; color: white; font-size: 10px; font-weight: 900; border-radius: 6px; }

        .status-pill { padding: 4px 12px; border-radius: 8px; font-size: 12px; font-weight: 850; }
        .status-pill.success { background: #f0fdf4; color: #16a34a; }
        .status-pill.warning { background: #fff7ed; color: #c2410c; }

        @media (max-width: 900px) {
            .details-grid { grid-template-columns: 1fr; }
            .data-grid-container { grid-template-columns: 1fr; }
        }

        .fade-up { animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-admin-layout>

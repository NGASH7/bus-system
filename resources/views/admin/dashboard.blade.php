<x-admin-layout>
    <div class="fade-up">
        <h1 style="font-family:'Outfit',sans-serif; font-size: 28px; font-weight: 800; color: #1f2937; margin-bottom: 24px;">Dashboard</h1>

        <!-- STAT CARDS -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-bus"></i></div>
                <div class="stat-value">11</div>
                <div class="stat-label">Buses: Active Fleet</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-id-card"></i></div>
                <div class="stat-value">9</div>
                <div class="stat-label">Drivers: On Duty</div>
            </div>
            <div class="stat-card urgent">
                <div class="stat-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                <div class="stat-value">6</div>
                <div class="stat-label">Billing: Pending Items</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-receipt"></i></div>
                <div class="stat-value">18</div>
                <div class="stat-label">Receipts: New Records</div>
            </div>
        </div>

        <!-- WELCOME BOX -->
        <div class="welcome-section shadow-sm">
            <div class="welcome-header">
                <i class="fas fa-shield-halved"></i>
                Welcome to Admin Panel
            </div>
            <div class="welcome-body">
                <p>Hello <strong>{{ Auth::user()->name }}</strong>, you are currently managing the Mwigito Excel Bus System.</p>
                <div class="alert-info">
                   <i class="fas fa-info-circle"></i> Use the sidebar navigation on the left to manage fleet, staff, and system contents.
                </div>
                
                <div class="quick-links mt-6 pt-6 border-t border-gray-100">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-3">System Shortcuts</span>
                    <div class="flex gap-3">
                         <a href="#" class="btn-sm"><i class="fas fa-bus"></i> Fleet Status</a>
                         <a href="#" class="btn-sm"><i class="fas fa-id-card"></i> Driver Records</a>
                         <a href="#" class="btn-sm"><i class="fas fa-receipt"></i> Receipts</a>
                         <a href="#" class="btn-sm"><i class="fas fa-calendar-alt"></i> Trip History</a>
                    </div>
                </div>

                <div class="quick-links mt-4">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-3">Sidebar Icon Guide</span>
                    <div class="icon-guide-grid">
                        <div class="icon-guide-item"><i class="fas fa-th-large"></i> Dashboard</div>
                        <div class="icon-guide-item"><i class="fas fa-bus"></i> Buses</div>
                        <div class="icon-guide-item"><i class="fas fa-id-card"></i> Drivers</div>
                        <div class="icon-guide-item"><i class="fas fa-file-invoice-dollar"></i> Billing</div>
                        <div class="icon-guide-item"><i class="fas fa-receipt"></i> Receipts</div>
                        <div class="icon-guide-item"><i class="fas fa-cog"></i> Settings</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            padding: 20px 14px;
            border-radius: 12px;
            text-align: center;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-color: var(--maroon);
        }

        .stat-icon {
            font-size: 14px;
            color: var(--maroon);
            margin-bottom: 10px;
            height: 30px;
            width: 30px;
            background: rgba(128, 0, 0, 0.05);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: auto;
            margin-right: auto;
        }

        .stat-value {
            font-family: 'Outfit', sans-serif;
            font-size: 30px;
            font-weight: 800;
            color: var(--maroon);
            line-height: 1;
            margin-bottom: 6px;
        }

        .stat-label {
            font-size: 10px;
            color: #6b7280;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.2px;
        }

        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        .stat-card.urgent .stat-icon {
            background: #fff5f5;
            color: #e53e3e;
        }
        .stat-card.urgent .stat-value {
            color: #e53e3e;
        }

        /* WELCOME SECTION */
        .welcome-section {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }

        .welcome-header {
            background: #fafafa;
            padding: 20px 32px;
            border-bottom: 1px solid #f0f0f0;
            font-weight: 700;
            color: var(--maroon);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .welcome-body {
            padding: 32px;
        }

        .welcome-body p {
            font-size: 16px;
            color: #374151;
            margin-bottom: 16px;
        }

        .alert-info {
            background: #eff6ff;
            color: #1e40af;
            padding: 14px 20px;
            border-radius: 10px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-sm {
            padding: 8px 16px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            color: #4b5563;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }

        .btn-sm:hover {
            border-color: var(--maroon);
            color: var(--maroon);
            background: #fffafa;
        }

        .icon-guide-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 10px;
        }

        .icon-guide-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 8px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 12px;
            color: #4b5563;
            background: #fafafa;
        }

        .icon-guide-item i {
            color: var(--maroon);
            width: 12px;
            font-size: 12px;
            text-align: center;
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

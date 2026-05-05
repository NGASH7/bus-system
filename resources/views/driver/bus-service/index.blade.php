<x-driver-layout>
    <div class="service-page-wrapper fade-up">
        <div class="service-container">
            <!-- PAGE HEADER -->
            <div class="page-header-premium">
                <div class="header-main">
                    <h1 class="premium-title">Bus Service & Maintenance</h1>
                    <div class="header-line"></div>
                </div>
                <p class="premium-subtitle">Report required maintenance or services for your assigned vehicle.</p>
            </div>

            <div class="service-grid">
                <!-- FORM CARD -->
                <div class="form-section">
                    <div class="premium-card form-card">
                        <h3 class="card-header-title">
                            <i class="fas fa-plus-circle"></i> New Service Request
                        </h3>

                        @if(!$bus)
                            <div class="warning-box">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>No bus assigned to your account. You cannot submit requests.</span>
                            </div>
                        @else
                            <form action="{{ route('driver.bus-service.store') }}" method="POST" class="modern-form">
                                @csrf
                                <div class="form-group">
                                    <label>Assigned Vehicle</label>
                                    <input type="text" value="{{ $bus->plate_number }} ({{ $bus->model }})" class="readonly-input" readonly>
                                </div>

                                <div class="form-group">
                                    <label>Service Description</label>
                                    <textarea name="description" rows="4" placeholder="Explain what needs to be fixed or serviced..." required></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Estimated Cost (KES)</label>
                                    <div class="input-icon-wrapper">
                                        <span class="currency-tag">KES</span>
                                        <input type="number" name="cost" step="0.01" placeholder="0.00" required>
                                    </div>
                                </div>

                                <button type="submit" class="btn-premium-submit">
                                    <span>Submit Request</span>
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- LIST CARD -->
                <div class="list-section">
                    <div class="premium-card table-card">
                        <div class="table-header-box">
                            <h3 class="table-title">My Recent Requests</h3>
                            <div class="badge-count">{{ count($services) }} Entries</div>
                        </div>

                        <div class="table-responsive">
                            <table class="premium-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Cost</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($services as $service)
                                        <tr>
                                            <td class="font-bold">{{ $service->created_at->format('M d, Y') }}</td>
                                            <td><div class="desc-text">{{ $service->description }}</div></td>
                                            <td class="cost-value">KES {{ number_format($service->cost, 2) }}</td>
                                            <td>
                                                <span class="status-badge {{ $service->status }}">
                                                    {{ ucfirst($service->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="empty-state">
                                                <i class="fas fa-inbox"></i>
                                                <p>No service requests found.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
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
            --gold: #c9a84c;
            --text-dark: #1a202c;
            --text-muted: #718096;
            --radius-lg: 24px;
            --radius-md: 16px;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .service-page-wrapper {
            padding: 10px 20px 40px;
            font-family: 'Inter', sans-serif;
            background: #fdfdfd;
        }

        .service-container {
            max-width: 1280px;
            margin: 0 auto;
        }

        /* HEADER */
        .page-header-premium {
            margin-bottom: 40px;
        }

        .header-main {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 10px;
        }

        .premium-title {
            font-family: 'Outfit', sans-serif;
            font-size: 32px;
            font-weight: 900;
            color: var(--text-dark);
            text-transform: uppercase;
            letter-spacing: -0.5px;
            margin: 0;
        }

        .header-line {
            flex: 1;
            height: 2px;
            background: linear-gradient(to right, var(--maroon), transparent);
            opacity: 0.1;
        }

        .premium-subtitle {
            color: var(--text-muted);
            font-size: 15px;
        }

        /* GRID */
        .service-grid {
            display: grid;
            grid-template-columns: 380px 1fr;
            gap: 30px;
            align-items: start;
        }

        @media (max-width: 1000px) {
            .service-grid { grid-template-columns: 1fr; }
        }

        /* CARDS */
        .premium-card {
            background: #ffffff;
            border-radius: var(--radius-lg);
            border: 1px solid #f1f5f9;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .form-card { padding: 30px; }

        .card-header-title {
            font-family: 'Outfit', sans-serif;
            font-size: 18px;
            font-weight: 800;
            color: var(--maroon);
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* FORM */
        .form-group { margin-bottom: 20px; }

        .form-group label {
            display: block;
            font-size: 11px;
            font-weight: 800;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .modern-form input, .modern-form textarea, .readonly-input {
            width: 100%;
            background: #f9fafb;
            border: 1px solid #edf2f7;
            border-radius: var(--radius-md);
            padding: 14px 18px;
            font-size: 14px;
            font-weight: 600;
            outline: none;
            transition: all 0.2s;
        }

        .modern-form input:focus, .modern-form textarea:focus {
            border-color: var(--maroon);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(128, 0, 0, 0.05);
        }

        .readonly-input {
            background: #f1f5f9;
            color: var(--text-muted);
            cursor: not-allowed;
        }

        .input-icon-wrapper { position: relative; }

        .currency-tag {
            position: absolute;
            left: 18px; top: 50%;
            transform: translateY(-50%);
            font-size: 12px;
            font-weight: 900;
            color: var(--text-muted);
        }

        .input-icon-wrapper input { padding-left: 55px; }

        .btn-premium-submit {
            width: 100%;
            background: var(--maroon);
            color: #fff;
            border: none;
            border-radius: var(--radius-md);
            padding: 16px;
            font-size: 14px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 8px 20px rgba(128, 0, 0, 0.2);
        }

        .btn-premium-submit:hover {
            background: var(--maroon-dark);
            transform: translateY(-2px);
        }

        .warning-box {
            background: #fff5f5;
            border: 1px solid #feb2b2;
            color: #c53030;
            padding: 20px;
            border-radius: var(--radius-md);
            font-size: 14px;
            font-weight: 700;
            display: flex;
            gap: 12px;
            align-items: center;
        }

        /* TABLE */
        .table-header-box {
            padding: 25px 30px;
            background: #fafafa;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-title {
            font-family: 'Outfit', sans-serif;
            font-size: 17px;
            font-weight: 800;
            color: var(--text-dark);
            margin: 0;
        }

        .badge-count {
            padding: 6px 12px;
            background: rgba(128, 0, 0, 0.08);
            color: var(--maroon);
            border-radius: 99px;
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
        }

        .premium-table { width: 100%; border-collapse: collapse; }

        .premium-table th {
            text-align: left;
            padding: 15px 30px;
            font-size: 10px;
            font-weight: 800;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid #f1f5f9;
        }

        .premium-table td {
            padding: 18px 30px;
            border-bottom: 1px solid #f8fafc;
            font-size: 14px;
            color: #4a5568;
        }

        .font-bold { font-weight: 800; color: var(--text-dark); }

        .desc-text {
            max-width: 250px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .cost-value { font-weight: 900; color: var(--maroon); }

        .status-badge {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
        }

        .status-badge.pending { background: #fffbeb; color: #92400e; }
        .status-badge.approved { background: #eff6ff; color: #1e40af; }
        .status-badge.paid { background: #f0fdf4; color: #166534; }

        .empty-state {
            padding: 60px !important;
            text-align: center;
            opacity: 0.3;
        }

        .empty-state i { font-size: 40px; margin-bottom: 10px; display: block; }

        .fade-up {
            animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-driver-layout>

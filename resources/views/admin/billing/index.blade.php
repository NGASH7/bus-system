<x-admin-layout>
    <div class="billing-page-wrapper fade-up">
        <div class="billing-container">
            <!-- PAGE HEADER -->
            <div class="page-header-premium">
                <div class="header-main">
                    <h1 class="premium-title">Expenses & Bus Services</h1>
                    <div class="header-line"></div>
                </div>
                <p class="premium-subtitle">Monitor operational costs, approve driver service requests, and track all fleet-related expenses.</p>
            </div>

            <div class="billing-grid">
                <!-- LEFT COLUMN: FORM & SUMMARY -->
                <div class="form-column">
                    <div class="premium-card form-card">
                        <div class="card-glow"></div>
                        <h3 class="card-header-title">
                            <i class="fas fa-plus-circle"></i> Add Manual Bill
                        </h3>
                        
                        <form action="{{ route('admin.billing.store') }}" method="POST" class="modern-form">
                            @csrf
                            <div class="form-group">
                                <label>Target Vehicle</label>
                                <div class="select-wrapper">
                                    <select name="bus_id" required>
                                        <option value="" disabled selected>Select Bus Plate</option>
                                        @foreach($buses as $bus)
                                            <option value="{{ $bus->id }}">{{ $bus->plate_number }} ({{ $bus->model }})</option>
                                        @endforeach
                                    </select>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Service Description</label>
                                <textarea name="description" rows="3" placeholder="e.g. Engine Oil Change, Tire Replacement..." required></textarea>
                            </div>

                            <div class="form-group">
                                <label>Total Cost (KES)</label>
                                <div class="input-icon-wrapper">
                                    <span class="currency-tag">KES</span>
                                    <input type="number" name="cost" step="0.01" placeholder="0.00" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Entry Status</label>
                                <div class="select-wrapper">
                                    <select name="status" required>
                                        <option value="approved">Approved</option>
                                        <option value="paid">Paid</option>
                                        <option value="pending">Pending</option>
                                    </select>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>

                            <button type="submit" class="btn-premium-submit">
                                <span>Save Billing Entry</span>
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        </form>
                    </div>

                    <!-- EXPENSE SUMMARY CARD -->
                    <div class="premium-card summary-card mt-6">
                        <h3 class="card-header-title text-maroon">
                            <i class="fas fa-chart-pie"></i> Expense Summary
                        </h3>
                        <div class="summary-list">
                            <div class="summary-item">
                                <span class="label">Pending Requests</span>
                                <span class="value text-amber-600">KES {{ number_format($summary['pending'], 2) }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="label">Approved (To Pay)</span>
                                <span class="value text-blue-600">KES {{ number_format($summary['approved'], 2) }}</span>
                            </div>
                            <div class="summary-item highlight">
                                <span class="label">Total Paid Out</span>
                                <span class="value text-green-600">KES {{ number_format($summary['paid'], 2) }}</span>
                            </div>
                            <div class="total-divider"></div>
                            <div class="summary-item grand-total">
                                <span class="label">Gross Expenses</span>
                                <span class="value">KES {{ number_format($summary['total'], 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: TABLES -->
                <div class="tables-column">
                    <!-- BUS SERVICES TABLE -->
                    <div class="premium-card table-card">
                        <div class="table-header-box">
                            <div class="title-wrap">
                                <h3 class="table-title">Bus Services & Maintenance Log</h3>
                                <p class="table-subtitle">Comprehensive list of all fleet maintenance activities</p>
                            </div>
                            <div class="badge-internal">Operational Cost</div>
                        </div>

                        <div class="table-responsive">
                            <table class="premium-table">
                                <thead>
                                    <tr>
                                        <th>Vehicle</th>
                                        <th>Origin</th>
                                        <th>Service Details</th>
                                        <th>Cost</th>
                                        <th>Status</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($services as $service)
                                        <tr>
                                            <td>
                                                <div class="bus-info">
                                                    <span class="plate">{{ $service->bus->plate_number }}</span>
                                                    <span class="model">{{ $service->bus->model }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="reported-info">
                                                    <span class="reporter">{{ $service->driver ? $service->driver->name : 'System Admin' }}</span>
                                                    <span class="role-tag">{{ $service->added_by }}</span>
                                                </div>
                                            </td>
                                            <td><div class="desc-text">{{ $service->description }}</div></td>
                                            <td><div class="cost-value">KES {{ number_format($service->cost, 2) }}</div></td>
                                            <td>
                                                <span class="status-badge {{ $service->status }}">
                                                    {{ ucfirst($service->status) }}
                                                </span>
                                            </td>
                                            <td class="text-right">
                                                <form action="{{ route('admin.bus-service.update-status', $service->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="status" onchange="this.form.submit()" class="mini-status-select">
                                                        <option value="pending" {{ $service->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="approved" {{ $service->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                                        <option value="paid" {{ $service->status == 'paid' ? 'selected' : '' }}>Paid</option>
                                                    </select>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="empty-state">No internal bus services recorded yet.</td>
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
            --maroon-light: #a52a2a;
            --gold: #c9a84c;
            --gold-light: #e2c275;
            --text-dark: #1a202c;
            --text-muted: #718096;
            --bg-light: #f8fafc;
            --white: #ffffff;
            --radius-lg: 24px;
            --radius-md: 16px;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .billing-page-wrapper {
            padding: 10px 20px 40px;
            font-family: 'Inter', sans-serif;
            background: #fdfdfd;
        }

        .billing-container {
            max-width: 1440px;
            margin: 0 auto;
        }

        /* HEADER */
        .page-header-premium {
            margin-bottom: 40px;
            text-align: left;
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
            font-weight: 500;
        }

        /* GRID */
        .billing-grid {
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 30px;
            align-items: start;
        }

        @media (max-width: 1100px) {
            .billing-grid { grid-template-columns: 1fr; }
        }

        /* CARDS */
        .premium-card {
            background: var(--white);
            border-radius: var(--radius-lg);
            border: 1px solid #f1f5f9;
            box-shadow: var(--shadow);
            overflow: hidden;
            position: relative;
        }

        .card-glow {
            position: absolute;
            top: 0; right: 0;
            width: 100px; height: 100px;
            background: radial-gradient(circle, rgba(128, 0, 0, 0.03) 0%, transparent 70%);
            pointer-events: none;
        }

        /* FORM */
        .form-card {
            padding: 30px;
        }

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

        .modern-form .form-group {
            margin-bottom: 20px;
        }

        .modern-form label {
            display: block;
            font-size: 11px;
            font-weight: 800;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .modern-form select, 
        .modern-form textarea, 
        .modern-form input {
            width: 100%;
            background: #f9fafb;
            border: 1px solid #edf2f7;
            border-radius: var(--radius-md);
            padding: 14px 18px;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-dark);
            transition: all 0.2s;
            outline: none;
        }

        .modern-form select:focus, 
        .modern-form textarea:focus, 
        .modern-form input:focus {
            border-color: var(--maroon);
            background: white;
            box-shadow: 0 0 0 4px rgba(128, 0, 0, 0.05);
        }

        .select-wrapper {
            position: relative;
        }

        .select-wrapper i {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 12px;
            color: var(--text-muted);
            pointer-events: none;
        }

        .input-icon-wrapper {
            position: relative;
        }

        .currency-tag {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 12px;
            font-weight: 900;
            color: var(--text-muted);
        }

        .input-icon-wrapper input {
            padding-left: 55px;
        }

        .btn-premium-submit {
            width: 100%;
            background: var(--maroon);
            color: white;
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
            margin-top: 10px;
        }

        .btn-premium-submit:hover {
            background: var(--maroon-dark);
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(128, 0, 0, 0.3);
        }

        /* SUMMARY */
        .summary-card { padding: 30px; }
        .summary-list { display: flex; flex-direction: column; gap: 15px; }
        .summary-item { display: flex; justify-content: space-between; align-items: center; }
        .summary-item .label { font-size: 13px; font-weight: 600; color: var(--text-muted); }
        .summary-item .value { font-weight: 800; font-family: 'Outfit', sans-serif; }
        .summary-item.highlight { background: #f0fdf4; padding: 10px; border-radius: 10px; margin: 0 -10px; }
        .total-divider { height: 1px; background: #edf2f7; margin: 5px 0; }
        .summary-item.grand-total .label { color: var(--text-dark); font-size: 14px; font-weight: 800; }
        .summary-item.grand-total .value { color: var(--maroon); font-size: 18px; font-weight: 900; }

        /* TABLES */
        .table-header-box {
            padding: 25px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fcfcfc;
            border-bottom: 1px solid #f1f5f9;
        }

        .table-title {
            font-family: 'Outfit', sans-serif;
            font-size: 17px;
            font-weight: 800;
            color: var(--text-dark);
            margin: 0;
        }

        .table-subtitle {
            font-size: 12px;
            color: var(--text-muted);
            margin: 4px 0 0;
        }

        .badge-internal {
            padding: 6px 12px;
            border-radius: 99px;
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: rgba(128, 0, 0, 0.08); color: var(--maroon);
        }

        .premium-table {
            width: 100%;
            border-collapse: collapse;
        }

        .premium-table th {
            text-align: left;
            padding: 15px 30px;
            background: #fafafa;
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

        .bus-info .plate {
            display: block;
            font-weight: 800;
            color: var(--text-dark);
        }

        .bus-info .model {
            font-size: 10px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
        }

        .reported-info .reporter {
            display: block;
            font-weight: 700;
        }

        .role-tag {
            font-size: 9px;
            font-weight: 800;
            color: var(--maroon);
            text-transform: uppercase;
            opacity: 0.7;
        }

        .desc-text {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .cost-value {
            font-weight: 900;
            color: var(--maroon);
        }

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

        .mini-status-select {
            background: #f1f5f9;
            border: none;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 800;
            color: var(--text-muted);
            cursor: pointer;
        }

        .empty-state {
            padding: 40px !important;
            text-align: center;
            color: var(--text-muted);
            font-weight: 600;
            opacity: 0.6;
        }

        .text-right { text-align: right; }
        .mt-6 { margin-top: 24px; }
        .font-bold { font-weight: 700; }
        .text-muted { color: var(--text-muted); font-size: 12px; }
        .text-amber-600 { color: #d97706; }
        .text-blue-600 { color: #2563eb; }
        .text-green-600 { color: #16a34a; }

        .fade-up {
            animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-admin-layout>

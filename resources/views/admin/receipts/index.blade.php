<x-admin-layout>
    <div class="receipt-index-wrapper fade-up">
        <div class="max-w-[1440px] mx-auto">
            
            <div class="welcome-header-minimal mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="page-title">Receipt Management</h1>
                        <p class="text-sm text-gray-500 mt-1">Generate new invoices and track payment history across the fleet.</p>
                    </div>
                </div>
            </div>

            <!-- RECEIPT HUB GRID -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
                <!-- Records Stats Column -->
                <div class="lg:col-span-1">
                    <div class="generate-cta-card">
                        <div class="cta-icon-box">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <h3 class="cta-title">New Transaction?</h3>
                        <p class="cta-desc">Quickly generate a professional receipt for a passenger or service.</p>
                        <a href="{{ route('admin.receipts.create') }}" class="btn-generate-main">
                            <i class="fas fa-file-invoice mr-2"></i> Generate Receipt
                        </a>
                    </div>

                    <div class="receipt-summary-card mt-6">
                        <h4 class="info-title">Global Stats</h4>
                        <div class="info-stat-row">
                            <span class="info-label">Total Volume</span>
                            <span class="info-value">KES {{ number_format($receipts->sum('amount'), 2) }}</span>
                        </div>
                        <div class="info-stat-row">
                            <span class="info-label">Record Count</span>
                            <span class="info-value">{{ $receipts->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Past Records Table Column -->
                <div class="lg:col-span-2">
                    <div class="records-white-card">
                        <div class="card-header-premium">
                            <h3 class="card-title-text"><i class="fas fa-history mr-2"></i> Past Records</h3>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="premium-table">
                                <thead>
                                    <tr>
                                        <th>Receipt No</th>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>Vehicle</th>
                                        <th class="text-right">Amount</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($receipts as $receipt)
                                    <tr>
                                        <td><span class="receipt-no-badge">{{ $receipt->receipt_no }}</span></td>
                                        <td><span class="text-xs font-semibold text-gray-500">{{ $receipt->receipt_date->format('d M, Y') }}</span></td>
                                        <td>
                                            <div class="cust-name">{{ $receipt->customer_name }}</div>
                                            <div class="text-[10px] text-gray-400 font-bold uppercase">{{ $receipt->payment_method }}</div>
                                        </td>
                                        <td><span class="bus-ref">{{ $receipt->bus_number }}</span></td>
                                        <td class="text-right font-black text-maroon">KES {{ number_format($receipt->amount, 2) }}</td>
                                        <td class="text-center">
                                            <div class="actions-flex">
                                                <a href="{{ route('admin.receipts.show', $receipt->id) }}" class="btn-action-receipt view" title="View & Print">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.receipts.edit', $receipt->id) }}" class="btn-action-receipt edit" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.receipts.destroy', $receipt->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this receipt? This action cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-action-receipt delete" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="empty-table-state">
                                            <div class="empty-icon-wrap"><i class="fas fa-file-invoice-dollar"></i></div>
                                            <p>No receipt records found in the system yet.</p>
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
            --text-main: #1a202c;
            --text-muted: #718096;
        }

        .receipt-index-wrapper { padding: 0 10px; }

        /* CTA CARD */
        .generate-cta-card {
            background: linear-gradient(135deg, var(--maroon) 0%, var(--maroon-dark) 100%);
            border-radius: 24px;
            padding: 40px 30px;
            color: white;
            text-align: center;
            box-shadow: 0 15px 35px rgba(128, 0, 0, 0.2);
        }

        .cta-icon-box {
            width: 60px;
            height: 60px;
            background: rgba(255,255,255,0.1);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin: 0 auto 20px;
            color: var(--gold);
        }

        .cta-title { font-family: 'Outfit', sans-serif; font-size: 22px; font-weight: 900; margin-bottom: 10px; }
        .cta-desc { font-size: 14px; opacity: 0.8; margin-bottom: 25px; line-height: 1.5; }

        .btn-generate-main {
            display: inline-flex;
            align-items: center;
            background: white;
            color: var(--maroon);
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 850;
            text-decoration: none;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }

        .btn-generate-main:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255,255,255,0.3);
        }

        /* STATS CARD */
        .receipt-summary-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            border: 1px solid #f1f5f9;
        }

        .info-title { font-size: 11px; font-weight: 900; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px; border-bottom: 1px solid #f8fafc; padding-bottom: 10px; }
        .info-stat-row { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .info-label { font-size: 13px; font-weight: 600; color: var(--text-muted); }
        .info-value { font-size: 14px; font-weight: 900; color: var(--text-main); }

        /* TABLE CARD */
        .records-white-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            overflow: hidden;
        }

        .card-header-premium { padding: 25px 30px; border-bottom: 1px solid #f1f5f9; background: #fafafa; }
        .card-title-text { font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 900; color: var(--text-main); text-transform: uppercase; letter-spacing: 0.5px; margin: 0; }

        .premium-table { width: 100%; border-collapse: collapse; }
        .premium-table th { text-align: left; padding: 16px 20px; font-size: 10px; font-weight: 950; text-transform: uppercase; color: var(--text-muted); letter-spacing: 1px; border-bottom: 1px solid #f1f5f9; }
        .premium-table td { padding: 18px 20px; border-bottom: 1px solid #f8fafc; font-size: 13px; }

        .receipt-no-badge { background: #f8fafc; color: var(--maroon); border: 1px solid #edf2f7; padding: 4px 10px; border-radius: 8px; font-weight: 850; font-size: 11px; }
        .cust-name { font-weight: 800; color: var(--text-main); margin-bottom: 2px; }
        .bus-ref { font-weight: 700; color: #4b5563; }

        .btn-action-receipt {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            font-size: 13px;
        }

        .btn-action-receipt.view { background: rgba(128, 0, 0, 0.05); color: var(--maroon); }
        .btn-action-receipt.view:hover { background: var(--maroon); color: white; }

        .actions-flex {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .actions-flex form {
            display: inline-block;
            margin: 0;
        }

        .btn-action-receipt.edit { background: rgba(59, 130, 246, 0.05); color: #3b82f6; }
        .btn-action-receipt.edit:hover { background: #3b82f6; color: white; }

        .btn-action-receipt.delete { background: rgba(239, 68, 68, 0.05); color: #ef4444; }
        .btn-action-receipt.delete:hover { background: #ef4444; color: white; }

        .empty-table-state { padding: 60px 20px !important; text-align: center; color: var(--text-muted); }
        .empty-icon-wrap { font-size: 40px; opacity: 0.2; margin-bottom: 15px; }

        .fade-up {
            animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-admin-layout>

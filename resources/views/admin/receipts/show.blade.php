<x-admin-layout>
    <div class="receipt-show-wrapper fade-up">
        <div class="max-w-3xl mx-auto">

            <!-- HEADER ACTIONS -->
            <div class="flex justify-between items-center mb-8 no-print">
                <a href="{{ route('admin.receipts.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left mr-2"></i> Back to History
                </a>
                <div class="flex gap-4">
                    <button onclick="window.print()" class="btn-print">
                        <i class="fas fa-print mr-2"></i> Print
                    </button>
                    <button id="download-pdf-btn" class="btn-download">
                        <i class="fas fa-file-pdf mr-2"></i> Download
                    </button>
                    <button id="share-receipt-btn" class="btn-share">
                        <i class="fas fa-share-alt mr-2"></i> Share
                    </button>
                </div>
            </div>

            <!-- THE ACTUAL RECEIPT -->
            <div class="receipt-paper shadow-2xl" id="printable-receipt">
                <!-- Receipt Header -->
                <div class="receipt-header">
                    <div class="company-brand">
                        <img src="{{ asset('Images/image.png') }}" alt="Logo" class="receipt-logo">
                        <div class="brand-text">
                            <h2>MWIGITO EXCEL</h2>
                            <p>Premium Bus Services & Fleet Management</p>
                        </div>
                    </div>
                    <div class="receipt-meta">
                        <div class="receipt-badge">OFFICIAL RECEIPT</div>
                        <div class="meta-row">
                            <span class="meta-label">RECEIPT NO:</span>
                            <span class="meta-value">{{ $receipt->receipt_no }}</span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-label">DATE:</span>
                            <span class="meta-value text-uppercase">{{ $receipt->receipt_date->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                <div class="receipt-divider"></div>

                <!-- Customer Info -->
                <div class="receipt-section">
                    <h4 class="section-title">Received From:</h4>
                    <div class="customer-info-box">
                        <div class="info-item">
                            <span class="info-label">Name:</span>
                            <span class="info-value">{{ $receipt->customer_name }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Phone:</span>
                            <span class="info-value">{{ $receipt->customer_phone }}</span>
                        </div>
                    </div>
                </div>

                <!-- Trip Details -->
                <div class="receipt-section">
                    <h4 class="section-title">Service Details:</h4>
                    <table class="receipt-table">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Vehicle</th>
                                <th class="text-right">Total (KES)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <strong>{{ $receipt->trip_route }}</strong>
                                    <p class="text-xs text-gray-400 mt-1">Passenger transport service</p>
                                </td>
                                <td>{{ $receipt->bus_number }}</td>
                                <td class="text-right font-bold">{{ number_format($receipt->amount, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Totals & Payment -->
                <div class="receipt-footer-flex mt-10">
                    <div class="payment-summary">
                        <div class="payment-method-badge">
                            <i class="fas fa-check-circle mr-1"></i> PAID VIA {{ strtoupper($receipt->payment_method) }}
                        </div>
                        <p class="text-xs text-gray-500 mt-4 italic">Thank you for choosing Mwigito Excel. We value your
                            business and wish you a safe journey.</p>
                    </div>

                    <div class="total-block">
                        <div class="total-row subtotal">
                            <span>Subtotal</span>
                            <span>KES {{ number_format($receipt->amount, 2) }}</span>
                        </div>
                        <div class="total-row tax">
                            <span>V.A.T (0%)</span>
                            <span>KES 0.00</span>
                        </div>
                        <div class="total-divider"></div>
                        <div class="total-row grand-total">
                            <span>Amount Paid</span>
                            <span>KES {{ number_format($receipt->amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Authorization Stamp -->
                <div class="authorization-section flex justify-between items-end mt-16">
                    <div class="auth-box text-center">
                        <div class="signature-line"></div>
                        <span class="auth-label">Customer Signature</span>
                    </div>
                    <div class="system-stamp">
                        <div class="stamp-circle">
                            <span class="stamp-text">MWIGITO EXCEL</span>
                            <span class="stamp-verified">VERIFIED</span>
                            <span class="stamp-date">{{ date('Y') }}</span>
                        </div>
                    </div>
                    <div class="auth-box text-center">
                        <div class="signature-line"></div>
                        <span class="auth-label">Authorized Signatory</span>
                    </div>
                </div>
            </div>

            <div class="mt-8 text-center text-gray-400 text-xs no-print">
                <i class="fas fa-lock mr-1"></i> This is a system-generated document and is valid without physical
                stamp.
            </div>
        </div>
    </div>

    <!-- PDF GENERATOR SCRIPT -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('download-pdf-btn');
            if (btn) {
                btn.addEventListener('click', function () {
                    const content = document.getElementById('printable-receipt');
                    const options = {
                        margin: 0.3,
                        filename: 'Mwigito_Receipt_{{ $receipt->receipt_no }}.pdf',
                        image: { type: 'jpeg', quality: 0.98 },
                        html2canvas: { scale: 2, useCORS: true },
                        jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
                    };

                    // Show a simple processing indicator
                    btn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i> Processing...';
                    btn.disabled = true;

                    html2pdf().from(content).set(options).save().then(() => {
                        btn.innerHTML = '<i class="fas fa-file-pdf mr-2"></i> Download';
                        btn.disabled = false;
                    }).catch(err => {
                        console.error('PDF Error:', err);
                        btn.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i> Error';
                        btn.disabled = false;
                        alert('Could not generate PDF. Please use the Print button instead.');
                    });
                });
            }

            // SHARE FUNCTIONALITY
            const shareBtn = document.getElementById('share-receipt-btn');
            if (shareBtn) {
                shareBtn.addEventListener('click', function () {
                    const shareData = {
                        title: 'Mwigito Excel Receipt - {{ $receipt->receipt_no }}',
                        text: 'Official Receipt for {{ $receipt->customer_name }} (KES {{ number_format($receipt->amount, 2) }}) from Mwigito Excel.',
                        url: window.location.href
                    };

                    if (navigator.share) {
                        navigator.share(shareData).catch(err => console.log('Error sharing:', err));
                    } else {
                        // Fallback: Copy to clipboard
                        const dummy = document.createElement('input');
                        document.body.appendChild(dummy);
                        dummy.value = window.location.href;
                        dummy.select();
                        document.execCommand('copy');
                        document.body.removeChild(dummy);

                        shareBtn.innerHTML = '<i class="fas fa-check mr-2"></i> Link Copied';
                        setTimeout(() => {
                            shareBtn.innerHTML = '<i class="fas fa-share-alt mr-2"></i> Share';
                        }, 2000);
                    }
                });
            }
        });
    </script>

    <style>
        :root {
            --maroon: #800000;
            --gold: #c9a84c;
        }

        .receipt-show-wrapper {
            padding: 40px 0;
            background: #f1f5f9;
            min-height: 100vh;
        }

        .btn-print {
            background: var(--maroon);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 5px 15px rgba(128, 0, 0, 0.2);
        }

        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(128, 0, 0, 0.3);
        }

        .btn-download {
            background: #1f2937;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.2s;
            min-width: 150px;
        }

        .btn-download:hover {
            background: #000;
        }

        .btn-download:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .btn-share {
            background: #0aa622ff;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-share:hover {
            background: #008211ff;
            transform: translateY(-2px);
        }

        .btn-back {
            color: var(--maroon);
            text-decoration: none;
            font-weight: 800;
            font-size: 14px;
        }

        .receipt-paper {
            background: white;
            padding: 50px;
            border-radius: 2px;
            color: #1a202c;
            font-family: 'Inter', sans-serif;
            position: relative;
        }

        .receipt-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
        }

        .company-brand {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .receipt-logo {
            height: 75px;
        }

        .brand-text h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 28px;
            font-weight: 900;
            color: var(--maroon);
            margin: 0;
        }

        .brand-text p {
            font-size: 11px;
            font-weight: 800;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }

        .receipt-badge {
            background: var(--maroon);
            color: white;
            padding: 6px 15px;
            font-size: 11px;
            font-weight: 950;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }

        .meta-row {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 5px;
        }

        .meta-label {
            font-size: 12px;
            font-weight: 800;
            color: #a0aec0;
        }

        .meta-value {
            font-size: 13px;
            font-weight: 800;
            color: #1a202c;
        }

        .receipt-divider {
            height: 4px;
            background: #f7fafc;
            border-bottom: 1px solid #edf2f7;
            margin-bottom: 35px;
        }

        .section-title {
            font-size: 11px;
            font-weight: 900;
            color: #a0aec0;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 15px;
        }

        .customer-info-box {
            background: #fbfbfc;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid var(--gold);
        }

        .info-label {
            font-size: 13px;
            color: #718096;
            font-weight: 600;
            margin-right: 8px;
        }

        .info-value {
            font-size: 14px;
            font-weight: 800;
        }

        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .receipt-table th {
            text-align: left;
            background: #f7fafc;
            padding: 12px 15px;
            font-size: 11px;
            font-weight: 900;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #edf2f7;
        }

        .receipt-table td {
            padding: 20px 15px;
            border-bottom: 1px solid #f7fafc;
            font-size: 14px;
        }

        .receipt-footer-flex {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 50px;
        }

        .payment-method-badge {
            background: #f0fff4;
            color: #276749;
            padding: 8px 15px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 800;
            border: 1px solid #c6f6d5;
            display: inline-block;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }

        .total-row.subtotal {
            font-size: 14px;
            font-weight: 600;
            color: #718096;
        }

        .total-row.tax {
            font-size: 13px;
            font-weight: 600;
            color: #a0aec0;
        }

        .total-divider {
            height: 1px;
            background: #edf2f7;
            margin: 10px 0;
        }

        .total-row.grand-total {
            font-size: 20px;
            font-weight: 900;
            color: var(--maroon);
        }

        .signature-line {
            border-bottom: 2px solid #edf2f7;
            width: 160px;
            margin: 0 auto 10px;
        }

        .auth-label {
            font-size: 10px;
            font-weight: 800;
            color: #a0aec0;
            text-transform: uppercase;
        }

        .stamp-circle {
            width: 100px;
            height: 100px;
            border: 4px double rgba(128, 0, 0, 0.4);
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transform: rotate(-15deg);
            color: rgba(128, 0, 0, 0.4);
        }

        .stamp-text {
            font-size: 9px;
            font-weight: 900;
        }

        .stamp-verified {
            font-size: 12px;
            font-weight: 950;
            border-top: 1px solid rgba(128, 0, 0, 0.2);
            border-bottom: 1px solid rgba(128, 0, 0, 0.2);
            margin: 2px 0;
            padding: 0 4px;
        }

        .stamp-date {
            font-size: 8px;
            font-weight: 800;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .receipt-show-wrapper {
                padding: 0;
                background: white;
            }

            .receipt-paper {
                box-shadow: none !important;
                border: 1px solid #eee;
                padding: 30px;
            }
        }

        .fade-up {
            animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</x-admin-layout>
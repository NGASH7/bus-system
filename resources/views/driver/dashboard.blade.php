<x-driver-layout>
    <div class="max-w-7xl mx-auto sm:px-3 lg:px-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Driver Dashboard') }}
            </h2>
            <div class="flex gap-4">
                <span class="px-3 py-1 bg-maroon text-white text-xs rounded-full">Driver Account</span>
            </div>
        </div>

        <div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Trip Summary Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-maroon">
                    <h3 class="text-lg font-bold mb-4">Today's Trip Summary</h3>
                    <div class="flex justify-around text-center">
                        <div>
                            <div class="text-sm text-gray-500 uppercase">Trips</div>
                            <div class="text-2xl font-bold">4</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 uppercase">Hours</div>
                            <div class="text-2xl font-bold">6.5</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 uppercase">Status</div>
                            <div class="text-2xl font-bold text-green-600">Active</div>
                        </div>
                    </div>
                </div>

                <!-- Assigned Bus Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-maroon">
                    <h3 class="text-lg font-bold mb-4">Assigned Vehicle</h3>
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-maroon-pale text-maroon flex items-center justify-center rounded-xl text-3xl">
                           🚌
                        </div>
                        <div>
                            <div class="text-xl font-bold">KDP 923K (School Bus)</div>
                            <div class="text-sm text-gray-500">Last serviced: 12 days ago</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="module-card">
                    <div class="module-head">
                        <span class="module-icon"><i class="fas fa-id-card"></i></span>
                        <h3 class="text-lg font-bold">My License</h3>
                    </div>
                    <p class="module-label">Status</p>
                    <p class="module-value status-good">Valid</p>
                    <p class="module-meta">Expiry: Dec 14, 2027</p>
                </div>

                <div class="module-card">
                    <div class="module-head">
                        <span class="module-icon"><i class="fas fa-shield-alt"></i></span>
                        <h3 class="text-lg font-bold">Insurance</h3>
                    </div>
                    <p class="module-label">Coverage</p>
                    <p class="module-value">Comprehensive</p>
                    <p class="module-meta">Renewal: Aug 03, 2026</p>
                </div>

                <div class="module-card">
                    <div class="module-head">
                        <span class="module-icon"><i class="fas fa-screwdriver-wrench"></i></span>
                        <h3 class="text-lg font-bold">Bus Service</h3>
                    </div>
                    <p class="module-label">Next Service</p>
                    <p class="module-value">In 18 days</p>
                    <p class="module-meta">Last serviced: 12 days ago</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-maroon { background-color: #800000; }
        .text-maroon { color: #800000; }
        .bg-maroon-pale { background-color: rgba(128, 0, 0, 0.05); }

        .module-card {
            background: #fff;
            border: 1px solid #eee;
            border-top: 4px solid #800000;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .module-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.1);
        }

        .module-head {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
            color: #1f2937;
        }

        .module-icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(128, 0, 0, 0.08);
            color: #800000;
            font-size: 14px;
        }

        .module-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #6b7280;
            margin-bottom: 4px;
            font-weight: 600;
        }

        .module-value {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
        }

        .status-good {
            color: #16a34a;
        }

        .module-meta {
            font-size: 13px;
            color: #6b7280;
        }
    </style>
</x-driver-layout>

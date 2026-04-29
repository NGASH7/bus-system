@php(
    $layout = Auth::user()->isAdmin()
        ? 'admin-layout'
        : (Auth::user()->isDriver() ? 'driver-layout' : 'user-layout')
)
<x-dynamic-component :component="$layout">
    <div class="profile-dashboard-wrapper fade-up">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- SOPHISTICATED MINIMAL HEADER -->
            <div class="profile-header-minimal">
                <div class="p-header-left">
                    <div class="p-mini-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="p-header-text">
                        <h1 class="page-title">Management Console</h1>
                        <p class="p-subtitle">Manage your personal information and security preferences</p>
                    </div>
                </div>
                <div class="p-header-badge">{{ ucfirst(Auth::user()->role ?? 'User') }} Account</div>
            </div>

            <div class="profile-main-grid">
                <!-- TOP ROW: INFO & PASSWORD -->
                <div class="profile-row-split">
                    <!-- INFORMATION CARD -->
                    <div class="profile-card-premium">
                        <div class="p-card-header">
                            <h3 class="p-card-title"><i class="fas fa-user-circle mr-2"></i> Account Details</h3>
                        </div>
                        <div class="p-card-body">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <!-- PASSWORD CARD -->
                    <div class="profile-card-premium">
                        <div class="p-card-header">
                            <h3 class="p-card-title"><i class="fas fa-shield-alt mr-2"></i> Security</h3>
                        </div>
                        <div class="p-card-body">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>

                <!-- BOTTOM ROW: DANGER ZONE -->
                <div class="profile-card-premium danger-zone mt-10">
                    <div class="p-card-header">
                        <h3 class="p-card-title text-danger"><i class="fas fa-trash-alt mr-2"></i> Permanently Delete Account</h3>
                    </div>
                    <div class="p-card-body">
                        @include('profile.partials.delete-user-form')
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
            --bg-body: #f8fafc;
            --success: #16a34a;
            --danger: #e53e3e;
        }

        .profile-dashboard-wrapper {
            padding: 30px 0 60px 0;
        }

        /* HEADER */
        .profile-header-minimal {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 25px;
            border-bottom: 2px solid #edf2f7;
            margin-bottom: 40px;
        }

        .p-header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .p-mini-avatar {
            width: 50px;
            height: 50px;
            background: var(--maroon);
            color: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: 900;
            font-family: 'Outfit', sans-serif;
            box-shadow: 0 4px 12px rgba(128, 0, 0, 0.2);
        }

        .page-title {
            font-family: 'Outfit', sans-serif;
            font-size: 28px;
            font-weight: 900;
            color: var(--text-main);
            margin: 0;
            letter-spacing: -0.5px;
            line-height: 1;
        }

        .p-subtitle {
            font-size: 14px;
            color: var(--text-muted);
            margin-top: 4px;
            font-weight: 500;
        }

        .p-header-badge {
            padding: 6px 14px;
            background: rgba(128, 0, 0, 0.05);
            color: var(--maroon);
            border-radius: 99px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 1px solid rgba(128, 0, 0, 0.1);
        }

        /* GRID LAYOUT */
        .profile-row-split {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
        }

        @media (max-width: 1024px) {
            .profile-row-split { grid-template-columns: 1fr; }
        }

        /* PREMIUM CARDS */
        .profile-card-premium {
            background: white;
            border-radius: 24px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 4px 25px rgba(0,0,0,0.03);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .profile-card-premium:hover {
            box-shadow: 0 15px 45px rgba(0,0,0,0.06);
            transform: translateY(-5px);
        }

        .p-card-header {
            padding: 24px 30px;
            background: #fafafa;
            border-bottom: 1px solid #f1f5f9;
        }

        .p-card-title {
            font-family: 'Outfit', sans-serif;
            font-size: 18px;
            font-weight: 900;
            color: var(--text-main);
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .p-card-body {
            padding: 30px;
        }

        /* DANGER ZONE */
        .danger-zone {
            border: 1px solid rgba(229, 62, 62, 0.1);
        }
        .danger-zone .p-card-header {
            background: rgba(229, 62, 62, 0.02);
        }

        /* FORM POLISH */
        .p-card-body .mt-6 { margin-top: 0 !important; }
        .p-card-body .space-y-6 > * + * { margin-top: 20px !important; }

        .p-card-body input {
            border-radius: 10px !important;
            border: 1.5px solid #edf2f7 !important;
            padding: 12px 15px !important;
            font-size: 14px !important;
            transition: all 0.2s !important;
        }

        .p-card-body input:focus {
            border-color: var(--maroon) !important;
            background: #fff !important;
            box-shadow: 0 0 0 4px rgba(128, 0, 0, 0.05) !important;
        }

        .p-card-body label {
            font-size: 13px !important;
            font-weight: 800 !important;
            color: var(--text-main) !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            margin-bottom: 8px !important;
        }

        .p-card-body button {
            width: 100%;
            justify-content: center;
            background: var(--maroon) !important;
            border-radius: 10px !important;
            font-weight: 900 !important;
            padding: 14px !important;
            letter-spacing: 1px !important;
            transition: all 0.3s !important;
        }

        .p-card-body button:hover {
            background: var(--maroon-dark) !important;
            box-shadow: 0 8px 20px rgba(128, 0, 0, 0.2) !important;
        }

        .fade-up {
            animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-dynamic-component>

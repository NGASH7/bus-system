<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Mwigito Excel') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --maroon: #800000;
            --maroon-dark: #600000;
            --maroon-deep: #400000;
            --gold: #c9a84c;
            --off-white: #f8f9fa;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            overflow: hidden;
        }

        .admin-container {
            display: flex;
            height: 100vh;
            width: 100vw;
        }

        /* ─── SIDEBAR ───────────────────────── */
        .sidebar {
            width: 260px;
            background: var(--maroon-deep);
            color: white;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .sidebar-logo {
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar-logo img {
            height: 40px;
            width: auto;
        }

        .sidebar-nav {
            flex: 1;
            padding: 20px 0;
            overflow-y: auto;
            scrollbar-width: auto;
            scrollbar-color: rgba(96, 0, 0, 0.95) rgba(255, 255, 255, 0.12);
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 24px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.2s;
            border-left: 4px solid transparent;
            margin-bottom: 4px;
        }

        .nav-item i {
            width: 18px;
            font-size: 14px;
            margin-right: 12px;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: white;
        }

        .nav-item.active {
            background: var(--maroon);
            color: white;
            border-left-color: var(--gold);
        }

        .nav-badge {
            margin-left: auto;
            background: var(--maroon);
            color: white;
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: 700;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .sidebar-footer {
            padding: 20px 24px;
            background: rgba(0, 0, 0, 0.2);
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--gold);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--maroon-deep);
            font-weight: 800;
        }

        .user-details .name {
            font-size: 14px;
            font-weight: 600;
            display: block;
        }

        .user-details .date {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.5);
        }

        /* ─── MAIN CONTENT ──────────────────── */
        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .admin-header {
            height: 64px;
            background: white;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            flex-shrink: 0;
        }

        .header-title {
            font-family: 'Outfit', sans-serif;
            font-size: 18px;
            font-weight: 800;
            color: var(--maroon);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 28px;
        }

        .header-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #4b5563;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .header-btn:hover {
            color: var(--maroon);
        }

        .notification-btn {
            position: relative;
            color: #d97706;
        }

        .notification-btn:hover {
            color: #b45309;
        }

        .notification-btn i {
            font-size: 20px;
        }

        .notification-badge {
            position: absolute;
            top: -6px;
            right: -10px;
            min-width: 18px;
            height: 18px;
            padding: 0 5px;
            border-radius: 999px;
            background: #dc2626;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            line-height: 18px;
            text-align: center;
            border: 2px solid #fff;
        }

        .content-area {
            flex: 1;
            padding: 32px;
            overflow-y: auto;
        }

        /* scrollbar */
        .sidebar-nav::-webkit-scrollbar,
        .content-area::-webkit-scrollbar {
            width: 10px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb,
        .content-area::-webkit-scrollbar-thumb {
            background: rgba(96, 0, 0, 0.95);
            border-radius: 10px;
            border: 2px solid transparent;
            background-clip: content-box;
        }

        .sidebar-nav::-webkit-scrollbar-track,
        .content-area::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.12);
        }
    </style>
</head>

<body class="font-sans antialiased text-gray-900 leading-normal">
    <div class="admin-container">
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="sidebar-logo">
                <img src="{{ asset('Images/image.png') }}" alt="Logo">
                <span style="font-family:'Outfit',sans-serif; font-weight:800; font-size:16px;">ADMIN PANEL</span>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
                <a href="{{ route('admin.buses.index') }}"
                    class="nav-item {{ request()->routeIs('admin.buses.*') ? 'active' : '' }}">
                    <i class="fas fa-bus"></i> Buses
                </a>
                <a href="{{ route('admin.drivers.index') }}"
                    class="nav-item {{ request()->routeIs('admin.drivers.*') ? 'active' : '' }}">
                    <i class="fas fa-id-card"></i> Drivers
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-chart-line"></i> Analytics
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-shield-alt"></i> Insurance
                </a>
                <a href="{{ route('admin.schedule.index') }}" class="nav-item {{ request()->routeIs('admin.schedule.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i> Schedule
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="nav-item {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                    <i class="fas fa-ticket-alt"></i> Bookings
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-file-invoice-dollar"></i> Billing
                </a>
                <a href="{{ route('admin.receipts.index') }}"
                    class="nav-item {{ request()->routeIs('admin.receipts.*') ? 'active' : '' }}">
                    <i class="fas fa-receipt"></i> Receipts
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-cog"></i> Settings
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-history"></i> System Log
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar text-xs">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="user-details">
                        <span class="name">{{ Auth::user()->name }}</span>
                        <span class="date">{{ date('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- MAIN -->
        <div class="main-wrapper">
            <header class="admin-header">
                <div class="header-title">Mwigito Excel - Bus Management System</div>
                <div class="header-actions">
                    <a href="#" class="header-btn notification-btn" title="Notifications" aria-label="Notifications">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="header-btn">
                        <i class="fas fa-user-circle"></i> Profile
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" class="header-btn"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </form>
                </div>
            </header>

            <main class="content-area">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>
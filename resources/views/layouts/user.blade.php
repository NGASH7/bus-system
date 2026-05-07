<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Mwigito Excel') }} - Dashboard</title>

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
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            overflow: hidden;
        }

        .user-container {
            display: flex;
            height: 100vh;
            width: 100vw;
        }

        .sidebar {
            width: 260px;
            background: var(--maroon-deep);
            color: white;
            display: flex;
            flex-direction: column;
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
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
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
            font-size: 14px;
        }

        .nav-item i {
            width: 18px;
            font-size: 14px;
            margin-right: 12px;
        }

        .nav-item:hover,
        .nav-item.active {
            background: rgba(255, 255, 255, 0.05);
            color: white;
        }

        .nav-item.active {
            background: var(--maroon);
            border-left-color: var(--gold);
        }

        .sidebar-footer {
            padding: 20px 24px;
            background: rgba(0, 0, 0, 0.2);
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .user-header {
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

        .notification-wrap {
            position: relative;
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

        .notification-panel {
            position: absolute;
            right: 0;
            top: 36px;
            width: 360px;
            max-height: 420px;
            overflow: hidden;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            box-shadow: 0 16px 32px rgba(0, 0, 0, 0.12);
            display: none;
            z-index: 1000;
        }

        .notification-panel.show {
            display: block;
        }

        .notification-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 14px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 13px;
        }

        .notification-panel-header span {
            background: #f3f4f6;
            color: #374151;
            border-radius: 999px;
            font-size: 11px;
            padding: 2px 8px;
            font-weight: 700;
        }

        .notification-list {
            max-height: 365px;
            overflow-y: auto;
        }

        .notification-item {
            display: flex;
            gap: 10px;
            padding: 12px 14px;
            border-bottom: 1px solid #f8fafc;
        }
        .notification-item-link {
            display: block;
            text-decoration: none;
            color: inherit;
        }
        .notification-item-link:hover .notification-item {
            background: #f9fafb;
        }
        .notification-item.unread {
            background: #fffaf0;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            flex-shrink: 0;
        }

        .notification-icon.success { background: #ecfdf3; color: #16a34a; }
        .notification-icon.warning { background: #fffbeb; color: #d97706; }
        .notification-icon.danger { background: #fef2f2; color: #dc2626; }
        .notification-icon.info { background: #eff6ff; color: #2563eb; }

        .notification-body { min-width: 0; }
        .notification-title {
            margin: 0 0 3px;
            font-size: 12px;
            font-weight: 700;
            color: #111827;
        }

        .notification-message {
            margin: 0 0 5px;
            font-size: 12px;
            line-height: 1.35;
            color: #4b5563;
        }

        .notification-time {
            font-size: 11px;
            color: #9ca3af;
            font-weight: 600;
        }

        .notification-empty {
            padding: 18px 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #6b7280;
            font-size: 12px;
        }

        .notification-footer-link {
            display: block;
            text-align: center;
            padding: 10px 12px;
            border-top: 1px solid #f3f4f6;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            color: var(--maroon);
            background: #fff;
        }

        .content-area {
            flex: 1;
            padding: 20px 32px 32px 32px;
            overflow-y: auto;
        }

        .content-area::-webkit-scrollbar {
            width: 8px;
        }

        .content-area::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 10px;
        }

        .flash-toast {
            position: fixed;
            top: 78px;
            right: 24px;
            z-index: 9999;
            background: #ecfdf3;
            border: 1px solid #86efac;
            color: #166534;
            border-radius: 12px;
            padding: 12px 14px;
            min-width: 260px;
            max-width: 420px;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: flex-start;
            gap: 10px;
            animation: toastIn 0.25s ease;
        }

        .flash-toast i {
            margin-top: 2px;
            color: #16a34a;
        }

        .flash-toast .title {
            font-weight: 700;
            font-size: 13px;
            margin-bottom: 2px;
        }

        .flash-toast .msg {
            font-size: 12px;
            line-height: 1.4;
        }

        @keyframes toastIn {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body class="font-sans antialiased text-gray-900 leading-normal">
    @if(session('success'))
        <div class="flash-toast" id="flash-success-toast" role="status" aria-live="polite">
            <i class="fas fa-check-circle"></i>
            <div>
                <div class="title">Submitted Successfully</div>
                <div class="msg">{{ session('success') }}</div>
            </div>
        </div>
        <script>
            setTimeout(function () {
                const toast = document.getElementById('flash-success-toast');
                if (toast) {
                    toast.style.transition = 'opacity 0.25s ease';
                    toast.style.opacity = '0';
                    setTimeout(function () { toast.remove(); }, 280);
                }
            }, 4200);
        </script>
    @endif

    <div class="user-container">
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="sidebar-logo">
                <img src="{{ asset('Images/image.png') }}" alt="Mwigito Excel Bus System" title="Mwigito Excel Bus System">
                <span style="font-family:'Outfit',sans-serif; font-weight:800; font-size:16px;">MWIGITO EXCEL</span>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}"
                    class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
                <a href="{{ route('bookings.create') }}" class="nav-item {{ request()->routeIs('bookings.create') ? 'active' : '' }}">
                    <i class="fas fa-bus"></i> Book A Bus
                </a>
                <a href="{{ route('bookings.index') }}" class="nav-item {{ request()->routeIs('bookings.index') || (request()->routeIs('bookings.*') && !request()->routeIs('bookings.create')) ? 'active' : '' }}">
                    <i class="fas fa-history"></i> My Bookings
                </a>
                <a href="{{ route('receipts.index') }}" class="nav-item {{ request()->routeIs('receipts.*') ? 'active' : '' }}">
                    <i class="fas fa-receipt"></i> Receipts
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-gold flex items-center justify-center text-maroon font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-sm font-semibold">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-white text-opacity-50 text-uppercase">{{ date('M d, Y') }}</div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- MAIN -->
        <div class="main-wrapper">
            <header class="user-header">
                <div class="header-title">Mwigito Excel Bus Management System</div>
                <div class="header-actions">
                    @include('partials.notification-dropdown')
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
    <script>
        (function () {
            const toggle = document.getElementById('notification-toggle');
            const panel = document.getElementById('notification-panel');
            const wrap = document.getElementById('notification-wrap');
            const badge = document.getElementById('notification-badge');
            const panelCount = document.getElementById('notification-panel-count');
            if (!toggle || !panel || !wrap) return;

            toggle.addEventListener('click', function (event) {
                event.preventDefault();
                panel.classList.toggle('show');
                if (panel.classList.contains('show')) {
                    if (badge) badge.textContent = '0';
                    if (panelCount) panelCount.textContent = '0';
                    fetch('{{ route('notifications.read-all') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }).catch(function () {});
                }
            });

            document.addEventListener('click', function (event) {
                if (!wrap.contains(event.target)) {
                    panel.classList.remove('show');
                }
            });
        })();
    </script>
</body>

</html>
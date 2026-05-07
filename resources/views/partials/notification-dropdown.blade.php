<div class="notification-wrap" id="notification-wrap">
    <a href="#" class="header-btn notification-btn" title="Notifications" aria-label="Notifications" id="notification-toggle">
        <i class="fas fa-bell"></i>
        @if(($appNotifications['count'] ?? 0) > 0)
            <span class="notification-badge" id="notification-badge">{{ $appNotifications['count'] }}</span>
        @endif
    </a>

    <div class="notification-panel" id="notification-panel">
        <div class="notification-panel-header">
            <strong>Recent Notifications</strong>
            <span id="notification-panel-count">{{ $appNotifications['count'] ?? 0 }}</span>
        </div>
        <div class="notification-list">
            @forelse(($appNotifications['items'] ?? collect()) as $notice)
                @php
                    $targetLink = $notice['link'] ?? route('notifications.index');
                @endphp
                <a href="{{ $targetLink }}" class="notification-item-link">
                    <div class="notification-item {{ ($notice['is_read'] ?? false) ? '' : 'unread' }}">
                        <div class="notification-icon {{ $notice['level'] }}">
                            <i class="{{ $notice['icon'] }}"></i>
                        </div>
                        <div class="notification-body">
                            <p class="notification-title">{{ $notice['title'] }}</p>
                            <p class="notification-message">{{ $notice['message'] }}</p>
                            <span class="notification-time">{{ $notice['time_text'] }}</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="notification-empty">
                    <i class="fas fa-bell-slash"></i>
                    <span>No recent notifications yet.</span>
                </div>
            @endforelse
        </div>
        <a href="{{ route('notifications.index') }}" class="notification-footer-link">View all notifications</a>
    </div>
</div>


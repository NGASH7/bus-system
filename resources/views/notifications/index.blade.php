<x-dynamic-component :component="$layoutComponent">
    <div class="notifications-page">
        <div class="notifications-head">
            <h1><i class="fas fa-bell"></i> All Notifications</h1>
            <p>Role-based activity feed with persistent read status.</p>
        </div>

        <div class="notifications-card">
            @forelse($notifications as $notification)
                <div class="notification-row {{ is_null($notification->read_at) ? 'unread' : '' }}">
                    <div class="notification-icon {{ $notification->level }}">
                        <i class="{{ $notification->icon }}"></i>
                    </div>
                    <div class="notification-body">
                        <div class="notification-title">{{ $notification->title }}</div>
                        <div class="notification-message">{{ $notification->message }}</div>
                        <div class="notification-meta">
                            <span>{{ optional($notification->noticed_at)->format('d M Y, H:i') ?? $notification->created_at->format('d M Y, H:i') }}</span>
                            <span>{{ optional($notification->noticed_at)->diffForHumans() ?? $notification->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    @if(is_null($notification->read_at))
                        <button type="button" class="mark-read-btn" data-id="{{ $notification->id }}">Mark read</button>
                    @endif
                </div>
            @empty
                <div class="empty-notifications">
                    <i class="fas fa-bell-slash"></i>
                    <span>No notifications yet.</span>
                </div>
            @endforelse
        </div>
    </div>

    <style>
        .notifications-page { max-width: 980px; margin: 0 auto; }
        .notifications-head h1 { margin: 0; font-size: 26px; font-weight: 800; color: #111827; }
        .notifications-head p { margin: 6px 0 18px; color: #6b7280; font-size: 13px; }
        .notifications-card { background: #fff; border: 1px solid #eef2f7; border-radius: 16px; overflow: hidden; }
        .notification-row { display: flex; gap: 12px; padding: 14px 16px; border-bottom: 1px solid #f8fafc; align-items: flex-start; }
        .notification-row:last-child { border-bottom: none; }
        .notification-row.unread { background: #fffbeb; }
        .notification-icon { width: 36px; height: 36px; border-radius: 11px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .notification-icon.success { background: #ecfdf3; color: #16a34a; }
        .notification-icon.warning { background: #fffbeb; color: #d97706; }
        .notification-icon.danger { background: #fef2f2; color: #dc2626; }
        .notification-icon.info { background: #eff6ff; color: #2563eb; }
        .notification-body { flex: 1; }
        .notification-title { font-weight: 700; font-size: 13px; color: #111827; margin-bottom: 3px; }
        .notification-message { font-size: 12px; color: #4b5563; line-height: 1.4; }
        .notification-meta { display: flex; gap: 12px; font-size: 11px; color: #9ca3af; margin-top: 6px; }
        .mark-read-btn { border: 1px solid #d1d5db; background: #fff; border-radius: 999px; padding: 6px 10px; font-size: 11px; font-weight: 700; cursor: pointer; }
        .empty-notifications { padding: 18px; color: #6b7280; display: flex; gap: 8px; align-items: center; }
    </style>

    <script>
        document.querySelectorAll('.mark-read-btn').forEach(function (button) {
            button.addEventListener('click', function () {
                fetch('/notifications/' + button.dataset.id + '/read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(function () { window.location.reload(); });
            });
        });
    </script>
</x-dynamic-component>


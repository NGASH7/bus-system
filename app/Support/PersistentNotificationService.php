<?php

namespace App\Support;

use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Support\Collection;

class PersistentNotificationService
{
    public static function syncAndFetch(User $user, int $limit = 8): array
    {
        $generated = RoleNotificationService::forUser($user, 20);

        foreach ($generated['items'] as $item) {
            $fingerprint = md5($item['type'] . '|' . $item['title'] . '|' . $item['message']);

            UserNotification::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'fingerprint' => $fingerprint,
                ],
                [
                    'title' => $item['title'],
                    'message' => $item['message'],
                    'icon' => $item['icon'],
                    'level' => $item['level'],
                    'type' => $item['type'],
                    'link' => $item['link'] ?? null,
                    'noticed_at' => $item['time'],
                ]
            );
        }

        $items = UserNotification::query()
            ->where('user_id', $user->id)
            ->latest('noticed_at')
            ->latest('id')
            ->take($limit)
            ->get()
            ->map(function (UserNotification $notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'icon' => $notification->icon,
                    'level' => $notification->level,
                    'type' => $notification->type,
                    'link' => $notification->link,
                    'time_text' => optional($notification->noticed_at)->diffForHumans() ?? $notification->created_at->diffForHumans(),
                    'is_read' => !is_null($notification->read_at),
                ];
            });

        $unreadCount = UserNotification::query()
            ->where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();

        return [
            'count' => $unreadCount,
            'items' => $items,
        ];
    }

    public static function allForPage(User $user): Collection
    {
        return UserNotification::query()
            ->where('user_id', $user->id)
            ->latest('noticed_at')
            ->latest('id')
            ->get();
    }
}


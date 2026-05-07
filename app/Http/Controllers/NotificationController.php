<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use App\Support\PersistentNotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        PersistentNotificationService::syncAndFetch($user, 10);

        $notifications = PersistentNotificationService::allForPage($user);
        $layoutComponent = $user->isAdmin() ? 'admin-layout' : ($user->isDriver() ? 'driver-layout' : 'user-layout');

        return view('notifications.index', compact('notifications', 'layoutComponent'));
    }

    public function markAllRead(Request $request): JsonResponse
    {
        UserNotification::query()
            ->where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['ok' => true]);
    }

    public function markRead(UserNotification $notification): JsonResponse
    {
        abort_unless($notification->user_id === Auth::id(), 403);

        if (is_null($notification->read_at)) {
            $notification->update(['read_at' => now()]);
        }

        return response()->json(['ok' => true]);
    }
}


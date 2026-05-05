<?php

namespace App\Support;

use App\Models\SystemLog;
use App\Models\User;
use Throwable;

class SystemActivity
{
    public static function record(string $action, string $description, ?User $user = null, array $metadata = []): void
    {
        try {
            $request = request();

            SystemLog::create([
                'user_id' => $user?->id,
                'action' => $action,
                'description' => $description,
                'metadata' => empty($metadata) ? null : $metadata,
                'ip_address' => $request?->ip(),
                'user_agent' => $request?->userAgent(),
            ]);
        } catch (Throwable $e) {
            // Never block user actions if activity logging fails.
        }
    }
}


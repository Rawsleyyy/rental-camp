<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    /**
     * Record a staff or admin operational action to activity_logs.
     */
    public static function log(string $action, string $description, ?int $userId = null): ?ActivityLog
    {
        $actorId = $userId ?? Auth::id();

        if (! $actorId) {
            return null;
        }

        return ActivityLog::create([
            'user_id' => $actorId,
            'action' => $action,
            'description' => $description,
        ]);
    }
}

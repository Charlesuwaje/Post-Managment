<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Enums\ActivityLogEnum;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class ActivityLogService
{
    /**
     * Log an activity.
     *
     * @param int $userId
     * @param string $action
     * @param string $entity
     * @param string $message
     * @return ActivityLog
     */
    public function logActivity(int $userId, int $postId, string $action, array $data, string $message)
    {
        return ActivityLog::create([
            'user_id' => $userId,
            'post_id' => $postId,
            'action' => $action,
            'data' => json_encode($data),
            'message' => $message,
        ]);
    }

    public function getUserActivities(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return $user->activities() 
            ->latest()
            ->paginate($perPage);
    }
    public function getAllActivities(int $perPage = 10): array
    {
        $activities = ActivityLog::latest()->paginate($perPage);

        return [
            'success' => true,
            'activities' => $activities,
        ];
    }
}

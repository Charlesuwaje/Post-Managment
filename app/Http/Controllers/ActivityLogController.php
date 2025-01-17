<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ActivityLogController extends Controller
{
    public function __construct(public readonly ActivityLogService $activityLogService)
    {
    }

    public function getUserActivities(Request $request, User $user): JsonResponse
    {
        $perPage = $request->get('perPage', 10);

        $activities = $this->activityLogService->getUserActivities($user, $perPage);

        return $this->success(
            // $activities->toArray(),
            $activities,
            Response::HTTP_OK
        );
    }

    /**
     * Get all activities.
     *
     * @return JsonResponse
     */
    public function adminActivities(Request $request): JsonResponse
    {
        $result = $this->activityLogService->getAllActivities($request->perPage);

        if (!$result['success']) {
            return $this->error(
                $result['message'],
                Response::HTTP_SERVICE_UNAVAILABLE
            );
        }

        return $this->success(
            $result['activities'],
            Response::HTTP_OK
        );
    }
}

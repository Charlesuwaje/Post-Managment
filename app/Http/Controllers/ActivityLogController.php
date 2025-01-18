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
    public function __construct(public readonly ActivityLogService $activityLogService) {}


    public function getUserActivities(Request $request, User $user): JsonResponse
    {
        $perPage = $request->get('perPage', 10);

        $activities = $this->activityLogService->getUserActivities($user, (int) $perPage);

        return response()->json([
            'success' => true,
            'data' => $activities,
        ], Response::HTTP_OK);
    }


    public function Activities(Request $request): JsonResponse
    {
        $result = $this->activityLogService->getAllActivities((int) $request->perPage);
        // dd($result);

        if (!$result['success']) {
            return $this->error(
                $result['message'],
                Response::HTTP_SERVICE_UNAVAILABLE
            );
        }
        return response()->json([
            $result['activities'],
        ], Response::HTTP_OK);
    }
}

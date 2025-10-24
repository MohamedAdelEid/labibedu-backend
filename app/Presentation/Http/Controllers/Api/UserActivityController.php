<?php

namespace App\Presentation\Http\Controllers\Api;

use App\Domain\Interfaces\Services\UserActivityServiceInterface;
use App\Infrastructure\Helpers\ApiResponse;
use App\Presentation\Http\Requests\Activity\ActivitySummaryRequest;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class UserActivityController extends Controller
{
    public function __construct(
        private UserActivityServiceInterface $userActivityService
    ) {}

    /**
     * Get user's activity summary for a specific period.
     */
    public function getActivitySummary(ActivitySummaryRequest $request): JsonResponse
    {
        $userId = Auth::user()->id;
        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'));

        $summary = $this->userActivityService->getUserActivitySummary($userId, $startDate, $endDate);

        return ApiResponse::success($summary, __('activity.summary_retrieved'));
    }

    /**
     * Get user's total time spent on platform.
     */
    public function getTotalTimeSpent(Request $request): JsonResponse
    {
        $userId = Auth::user()->id;
        $totalTime = $this->userActivityService->getTotalTimeSpent($userId);

        return ApiResponse::success($totalTime, __('activity.total_time_retrieved'));
    }

    /**
     * Get user's daily activity for the last N days.
     */
    public function getDailyActivity(Request $request): JsonResponse
    {
        $userId = Auth::user()->id;
        $days = $request->input('days', 30);

        $dailyActivity = $this->userActivityService->getDailyActivity($userId, $days);

        return ApiResponse::success($dailyActivity, __('activity.daily_activity_retrieved'));
    }

    /**
     * Get most active users (admin only).
     */
    public function getMostActiveUsers(Request $request): JsonResponse
    {
        // Check if user is admin
        if (Auth::user()->role->value !== 'admin') {
            return ApiResponse::error(__('auth.unauthorized'), null, 403);
        }

        $limit = $request->input('limit', 10);
        $mostActiveUsers = $this->userActivityService->getMostActiveUsers($limit);

        return ApiResponse::success($mostActiveUsers, __('activity.most_active_users_retrieved'));
    }

    /**
     * End current user session.
     */
    public function endSession(Request $request): JsonResponse
    {
        $userId = Auth::user()->id;
        $this->userActivityService->endSession($userId);

        return ApiResponse::success(null, __('activity.session_ended'));
    }
}

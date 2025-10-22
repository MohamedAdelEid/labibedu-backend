<?php

namespace App\Application\Services;

use App\Domain\Enums\ActivityType;
use App\Domain\Interfaces\Repositories\UserActivityRepositoryInterface;
use App\Domain\Interfaces\Services\UserActivityServiceInterface;
use App\Infrastructure\Models\UserActivity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class UserActivityService implements UserActivityServiceInterface
{
    public function __construct(
        private UserActivityRepositoryInterface $userActivityRepository
    ) {
    }

    public function trackLogin(int $userId, string $ipAddress, string $userAgent): void
    {
        // End any existing active sessions
        $this->endSession($userId);

        // Create new login session
        $this->userActivityRepository->create([
            'user_id' => $userId,
            'session_start' => Carbon::now(),
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'activity_type' => ActivityType::LOGIN,
        ]);
    }

    public function trackApiRequest(int $userId, string $endpoint, string $ipAddress, string $userAgent): void
    {
        $lastSession = $this->userActivityRepository->findLastActiveSession($userId);
        $now = Carbon::now();
        $sessionTimeoutMinutes = config('activity.session_timeout_minutes', 30);

        if ($lastSession && $lastSession->session_start) {
            // Check if session has expired (more than X minutes of inactivity)
            $lastActivity = $lastSession->updated_at ?? $lastSession->created_at;
            $minutesSinceLastActivity = $now->diffInMinutes($lastActivity);

            if ($minutesSinceLastActivity > $sessionTimeoutMinutes) {
                // Session expired - end the old session and create a new one
                $this->endSession($userId);

                // Create new session
                $this->userActivityRepository->create([
                    'user_id' => $userId,
                    'session_start' => $now,
                    'ip_address' => $ipAddress,
                    'user_agent' => $userAgent,
                    'endpoint' => $endpoint,
                    'activity_type' => ActivityType::API_REQUEST,
                ]);
            } else {
                // Update existing session
                $duration = $lastSession->session_start->diffInSeconds($now);

                $this->userActivityRepository->update($lastSession, [
                    'duration_seconds' => $duration,
                    'endpoint' => $endpoint,
                    'ip_address' => $ipAddress,
                    'user_agent' => $userAgent,
                ]);
            }
        } else {
            // Create new session
            $this->userActivityRepository->create([
                'user_id' => $userId,
                'session_start' => $now,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'endpoint' => $endpoint,
                'activity_type' => ActivityType::API_REQUEST,
            ]);
        }
    }

    public function endSession(int $userId): void
    {
        $lastSession = $this->userActivityRepository->findLastActiveSession($userId);

        if ($lastSession) {
            $now = Carbon::now();
            $duration = $lastSession->session_start ? $lastSession->session_start->diffInSeconds($now) : 0;

            $this->userActivityRepository->update($lastSession, [
                'session_end' => $now,
                'duration_seconds' => $duration,
                'activity_type' => ActivityType::SESSION_END,
            ]);
        }
    }

    public function getUserActivitySummary(int $userId, Carbon $startDate, Carbon $endDate): array
    {
        return $this->userActivityRepository->getUserActivitySummary($userId, $startDate, $endDate);
    }

    public function getTotalTimeSpent(int $userId): array
    {
        $totalSeconds = $this->userActivityRepository->getTotalTimeSpent($userId);

        return [
            'total_seconds' => $totalSeconds,
            'total_minutes' => round($totalSeconds / 60, 2),
            'total_hours' => round($totalSeconds / 3600, 2),
            'total_days' => round($totalSeconds / 86400, 2),
        ];
    }

    public function getDailyActivity(int $userId, int $days = 30): Collection
    {
        return $this->userActivityRepository->getDailyActivity($userId, $days);
    }

    public function getMostActiveUsers(int $limit = 10): Collection
    {
        return $this->userActivityRepository->getMostActiveUsers($limit);
    }

    public function cleanupOldRecords(int $daysToKeep = 90): int
    {
        $cutoffDate = Carbon::now()->subDays($daysToKeep);

        return UserActivity::where('created_at', '<', $cutoffDate)->delete();
    }
}

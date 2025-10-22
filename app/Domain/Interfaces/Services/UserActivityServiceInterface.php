<?php

namespace App\Domain\Interfaces\Services;

use App\Domain\Enums\ActivityType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

interface UserActivityServiceInterface
{
    /**
     * Track user login activity.
     */
    public function trackLogin(int $userId, string $ipAddress, string $userAgent): void;

    /**
     * Track API request activity.
     */
    public function trackApiRequest(int $userId, string $endpoint, string $ipAddress, string $userAgent): void;

    /**
     * End user session.
     */
    public function endSession(int $userId): void;

    /**
     * Get user activity summary.
     */
    public function getUserActivitySummary(int $userId, Carbon $startDate, Carbon $endDate): array;

    /**
     * Get total time spent by user.
     */
    public function getTotalTimeSpent(int $userId): array;

    /**
     * Get daily activity for a user.
     */
    public function getDailyActivity(int $userId, int $days = 30): Collection;

    /**
     * Get most active users.
     */
    public function getMostActiveUsers(int $limit = 10): Collection;

    /**
     * Clean up old activity records.
     */
    public function cleanupOldRecords(int $daysToKeep = 90): int;
}

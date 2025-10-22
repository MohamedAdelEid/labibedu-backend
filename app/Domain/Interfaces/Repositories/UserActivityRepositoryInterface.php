<?php

namespace App\Domain\Interfaces\Repositories;

use App\Infrastructure\Models\User;
use App\Infrastructure\Models\UserActivity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

interface UserActivityRepositoryInterface
{
    /**
     * Create a new user activity record.
     */
    public function create(array $data): UserActivity;

    /**
     * Find the last active session for a user.
     */
    public function findLastActiveSession(int $userId): ?UserActivity;

    /**
     * Update an existing user activity record.
     */
    public function update(UserActivity $activity, array $data): bool;

    /**
     * Get user activity summary for a specific period.
     */
    public function getUserActivitySummary(int $userId, Carbon $startDate, Carbon $endDate): array;

    /**
     * Get total time spent by user.
     */
    public function getTotalTimeSpent(int $userId): int;

    /**
     * Get daily activity for a user.
     */
    public function getDailyActivity(int $userId, int $days = 30): Collection;

    /**
     * End all active sessions for a user.
     */
    public function endAllActiveSessions(int $userId): int;

    /**
     * Get users with most activity.
     */
    public function getMostActiveUsers(int $limit = 10): Collection;
}

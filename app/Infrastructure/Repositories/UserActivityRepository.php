<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\Repositories\UserActivityRepositoryInterface;
use App\Infrastructure\Models\UserActivity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class UserActivityRepository implements UserActivityRepositoryInterface
{
    public function create(array $data): UserActivity
    {
        return UserActivity::create($data);
    }

    public function findLastActiveSession(int $userId): ?UserActivity
    {
        return UserActivity::where('user_id', $userId)
            ->where('activity_type', 'api_request')
            ->whereNull('session_end')
            ->orderBy('session_start', 'desc')
            ->first();
    }

    public function update(UserActivity $activity, array $data): bool
    {
        return $activity->update($data);
    }

    public function getUserActivitySummary(int $userId, Carbon $startDate, Carbon $endDate): array
    {
        $activities = UserActivity::where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $totalDuration = $activities->sum('duration_seconds');
        $sessionCount = $activities->where('activity_type', 'login')->count();
        $apiRequestCount = $activities->where('activity_type', 'api_request')->count();

        return [
            'total_duration_seconds' => $totalDuration,
            'total_duration_minutes' => round($totalDuration / 60, 2),
            'total_duration_hours' => round($totalDuration / 3600, 2),
            'session_count' => $sessionCount,
            'api_request_count' => $apiRequestCount,
            'average_session_duration_minutes' => $sessionCount > 0 ? round($totalDuration / $sessionCount / 60, 2) : 0,
        ];
    }

    public function getTotalTimeSpent(int $userId): int
    {
        return UserActivity::where('user_id', $userId)
            ->sum('duration_seconds');
    }

    public function getDailyActivity(int $userId, int $days = 30): Collection
    {
        $startDate = Carbon::now()->subDays($days);

        return UserActivity::where('user_id', $userId)
            ->where('created_at', '>=', $startDate)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(duration_seconds) as total_duration'),
                DB::raw('COUNT(*) as activity_count')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'desc')
            ->get();
    }

    public function endAllActiveSessions(int $userId): int
    {
        $now = Carbon::now();

        return UserActivity::where('user_id', $userId)
            ->where('activity_type', 'api_request')
            ->whereNull('session_end')
            ->update([
                'session_end' => $now,
                'activity_type' => 'session_end',
            ]);
    }

    public function getMostActiveUsers(int $limit = 10): Collection
    {
        return UserActivity::select(
            'user_id',
            DB::raw('SUM(duration_seconds) as total_duration'),
            DB::raw('COUNT(*) as activity_count')
        )
            ->groupBy('user_id')
            ->orderBy('total_duration', 'desc')
            ->limit($limit)
            ->with('user')
            ->get();
    }
}

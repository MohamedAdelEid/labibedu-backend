<?php

namespace App\Application\Services;

use App\Domain\Interfaces\Services\VideoServiceInterface;
use App\Domain\Interfaces\Repositories\VideoRepositoryInterface;
use App\Infrastructure\Models\VideoProgress;
use App\Infrastructure\Models\Video;
use Exception;

class VideoService implements VideoServiceInterface
{
    public function __construct(
        private VideoProgress $videoProgressModel,
        private Video $videoModel
    ) {
    }

    /**
     * Check if video is completed and get XP/coins values
     */
    public function getVideoCompletionScoring(int $studentId, int $videoId): array
    {
        // Check if video progress exists and is completed
        $videoProgress = $this->videoProgressModel
            ->where('student_id', $studentId)
            ->where('video_id', $videoId)
            ->where('is_completed', true)
            ->with('video')
            ->first();

        if (!$videoProgress) {
            throw new Exception('Video must be completed first before scoring');
        }

        if (!$videoProgress->video) {
            throw new Exception('Video not found');
        }

        return [
            'success' => true,
            'xp' => $videoProgress->video->xp ?? 0,
            'coins' => $videoProgress->video->coins ?? 0,
            'video_id' => $videoId,
            'completed_at' => $videoProgress->updated_at,
        ];
    }
}

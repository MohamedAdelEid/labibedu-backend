<?php

namespace App\Domain\Interfaces\Services;

interface VideoServiceInterface
{
    /**
     * Check if video is completed and get XP/coins values
     *
     * @param int $studentId
     * @param int $videoId
     * @return array
     */
    public function getVideoCompletionScoring(int $studentId, int $videoId): array;
}

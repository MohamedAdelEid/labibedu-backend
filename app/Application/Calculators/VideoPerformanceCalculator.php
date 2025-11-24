<?php

namespace App\Application\Calculators;

class VideoPerformanceCalculator
{
    /**
     * Calculate video performance metrics
     * 
     * @param mixed $video
     * @param mixed $progress
     * @param array|null $relatedTrainingPerformance
     * @return array
     */
    public function calculate($video, $progress, ?array $relatedTrainingPerformance = null): array
    {
        $earnedMarks = 0;
        $earnedXp = 0;
        $earnedCoins = 0;

        if ($relatedTrainingPerformance) {
            $earnedMarks += $relatedTrainingPerformance['earned_marks'] ?? 0;
            $earnedXp += $relatedTrainingPerformance['earned_xp'] ?? 0;
            $earnedCoins += $relatedTrainingPerformance['earned_coins'] ?? 0;
        }

        return [
            'earned_marks' => $earnedMarks,
            'earned_xp' => $earnedXp,
            'earned_coins' => $earnedCoins,
        ];
    }
}

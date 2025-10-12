<?php

namespace App\Application\Calculators;

class BookPerformanceCalculator
{
    /**
     * Calculate book performance metrics
     * 
     * @param mixed $book
     * @param mixed $progress
     * @param array|null $relatedTrainingPerformance
     * @return array
     */
    public function calculate($book, $progress, ?array $relatedTrainingPerformance = null): array
    {
        $earnedMarks = $progress?->is_completed ? $book->marks : 0;
        $earnedXp = $progress?->is_completed ? $book->xp : 0;
        $earnedCoins = $progress?->is_completed ? $book->coins : 0;

        if ($relatedTrainingPerformance) {
            $earnedMarks += $relatedTrainingPerformance['earned_marks'] ?? 0;
            $earnedXp += $relatedTrainingPerformance['earned_xp'] ?? 0;
            $earnedCoins += $relatedTrainingPerformance['earned_coins'] ?? 0;
        }

        $data = [
            'earned_marks' => $earnedMarks,
            'earned_xp' => $earnedXp,
            'earned_coins' => $earnedCoins,
        ];

        if ($progress) {
            $data['pages_read'] = $progress->pages_read;
            $data['progress'] = $book->pages_count > 0 
                ? round(($progress->pages_read / $book->pages_count) * 100) . '%' 
                : '0%';
        }

        return $data;
    }
}

<?php

namespace App\Application\Calculators;

use App\Application\Services\BookProgressService;

class BookPerformanceCalculator
{
    public function __construct(
        private BookProgressService $bookProgressService
    ) {
    }

    /**
     * Calculate book performance metrics
     * 
     * @param mixed $book - Book model
     * @param int $studentId - Student ID
     * @param array|null $relatedTrainingPerformance - Related training performance data
     * @return array
     */
    public function calculate($book, int $studentId, ?array $relatedTrainingPerformance = null): array
    {
        // Check if book is completed
        $isCompleted = $this->bookProgressService->isRead($book, $studentId);
        
        $earnedMarks = $isCompleted ? $book->marks : 0;
        $earnedXp = $isCompleted ? $book->xp : 0;
        $earnedCoins = $isCompleted ? $book->coins : 0;

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

        // Get reading status for progress calculation
        $readingStatus = $this->bookProgressService->getReadingStatus($book, $studentId);
        
        if ($readingStatus === 'completed') {
            $data['progress'] = '100%';
        } elseif ($readingStatus === 'in_progress') {
            // For in_progress, we can't accurately calculate without page tracking
            // Return a generic message or partial percentage
            $data['progress'] = 'in_progress';
        } else {
            $data['progress'] = '0%';
        }

        return $data;
    }
}

<?php

namespace App\Domain\Interfaces\Services;

interface BookServiceInterface
{
    /**
     * Check if book is completed and get XP/coins values
     *
     * @param int $studentId
     * @param int $bookId
     * @return array
     */
    public function getBookCompletionScoring(int $studentId, int $bookId): array;

    /**
     * Get count of books read by student
     *
     * @param int $studentId
     * @return int
     */
    public function getBooksReadCount(int $studentId): int;

    /**
     * Check if a book is completed by the student
     *
     * @param int $studentId
     * @param int $bookId
     * @return bool
     */
    public function isBookCompleted(int $studentId, int $bookId): bool;
}

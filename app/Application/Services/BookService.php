<?php

namespace App\Application\Services;

use App\Domain\Interfaces\Services\BookServiceInterface;
use App\Infrastructure\Models\BookProgress;
use App\Infrastructure\Models\Book;
use Exception;

class BookService implements BookServiceInterface
{
    public function __construct(
        private BookProgress $bookProgressModel,
        private Book $bookModel
    ) {
    }

    /**
     * Check if book is completed and get XP/coins values
     */
    public function getBookCompletionScoring(int $studentId, int $bookId): array
    {
        // Check if book progress exists and is completed
        $bookProgress = $this->bookProgressModel
            ->where('student_id', $studentId)
            ->where('book_id', $bookId)
            ->where('is_completed', true)
            ->with('book')
            ->first();

        if (!$bookProgress) {
            throw new Exception('Book must be completed first before scoring');
        }

        if (!$bookProgress->book) {
            throw new Exception('Book not found');
        }

        return [
            'success' => true,
            'xp' => $bookProgress->book->xp ?? 0,
            'coins' => $bookProgress->book->coins ?? 0,
            'book_id' => $bookId,
            'completed_at' => $bookProgress->updated_at,
        ];
    }

    /**
     * Get count of books read by student
     */
    public function getBooksReadCount(int $studentId): int
    {
        return $this->bookProgressModel
            ->where('student_id', $studentId)
            ->where('is_completed', true)
            ->count();
    }
}

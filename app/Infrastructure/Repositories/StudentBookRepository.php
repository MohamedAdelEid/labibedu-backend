<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\Repositories\StudentBookRepositoryInterface;
use App\Infrastructure\Models\StudentBook;
use Illuminate\Support\Collection;

class StudentBookRepository implements StudentBookRepositoryInterface
{
    public function __construct(
        private StudentBook $model
    ) {
    }

    public function findByStudentAndBook(int $studentId, int $bookId): ?StudentBook
    {
        return $this->model
            ->where('student_id', $studentId)
            ->where('book_id', $bookId)
            ->first();
    }

    public function createOrUpdate(int $studentId, int $bookId, array $data): StudentBook
    {
        return $this->model->updateOrCreate(
            [
                'student_id' => $studentId,
                'book_id' => $bookId,
            ],
            $data
        );
    }

    public function toggleFavorite(int $studentId, int $bookId): StudentBook
    {
        $studentBook = $this->findByStudentAndBook($studentId, $bookId);

        if (!$studentBook) {
            // Create if doesn't exist
            return $this->createOrUpdate($studentId, $bookId, ['is_favorite' => true]);
        }

        // Toggle favorite status
        $studentBook->update(['is_favorite' => !$studentBook->is_favorite]);
        return $studentBook->fresh();
    }

    public function updateLastReadPage(int $studentId, int $bookId, int $pageId): StudentBook
    {
        return $this->createOrUpdate($studentId, $bookId, ['last_read_page_id' => $pageId]);
    }

    public function getFavoriteCount(int $studentId): int
    {
        return $this->model
            ->where('student_id', $studentId)
            ->where('is_favorite', true)
            ->count();
    }

    public function getStudentBookIds(int $studentId): Collection
    {
        return $this->model
            ->where('student_id', $studentId)
            ->pluck('book_id');
    }
}


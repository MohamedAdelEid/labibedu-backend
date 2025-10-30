<?php

namespace App\Domain\Interfaces\Repositories;

use App\Infrastructure\Models\StudentBook;
use Illuminate\Support\Collection;

interface StudentBookRepositoryInterface
{
    public function findByStudentAndBook(int $studentId, int $bookId): ?StudentBook;

    public function createOrUpdate(int $studentId, int $bookId, array $data): StudentBook;

    public function toggleFavorite(int $studentId, int $bookId): StudentBook;

    public function updateLastReadPage(int $studentId, int $bookId, int $pageId): StudentBook;

    public function getFavoriteCount(int $studentId): int;

    public function getStudentBookIds(int $studentId): Collection;
}


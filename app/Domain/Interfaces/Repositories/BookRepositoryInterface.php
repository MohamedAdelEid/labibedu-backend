<?php

namespace App\Domain\Interfaces\Repositories;

use App\Infrastructure\Models\Book;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BookRepositoryInterface
{
    public function findOrFail(int $id, array $columns = ['*']): Book;

    public function getProgress(int $studentId, int $bookId);

    public function updateProgress(int $studentId, int $bookId, array $data);

    public function getByRelatedTrainingId(int $trainingId): \Illuminate\Support\Collection;

    public function getAllLibraryBooks(
        int $studentId,
        ?int $levelId,
        ?string $search,
        int $perPage
    ): LengthAwarePaginator;

    public function getStudentBooks(
        int $studentId,
        ?int $levelId,
        ?string $search,
        int $perPage
    ): LengthAwarePaginator;

    public function getFavoriteBooks(
        int $studentId,
        ?int $levelId,
        ?string $search,
        int $perPage
    ): LengthAwarePaginator;

    public function findByTitle(string $title): ?Book;
}

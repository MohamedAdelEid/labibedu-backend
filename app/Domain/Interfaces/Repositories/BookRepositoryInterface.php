<?php

namespace App\Domain\Interfaces\Repositories;

use App\Infrastructure\Models\Book;

interface BookRepositoryInterface
{
    public function findOrFail(int $id, array $columns = ['*']): Book;

    public function getProgress(int $studentId, int $bookId);

    public function updateProgress(int $studentId, int $bookId, array $data);

    public function getByRelatedTrainingId(int $trainingId): \Illuminate\Support\Collection;
}

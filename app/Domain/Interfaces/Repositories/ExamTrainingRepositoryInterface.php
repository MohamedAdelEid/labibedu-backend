<?php

namespace App\Domain\Interfaces\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ExamTrainingRepositoryInterface extends BaseRepositoryInterface
{
    public function getForStudent(int $studentId, ?string $type, int $perPage): LengthAwarePaginator;
    public function getDetailsWithQuestions(int $id, int $perPage): array;
}
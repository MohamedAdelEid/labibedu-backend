<?php

namespace App\Domain\Interfaces\Repositories;

use App\Infrastructure\Models\ExamTraining;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ExamTrainingRepositoryInterface
{
    public function findOrFail(int $id, array $columns = ['*']): ExamTraining;

    public function getForStudent(int $studentId, ?string $type, int $perPage): LengthAwarePaginator;

}

<?php

namespace App\Domain\Interfaces\Repositories;

use App\Infrastructure\Models\Question;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
interface QuestionRepositoryInterface
{
    public function findOrFail(int $id, array $columns = ['*']): Question;

    public function getByExamTrainingId(int $examTrainingId, int $perPage): LengthAwarePaginator;
}
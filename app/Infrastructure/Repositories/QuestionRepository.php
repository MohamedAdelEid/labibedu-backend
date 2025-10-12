<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\Repositories\QuestionRepositoryInterface;
use App\Infrastructure\Models\Question;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class QuestionRepository extends BaseRepository implements QuestionRepositoryInterface
{
    public function __construct(Question $model)
    {
        parent::__construct($model);
    }

    public function findOrFail(int $id, array $columns = ['*']): Question
    {
        return $this->model->findOrFail($id, $columns);
    }

    public function getByExamTrainingId(int $examTrainingId, int $perPage): LengthAwarePaginator
    {
        return $this->model
            ->where('exam_training_id', $examTrainingId)
            ->with('options')
            ->paginate($perPage);
    }

}

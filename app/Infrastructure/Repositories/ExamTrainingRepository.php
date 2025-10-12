<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\Repositories\ExamTrainingRepositoryInterface;
use App\Infrastructure\Models\ExamTraining;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ExamTrainingRepository extends BaseRepository implements ExamTrainingRepositoryInterface
{
    public function __construct(ExamTraining $model)
    {
        parent::__construct($model);
    }

    public function findOrFail(int $id, array $columns = ['*']): ExamTraining
    {
        return $this->model->findOrFail($id, $columns);
    }

    public function getForStudent(int $studentId, ?string $type, int $perPage): LengthAwarePaginator
    {
        $query = $this->model->with(['subject', 'creator']);

        if ($type) {
            $query->where('type', $type);
        }

        return $query->paginate($perPage);
    }

}

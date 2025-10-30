<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\Repositories\LevelRepositoryInterface;
use App\Infrastructure\Models\Level;
use Illuminate\Database\Eloquent\Collection;

class LevelRepository implements LevelRepositoryInterface
{
    public function __construct(
        private Level $model
    ) {
    }

    public function all(array $columns = ['*']): Collection
    {
        return $this->model->all($columns);
    }

    public function find(int $id)
    {
        return $this->model->find($id);
    }

    public function getStudentLevel(int $studentId)
    {
        return $this->model
            ->whereHas('students', function ($query) use ($studentId) {
                $query->where('student_id', $studentId);
            })
            ->first();
    }
}


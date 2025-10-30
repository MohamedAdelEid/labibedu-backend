<?php

namespace App\Domain\Interfaces\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface LevelRepositoryInterface
{
    public function all(array $columns = ['*']): Collection;

    public function find(int $id);

    public function getStudentLevel(int $studentId);
}


<?php

namespace App\Domain\Interfaces\Repositories;

use App\Infrastructure\Models\Assignment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AssignmentRepositoryInterface
{
    public function getAssignmentsForStudent(
        int $studentId,
        ?string $type,
        ?string $status,
        int $perPage
    ): LengthAwarePaginator;

    public function findAssignmentForStudent(int $assignmentId, int $studentId);

    public function findOrFail(int $id, array $columns = ['*']): Assignment;
}

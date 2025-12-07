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

    public function getAssignmentsStats(int $studentId): array;

    public function activateAssignment(int $assignmentId, int $studentId): Assignment;

    public function getUpcomingExam(int $studentId, $now, $futureTime): ?Assignment;

    public function getNotStartedCount(int $studentId): int;

    public function getNotStartedExamTrainingCount(int $studentId): int;

    public function completeAssignmentForStudent(int $assignmentId, int $studentId): void;
}

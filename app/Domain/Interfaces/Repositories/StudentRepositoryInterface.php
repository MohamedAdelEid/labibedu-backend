<?php

namespace App\Domain\Interfaces\Repositories;

use App\Infrastructure\Models\Student;
use Illuminate\Support\Collection;

interface StudentRepositoryInterface
{
    /**
     * Find student by ID
     */
    public function findById(int $id): ?Student;

    /**
     * Find student by user ID
     */
    public function findByUserId(int $userId): ?Student;

    /**
     * Update student XP and coins
     */
    public function updateScoring(int $studentId, int $xpGained, int $coinsGained): bool;

    /**
     * Get student's total XP and coins from all sources
     */
    public function getTotalScoring(int $studentId): array;

    /**
     * Get students by school
     */
    public function getBySchool(int $schoolId): Collection;

    /**
     * Get students by classroom
     */
    public function getByClassroom(int $classroomId): Collection;

    /**
     * Get students by group
     */
    public function getByGroup(int $groupId): Collection;

    /**
     * Update student first setup data
     */
    public function updateFirstSetup(int $studentId, array $data): Student;

    /**
     * Update student settings
     */
    public function updateSettings(int $studentId, array $data): Student;
}

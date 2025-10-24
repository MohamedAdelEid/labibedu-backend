<?php

namespace App\Domain\Interfaces\Repositories;

use App\Infrastructure\Models\Avatar;
use App\Infrastructure\Models\Student;
use Illuminate\Support\Collection;

interface AvatarRepositoryInterface
{
    /**
     * Find avatar by ID
     */
    public function findById(int $id): ?Avatar;

    /**
     * Get all available avatars
     */
    public function getAll(): Collection;

    /**
     * Get avatars owned by a student
     */
    public function getOwnedByStudent(int $studentId): Collection;

    /**
     * Check if student owns an avatar
     */
    public function studentOwnsAvatar(int $studentId, int $avatarId): bool;

    /**
     * Purchase avatar for student
     */
    public function purchaseAvatar(Student $student, Avatar $avatar): Avatar;

    /**
     * Set active avatar for student
     */
    public function setActiveAvatar(Student $student, Avatar $avatar): Avatar;

    /**
     * Get avatars by category
     */
    public function getByCategory(int $categoryId): Collection;

    /**
     * Get avatars grouped by category
     */
    public function getGroupedByCategory(): Collection;

    /**
     * Create a new avatar with uploaded file
     */
    public function createAvatar(string $filePath, int $coins = 0): Avatar;
}

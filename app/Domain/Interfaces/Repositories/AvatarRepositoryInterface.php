<?php

namespace App\Domain\Interfaces\Repositories;

use App\Infrastructure\Models\Avatar;
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
    public function purchaseAvatar(int $studentId, int $avatarId): bool;

    /**
     * Set active avatar for student
     */
    public function setActiveAvatar(int $studentId, int $avatarId): bool;
}

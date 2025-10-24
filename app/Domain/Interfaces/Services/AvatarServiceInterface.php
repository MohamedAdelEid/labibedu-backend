<?php

namespace App\Domain\Interfaces\Services;

use App\Application\DTOs\Avatar\CreateAvatarDTO;
use App\Infrastructure\Models\Avatar;
use Illuminate\Support\Collection;

interface AvatarServiceInterface
{
    /**
     * Get all available avatars
     */
    public function getAllAvatars(): Collection;

    /**
     * Get all avatars with student-specific information
     */
    public function getAllAvatarsForStudent(int $studentId): Collection;

    /**
     * Get avatars owned by a student
     */
    public function getOwnedAvatars(int $studentId): Collection;

    /**
     * Purchase an avatar for a student
     */
    public function purchaseAvatar(int $studentId, int $avatarId): Avatar;

    /**
     * Set active avatar for a student
     */
    public function setActiveAvatar(int $studentId, int $avatarId): Avatar;

    /**
     * Get student's active avatar info
     */
    public function getActiveAvatarInfo(int $studentId): ?Avatar;

    /**
     * Get avatars by category
     */
    public function getAvatarsByCategory(int $categoryId): Collection;

    /**
     * Get avatars grouped by category
     */
    public function getAvatarsGroupedByCategory(?int $studentId = null): Collection;

    /**
     * Upload a new avatar
     */
    public function createAvatar(CreateAvatarDTO $dto): Avatar;
}

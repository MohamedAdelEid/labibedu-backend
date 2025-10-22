<?php

namespace App\Domain\Interfaces\Services;

interface AvatarServiceInterface
{
    /**
     * Get all available avatars
     */
    public function getAllAvatars(): array;

    /**
     * Get avatars owned by a student
     */
    public function getOwnedAvatars(int $studentId): array;

    /**
     * Purchase an avatar for a student
     */
    public function purchaseAvatar(int $studentId, int $avatarId): array;

    /**
     * Set active avatar for a student
     */
    public function setActiveAvatar(int $studentId, int $avatarId): array;

    /**
     * Get student's active avatar info
     */
    public function getActiveAvatarInfo(int $studentId): ?array;
}

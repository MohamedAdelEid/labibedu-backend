<?php

namespace App\Application\Services;

use App\Domain\Interfaces\Repositories\AvatarRepositoryInterface;
use App\Domain\Interfaces\Services\AvatarServiceInterface;

class AvatarService implements AvatarServiceInterface
{
    public function __construct(
        private AvatarRepositoryInterface $avatarRepository
    ) {
    }

    /**
     * Get all available avatars
     */
    public function getAllAvatars(): array
    {
        $avatars = $this->avatarRepository->getAll();

        return $avatars->map(function ($avatar) {
            return [
                'id' => $avatar->id,
                'url' => $avatar->url,
                'coins' => $avatar->coins,
            ];
        })->toArray();
    }

    /**
     * Get avatars owned by a student
     */
    public function getOwnedAvatars(int $studentId): array
    {
        $avatars = $this->avatarRepository->getOwnedByStudent($studentId);

        return $avatars->map(function ($avatar) {
            return [
                'id' => $avatar->id,
                'url' => $avatar->url,
                'coins' => $avatar->coins,
                'purchased_at' => $avatar->pivot->purchased_at,
            ];
        })->toArray();
    }

    /**
     * Purchase an avatar for a student
     */
    public function purchaseAvatar(int $studentId, int $avatarId): array
    {
        $success = $this->avatarRepository->purchaseAvatar($studentId, $avatarId);

        if ($success) {
            $avatar = $this->avatarRepository->findById($avatarId);
            return [
                'success' => true,
                'avatar' => [
                    'id' => $avatar->id,
                    'url' => $avatar->url,
                    'coins' => $avatar->coins,
                ],
            ];
        }

        return [
            'success' => false,
            'message' => 'Unable to purchase avatar. Check if you have enough coins or if you already own this avatar.',
        ];
    }

    /**
     * Set active avatar for a student
     */
    public function setActiveAvatar(int $studentId, int $avatarId): array
    {
        $success = $this->avatarRepository->setActiveAvatar($studentId, $avatarId);

        if ($success) {
            $avatar = $this->avatarRepository->findById($avatarId);
            return [
                'success' => true,
                'avatar' => [
                    'id' => $avatar->id,
                    'url' => $avatar->url,
                    'coins' => $avatar->coins,
                ],
            ];
        }

        return [
            'success' => false,
            'message' => 'Unable to set active avatar. Make sure you own this avatar.',
        ];
    }

    /**
     * Get student's active avatar info
     */
    public function getActiveAvatarInfo(int $studentId): ?array
    {
        $student = \App\Infrastructure\Models\Student::find($studentId);

        if (!$student || !$student->activeAvatar) {
            return null;
        }

        $avatar = $student->activeAvatar;
        return [
            'id' => $avatar->id,
            'url' => $avatar->url,
        ];
    }
}

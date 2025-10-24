<?php

namespace App\Application\Services;

use App\Application\DTOs\Avatar\CreateAvatarDTO;
use App\Application\Exceptions\Avatar\AvatarAlreadyOwnedException;
use App\Application\Exceptions\Avatar\AvatarNotFoundException;
use App\Application\Exceptions\Avatar\AvatarNotOwnedException;
use App\Application\Exceptions\Avatar\AvatarPurchaseFailedException;
use App\Application\Exceptions\Student\InsufficientCoinsException;
use App\Domain\Interfaces\Repositories\AvatarCategoryRepositoryInterface;
use App\Domain\Interfaces\Repositories\AvatarRepositoryInterface;
use App\Domain\Interfaces\Repositories\StudentRepositoryInterface;
use App\Domain\Interfaces\Services\AvatarServiceInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use App\Infrastructure\Models\Avatar;

class AvatarService implements AvatarServiceInterface
{
    public function __construct(
        private AvatarRepositoryInterface $avatarRepository,
        private StudentRepositoryInterface $studentRepository,
        private AvatarCategoryRepositoryInterface $avatarCategoryRepository
    ) {
    }

    /**
     * Get all available avatars
     */
    public function getAllAvatars(): Collection
    {
        return $this->avatarRepository->getAll()->load('category');
    }

    /**
     * Get avatars by category
     */
    public function getAvatarsByCategory(int $categoryId): Collection
    {
        return $this->avatarRepository->getByCategory($categoryId)->load('category');
    }

    /**
     * Get avatars grouped by category
     */
    public function getAvatarsGroupedByCategory(?int $studentId = null): Collection
    {
        $groupedAvatars = $this->avatarRepository->getGroupedByCategory();

        // If student ID is provided, add student-specific information
        if ($studentId) {
            $ownedAvatars = $this->avatarRepository->getOwnedByStudent($studentId);
            $student = $this->studentRepository->findById($studentId);

            $ownedAvatarIds = $ownedAvatars->pluck('id')->toArray();
            $activeAvatarId = $student?->active_avatar_id;

            // Add student-specific data to each avatar in each category
            return $groupedAvatars->map(function ($avatars) use ($ownedAvatarIds, $activeAvatarId) {
                return $avatars->map(function ($avatar) use ($ownedAvatarIds, $activeAvatarId) {
                    $avatar->is_owned = in_array($avatar->id, $ownedAvatarIds);
                    $avatar->is_active = $avatar->id === $activeAvatarId;
                    return $avatar;
                });
            });
        }

        return $groupedAvatars;
    }

    /**
     * Get all avatars with student-specific information
     */
    public function getAllAvatarsForStudent(int $studentId): Collection
    {
        $allAvatars = $this->avatarRepository->getAll()->load('category');
        $ownedAvatars = $this->avatarRepository->getOwnedByStudent($studentId);
        $student = $this->studentRepository->findById($studentId);

        $ownedAvatarIds = $ownedAvatars->pluck('id')->toArray();
        $activeAvatarId = $student?->active_avatar_id;

        return $allAvatars->map(function ($avatar) use ($ownedAvatarIds, $activeAvatarId) {
            $avatar->is_owned = in_array($avatar->id, $ownedAvatarIds);
            $avatar->is_active = $avatar->id === $activeAvatarId;
            return $avatar;
        });
    }

    /**
     * Get avatars owned by a student
     */
    public function getOwnedAvatars(int $studentId): Collection
    {
        return $this->avatarRepository->getOwnedByStudent($studentId);
    }

    /**
     * Purchase an avatar for a student
     */
    public function purchaseAvatar(int $studentId, int $avatarId): Avatar
    {
        $student = $this->studentRepository->findById($studentId);
        $avatar = $this->avatarRepository->findById($avatarId);


        if (!$avatar) {
            throw new AvatarNotFoundException();
        }

        // Check if student can afford the avatar
        if (!$student->canAfford($avatar->coins)) {
            throw new InsufficientCoinsException();
        }

        // Check if student already owns this avatar
        if ($this->avatarRepository->studentOwnsAvatar($studentId, $avatarId)) {
            throw new AvatarAlreadyOwnedException();
        }

        // Spend coins and attach avatar
        if (!$student->spendCoins($avatar->coins)) {
            throw new AvatarPurchaseFailedException();
        }

        $avatar = $this->avatarRepository->purchaseAvatar($student, $avatar);
        return $avatar;
    }

    /**
     * Set active avatar for a student
     */
    public function setActiveAvatar(int $studentId, int $avatarId): Avatar
    {
        $student = $this->studentRepository->findById($studentId);
        $avatar = $this->avatarRepository->findById($avatarId);


        if (!$avatar) {
            throw new AvatarNotFoundException();
        }

        // Check if student owns this avatar
        if (!$this->avatarRepository->studentOwnsAvatar($studentId, $avatarId)) {
            throw new AvatarNotOwnedException();
        }

        $avatar = $this->avatarRepository->setActiveAvatar($student, $avatar);
        return $avatar;
    }

    /**
     * Get student's active avatar info
     */
    public function getActiveAvatarInfo(int $studentId): ?Avatar
    {
        $student = $this->studentRepository->findById($studentId);
        return $student?->activeAvatar;
    }

    /**
     * Upload a new avatar
     */
    public function createAvatar(CreateAvatarDTO $dto): Avatar
    {
        $category = $this->avatarCategoryRepository->findById($dto->categoryId);

        // Generate unique filename
        $extension = $dto->avatar->getClientOriginalExtension();
        $filename = 'avatar_' . time() . '_' . Str::random(10) . '.' . $extension;

        // Store file in public/assets/images/avatars folder
        $directory = 'assets/images/avatars/' . $category->name_en;
        $storedPath = $dto->avatar->storeAs($directory, $filename, 'public');

        // Create avatar record
        return $this->avatarRepository->createAvatar($storedPath, $dto->coins);
    }
}

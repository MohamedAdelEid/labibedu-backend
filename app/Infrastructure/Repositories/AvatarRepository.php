<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\Repositories\AvatarRepositoryInterface;
use App\Infrastructure\Models\Avatar;
use App\Infrastructure\Models\Student;
use Illuminate\Support\Collection;

class AvatarRepository extends BaseRepository implements AvatarRepositoryInterface
{
    public function __construct(Avatar $model)
    {
        parent::__construct($model);
    }

    /**
     * Find avatar by ID
     */
    public function findById(int $id): ?Avatar
    {
        return $this->model->find($id);
    }

    /**
     * Get all available avatars
     */
    public function getAll(): Collection
    {
        return $this->model->orderBy('coins')->get();
    }

    /**
     * Get avatars owned by a student
     */
    public function getOwnedByStudent(int $studentId): Collection
    {
        return $this->model->whereHas('students', function ($query) use ($studentId) {
            $query->where('student_id', $studentId);
        })->get();
    }

    /**
     * Check if student owns an avatar
     */
    public function studentOwnsAvatar(int $studentId, int $avatarId): bool
    {
        return $this->model->whereHas('students', function ($query) use ($studentId) {
            $query->where('student_id', $studentId);
        })->where('id', $avatarId)->exists();
    }

    /**
     * Purchase avatar for student
     */
    public function purchaseAvatar(int $studentId, int $avatarId): bool
    {
        $student = Student::find($studentId);
        $avatar = $this->findById($avatarId);

        if (!$student || !$avatar) {
            return false;
        }

        // Check if student can afford the avatar
        if (!$student->canAfford($avatar->coins)) {
            return false;
        }

        // Check if student already owns this avatar
        if ($this->studentOwnsAvatar($studentId, $avatarId)) {
            return false;
        }

        // Spend coins and attach avatar
        if ($student->spendCoins($avatar->coins)) {
            $student->avatars()->attach($avatarId, ['purchased_at' => now()]);
            return true;
        }

        return false;
    }

    /**
     * Set active avatar for student
     */
    public function setActiveAvatar(int $studentId, int $avatarId): bool
    {
        $student = Student::find($studentId);

        if (!$student) {
            return false;
        }

        // Check if student owns this avatar
        if (!$this->studentOwnsAvatar($studentId, $avatarId)) {
            return false;
        }

        $student->update(['active_avatar_id' => $avatarId]);
        return true;
    }
}

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
    public function purchaseAvatar(Student $student, Avatar $avatar): Avatar
    {
        $student->avatars()->attach($avatar->id, ['purchased_at' => now()]);
        return $avatar;
    }

    /**
     * Set active avatar for student
     */
    public function setActiveAvatar(Student $student, Avatar $avatar): Avatar
    {
        $student->active_avatar_id = $avatar->id;
        $student->save();
        return $avatar;
    }

    /**
     * Get avatars by category
     */
    public function getByCategory(int $categoryId): Collection
    {
        return $this->model->where('category_id', $categoryId)->get();
    }

    /**
     * Get avatars grouped by category
     */
    public function getGroupedByCategory(): Collection
    {
        return $this->model->with('category')
            ->get()
            ->groupBy('category.name');
    }

    /**
     * Create a new avatar with uploaded file
     */
    public function createAvatar(string $filePath, int $coins = 0): Avatar
    {
        return $this->model->create([
            'url' => $filePath,
            'coins' => $coins,
        ]);
    }
}

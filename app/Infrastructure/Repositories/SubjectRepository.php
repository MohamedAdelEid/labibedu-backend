<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\Repositories\SubjectRepositoryInterface;
use App\Infrastructure\Models\Subject;
use App\Infrastructure\Models\Student;
use App\Infrastructure\Models\User;
use Illuminate\Support\Collection;

class SubjectRepository extends BaseRepository implements SubjectRepositoryInterface
{
    public function __construct(Subject $model)
    {
        parent::__construct($model);
    }

    public function getSubjectsByGradeId(int $gradeId): Collection
    {
        return $this->model->query()
            ->whereHas('classroom', function ($query) use ($gradeId) {
                $query->where('grade_id', $gradeId);
            })
            ->get();
    }

    public function getSubjectsByUserId(int $userId): Collection
    {
        // Get user's student
        $user = User::with('student.classroom')->findOrFail($userId);

        if (!$user->student || !$user->student->classroom) {
            return collect();
        }

        $gradeId = $user->student->classroom->grade_id;

        if (!$gradeId) {
            return collect();
        }

        // Get subjects for this grade
        return $this->getSubjectsByGradeId($gradeId);
    }
}


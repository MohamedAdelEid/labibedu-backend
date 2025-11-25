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
        // Get student by id
        $student = Student::with('classroom')->find($userId);

        if (!$student || !$student->classroom) {
            return collect();
        }

        $classroomId = $student->classroom_id;

        if (!$classroomId) {
            return collect();
        }

        // Get subjects for this classroom
        return $this->model->query()
            ->where('classroom_id', $classroomId)
            ->get();
    }
}


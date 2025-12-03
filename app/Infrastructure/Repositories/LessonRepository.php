<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\Repositories\LessonRepositoryInterface;
use App\Infrastructure\Models\Lesson;
use App\Infrastructure\Models\Student;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LessonRepository extends BaseRepository implements LessonRepositoryInterface
{
    public function __construct(Lesson $model)
    {
        parent::__construct($model);
    }

    public function getLessonsForStudent(
        int $studentId,
        int $subjectId,
        ?string $search,
        int $perPage
    ): LengthAwarePaginator {

        $student = Student::with('classroom.grade')->findOrFail($studentId);

        $gradeId = $student->classroom?->grade_id;

        if (!$gradeId) {
            return $this->model->query()->whereRaw('1 = 0')->paginate($perPage);
        }

        $query = $this->model->query()
            ->with(['books', 'videos', 'subject', 'grade', 'category'])
            ->withCount(['books', 'videos'])
            ->where('subject_id', $subjectId)
            ->where('grade_id', $gradeId);

        // Apply search filter if provided
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title_ar', 'LIKE', "%{$search}%")
                    ->orWhere('title_en', 'LIKE', "%{$search}%");
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
}


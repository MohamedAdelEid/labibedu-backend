<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\Repositories\AssignmentRepositoryInterface;
use App\Infrastructure\Models\Assignment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AssignmentRepository extends BaseRepository implements AssignmentRepositoryInterface
{
    public function __construct(Assignment $model)
    {
        parent::__construct($model);
    }

    public function findOrFail(int $id, array $columns = ['*']): Assignment
    {
        return $this->model->findOrFail($id, $columns);
    }

    public function getAssignmentsForStudent(int $studentId, ?string $type, ?string $status, int $perPage): LengthAwarePaginator
    {
        $query = $this->model->whereHas('students', function ($q) use ($studentId, $status) {
            $q->where('student_id', $studentId);

            if ($status) {
                $q->where('assignment_student.status', $status);
            }
        })
            ->with([
                'students' => function ($q) use ($studentId) {
                    $q->where('student_id', $studentId);
                },
                'examTraining.questions',
                'video.relatedTraining.questions',
                'book.relatedTraining.questions',
            ]);

        if ($type === 'current') {
            $query->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            });
        } elseif ($type === 'exams') {
            $query->whereIn('type', ['exam', 'training']);
        } elseif ($type === 'reading') {
            $query->where('type', 'book');
        } elseif ($type === 'watching') {
            $query->where('type', 'video');
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function findAssignmentForStudent(int $assignmentId, int $studentId)
    {
        return $this->model->whereHas('students', function ($q) use ($studentId) {
            $q->where('student_id', $studentId);
        })
            ->with([
                'students' => function ($q) use ($studentId) {
                    $q->where('student_id', $studentId);
                },
                'examTraining.questions.options',
                'video.relatedTraining.questions.options',
                'book.relatedTraining.questions.options',
            ])
            ->findOrFail($assignmentId);
    }
}

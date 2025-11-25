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
                'assignable', // Load polymorphic relationship
            ]);

        // Apply type filter
        if ($type === 'current') {
            // Current: all assignments that haven't ended yet
            $query->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            });
        } elseif ($type === 'exams') {
            // Exams: all examTraining types
            $query->where('assignable_type', 'examTraining');
        } elseif ($type === 'reading') {
            // Reading: all book types
            $query->where('assignable_type', 'book');
        } elseif ($type === 'watching') {
            // Watching: all video types
            $query->where('assignable_type', 'video');
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
                'assignable.questions.options',
                'assignable.relatedTraining.questions.options',
            ])
            ->findOrFail($assignmentId);
    }
}

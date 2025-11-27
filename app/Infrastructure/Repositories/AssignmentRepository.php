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
        // If type is 'current', force status to 'not_started'
        // If type is other tabs (exams, training, reading, watching), exclude 'not_started'
        if ($type === 'current' && !$status) {
            $status = 'not_started';
        }

        $query = $this->model->whereHas('students', function ($q) use ($studentId, $status, $type) {
            $q->where('student_id', $studentId);

            if ($status) {
                $q->where('assignment_student.status', $status);
            } elseif (in_array($type, ['exams', 'training', 'reading', 'watching'])) {
                // For other tabs, exclude 'not_started' status
                $q->whereIn('assignment_student.status', ['in_progress', 'completed', 'not_submitted']);
            }
        })
            ->with([
                'students' => function ($q) use ($studentId) {
                    $q->where('student_id', $studentId);
                },
                'assignable', // Load base polymorphic relationship
            ]);

        // Apply type filter and conditional eager loading
        if ($type === 'current') {
            // Current: all assignments that haven't ended yet
            $query->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            });
        } elseif ($type === 'exams') {
            // Exams: only examTraining with type 'exam'
            $query->where('assignable_type', 'examTraining')
                ->whereExists(function ($q) {
                    $q->selectRaw(1)
                        ->from('exams_trainings')
                        ->whereColumn('assignments.assignable_id', 'exams_trainings.id')
                        ->where('exams_trainings.type', 'exam');
                })
                ->with('assignable.questions'); // Load questions for exams
        } elseif ($type === 'training') {
            // Training: only examTraining with type 'training'
            $query->where('assignable_type', 'examTraining')
                ->whereExists(function ($q) {
                    $q->selectRaw(1)
                        ->from('exams_trainings')
                        ->whereColumn('assignments.assignable_id', 'exams_trainings.id')
                        ->where('exams_trainings.type', 'training');
                })
                ->with('assignable.questions'); // Load questions for training
        } elseif ($type === 'reading') {
            // Reading: all book types
            $query->where('assignable_type', 'book')
                ->with([
                    'assignable.pages', // Load pages for books
                    'assignable.relatedTraining.questions', // Load related training questions
                ]);
        } elseif ($type === 'watching') {
            // Watching: all video types
            $query->where('assignable_type', 'video')
                ->with('assignable.relatedTraining.questions'); // Load related training questions
        }

        $results = $query->orderBy('created_at', 'desc')->paginate($perPage);

        // For 'current' tab (mixed types), load relationships after getting results
        if ($type === 'current' || !$type) {
            foreach ($results as $assignment) {
                if ($assignment->assignable_type === 'examTraining') {
                    $assignment->load('assignable.questions');
                } elseif ($assignment->assignable_type === 'book') {
                    $assignment->load('assignable.pages', 'assignable.relatedTraining.questions');
                } elseif ($assignment->assignable_type === 'video') {
                    $assignment->load('assignable.relatedTraining.questions');
                }
            }
        }

        return $results;
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

    public function getAssignmentsStats(int $studentId): array
    {
        $baseQuery = $this->model->whereHas('students', function ($q) use ($studentId) {
            $q->where('student_id', $studentId);
        });

        $total = $baseQuery->count();

        $exams = (clone $baseQuery)->where('assignable_type', 'examTraining')
            ->whereExists(function ($q) {
                $q->selectRaw(1)
                    ->from('exams_trainings')
                    ->whereColumn('assignments.assignable_id', 'exams_trainings.id')
                    ->where('exams_trainings.type', 'exam');
            })->count();

        $training = (clone $baseQuery)->where('assignable_type', 'examTraining')
            ->whereExists(function ($q) {
                $q->selectRaw(1)
                    ->from('exams_trainings')
                    ->whereColumn('assignments.assignable_id', 'exams_trainings.id')
                    ->where('exams_trainings.type', 'training');
            })->count();

        $reading = (clone $baseQuery)->where('assignable_type', 'book')->count();
        $watching = (clone $baseQuery)->where('assignable_type', 'video')->count();

        // Get current count (in_progress + not_started)
        $current = $this->model->whereHas('students', function ($q) use ($studentId) {
            $q->where('student_id', $studentId)
                ->whereIn('assignment_student.status', ['in_progress', 'not_started']);
        })->count();

        return [
            'total' => $total,
            'exams' => $exams,
            'training' => $training,
            'reading' => $reading,
            'watching' => $watching,
            'current' => $current,
        ];
    }
}

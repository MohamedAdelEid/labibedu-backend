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
        $query = $this->model->whereHas('students', function ($q) use ($studentId, $status, $type) {
            $q->where('student_id', $studentId);

            if ($status) {
                $q->where('assignment_student.status', $status);
            } elseif ($type === 'current') {
                // For 'current' tab, include both 'not_started' and 'in_progress'
                $q->whereIn('assignment_student.status', ['not_started', 'in_progress']);
            } elseif (in_array($type, ['exams', 'training', 'reading', 'watching'])) {
                // For other tabs, only include 'completed' and 'not_submitted'
                $q->whereIn('assignment_student.status', ['completed', 'not_submitted']);
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
        $assignment = $this->model->whereHas('students', function ($q) use ($studentId) {
            $q->where('student_id', $studentId);
        })
            ->with([
                'students' => function ($q) use ($studentId) {
                    $q->where('student_id', $studentId);
                },
                'assignable',
            ])
            ->findOrFail($assignmentId);

        // Conditionally load relationships based on assignable_type
        if ($assignment->assignable_type === 'examTraining') {
            $assignment->load('assignable.questions.options');
        } elseif ($assignment->assignable_type === 'book') {
            $assignment->load('assignable.relatedTraining.questions.options', 'assignable.pages');
        } elseif ($assignment->assignable_type === 'video') {
            $assignment->load('assignable.relatedTraining.questions.options');
        }

        return $assignment;
    }

    public function getAssignmentsStats(int $studentId): array
    {
        $baseQuery = $this->model->whereHas('students', function ($q) use ($studentId) {
            $q->where('student_id', $studentId);
        });

        $total = $baseQuery->count();

        // For other tabs (exams, training, reading, watching), count only completed and not_submitted assignments
        $exams = (clone $baseQuery)->where('assignable_type', 'examTraining')
            ->whereExists(function ($q) {
                $q->selectRaw(1)
                    ->from('exams_trainings')
                    ->whereColumn('assignments.assignable_id', 'exams_trainings.id')
                    ->where('exams_trainings.type', 'exam');
            })
            ->whereHas('students', function ($q) use ($studentId) {
                $q->where('student_id', $studentId)
                    ->whereIn('assignment_student.status', ['completed', 'not_submitted']);
            })->count();

        $training = (clone $baseQuery)->where('assignable_type', 'examTraining')
            ->whereExists(function ($q) {
                $q->selectRaw(1)
                    ->from('exams_trainings')
                    ->whereColumn('assignments.assignable_id', 'exams_trainings.id')
                    ->where('exams_trainings.type', 'training');
            })
            ->whereHas('students', function ($q) use ($studentId) {
                $q->where('student_id', $studentId)
                    ->where('assignment_student.status', 'completed');
            })->count();

        $reading = (clone $baseQuery)->where('assignable_type', 'book')
            ->whereHas('students', function ($q) use ($studentId) {
                $q->where('student_id', $studentId)
                    ->where('assignment_student.status', 'completed');
            })->count();

        $watching = (clone $baseQuery)->where('assignable_type', 'video')
            ->whereHas('students', function ($q) use ($studentId) {
                $q->where('student_id', $studentId)
                    ->where('assignment_student.status', 'completed');
            })->count();

        // Get current count (in_progress + not_started) for assignments that haven't ended yet
        $current = $this->model->whereHas('students', function ($q) use ($studentId) {
            $q->where('student_id', $studentId)
                ->whereIn('assignment_student.status', ['in_progress', 'not_started']);
        })
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            })
            ->count();

        return [
            'total' => $total,
            'exams' => $exams,
            'training' => $training,
            'reading' => $reading,
            'watching' => $watching,
            'current' => $current,
        ];
    }

    /**
     * Activate assignment (change status from not_started to in_progress)
     */
    public function activateAssignment(int $assignmentId, int $studentId): Assignment
    {
        // Find assignment and verify it belongs to the student with not_started or in_progress status
        $assignment = $this->model->whereHas('students', function ($q) use ($studentId) {
            $q->where('student_id', $studentId)
                ->whereIn('assignment_student.status', ['not_started', 'in_progress']);
        })->findOrFail($assignmentId);

        // Get current status
        $currentStatus = $assignment->students->first()?->pivot->status ?? 'not_started';

        // Only update if status is not_started, if already in_progress, just reload
        if ($currentStatus === 'not_started') {
            // Update pivot status from not_started to in_progress
            $assignment->students()->updateExistingPivot($studentId, [
                'status' => 'in_progress',
                'updated_at' => now(),
            ]);
        }

        // Reload the assignment with updated relationships
        $assignment->load([
            'students' => function ($q) use ($studentId) {
                $q->where('student_id', $studentId);
            },
            'assignable',
        ]);

        return $assignment;
    }

    public function getUpcomingExam(int $studentId, $now, $futureTime): ?Assignment
    {
        return $this->model->whereHas('students', function ($q) use ($studentId) {
            $q->where('student_id', $studentId);
        })
            ->where('assignable_type', 'examTraining')
            ->whereExists(function ($q) {
                $q->selectRaw(1)
                    ->from('exams_trainings')
                    ->whereColumn('assignments.assignable_id', 'exams_trainings.id')
                    ->where('exams_trainings.type', 'exam');
            })
            ->whereNotNull('end_date')
            ->whereBetween('end_date', [$now, $futureTime])
            ->with('assignable')
            ->orderBy('end_date', 'asc')
            ->first();
    }

    public function getNotStartedCount(int $studentId): int
    {
        return $this->model->whereHas('students', function ($q) use ($studentId) {
            $q->where('student_id', $studentId)
                ->where('assignment_student.status', 'not_started');
        })->count();
    }

    public function getNotStartedExamTrainingCount(int $studentId): int
    {
        return $this->model->whereHas('students', function ($q) use ($studentId) {
            $q->where('student_id', $studentId)
                ->where('assignment_student.status', 'not_started');
        })
            ->where('assignable_type', 'examTraining')
            ->whereExists(function ($q) {
                $q->selectRaw(1)
                    ->from('exams_trainings')
                    ->whereColumn('assignments.assignable_id', 'exams_trainings.id')
                    ->where('exams_trainings.type', 'training');
            })
            ->count();
    }

    public function getNotStartedAssignmentsExceptExamCount(int $studentId): int
    {
        return $this->model->whereHas('students', function ($q) use ($studentId) {
            $q->where('student_id', $studentId)
                ->where('assignment_student.status', 'not_started');
        })
            ->where(function ($query) {
                // Include all assignments except examTraining with type 'exam'
                $query->where('assignable_type', '!=', 'examTraining')
                    ->orWhere(function ($q) {
                    // Include examTraining but exclude those with type 'exam'
                    $q->where('assignable_type', 'examTraining')
                        ->whereExists(function ($subQ) {
                        $subQ->selectRaw(1)
                            ->from('exams_trainings')
                            ->whereColumn('assignments.assignable_id', 'exams_trainings.id')
                            ->where('exams_trainings.type', '!=', 'exam');
                    });
                });
            })
            ->count();
    }

    /**
     * Complete assignment for student (change status to completed)
     */
    public function completeAssignmentForStudent(int $assignmentId, int $studentId): void
    {
        $assignment = $this->model->whereHas('students', function ($q) use ($studentId) {
            $q->where('student_id', $studentId);
        })->findOrFail($assignmentId);

        // Update pivot status to completed
        $assignment->students()->updateExistingPivot($studentId, [
            'status' => 'completed',
            'updated_at' => now(),
        ]);
    }
}

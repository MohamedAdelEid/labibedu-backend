<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Enums\AttemptStatus;
use App\Domain\Interfaces\Repositories\ExamAttemptRepositoryInterface;
use App\Infrastructure\Models\ExamAttempt;

class ExamAttemptRepository extends BaseRepository implements ExamAttemptRepositoryInterface
{
    public function __construct(ExamAttempt $model)
    {
        parent::__construct($model);
    }

    public function findLatestAttempt(int $studentId, int $examTrainingId): ?ExamAttempt
    {
        return $this->model
            ->where('student_id', $studentId)
            ->where('exam_training_id', $examTrainingId)
            ->latest()
            ->first();
    }

    public function findActiveAttempt(int $studentId, int $examTrainingId): ?ExamAttempt
    {
        return $this->model
            ->where('student_id', $studentId)
            ->where('exam_training_id', $examTrainingId)
            ->where('status', AttemptStatus::IN_PROGRESS)
            ->first();
    }

    public function createAttempt(int $studentId, int $examTrainingId, int $duration): ExamAttempt
    {
        return $this->model->create([
            'student_id' => $studentId,
            'exam_training_id' => $examTrainingId,
            'start_time' => now(),
            'remaining_seconds' => $duration > 0 ? $duration * 60 : 0, // 0 means unlimited time for training
            'status' => AttemptStatus::IN_PROGRESS,
        ]);
    }

    public function autoFinishExpiredAttempts(): void
    {
        $this->model
            ->where('status', AttemptStatus::IN_PROGRESS)
            ->where('remaining_seconds', '>', 0) // Only process exams (training has remaining_seconds = 0)
            ->where('remaining_seconds', '<=', 0)
            ->update([
                'status' => AttemptStatus::FINISHED,
                'end_time' => now(),
            ]);
    }

    public function updateRemainingTime(int $attemptId, int $remainingSeconds): void
    {
        $this->model->where('id', $attemptId)->update([
            'remaining_seconds' => $remainingSeconds,
        ]);
    }
}

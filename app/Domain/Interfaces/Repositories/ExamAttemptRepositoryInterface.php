<?php

namespace App\Domain\Interfaces\Repositories;

use App\Infrastructure\Models\ExamAttempt;

interface ExamAttemptRepositoryInterface
{
    public function findLatestAttempt(int $studentId, int $examTrainingId): ?ExamAttempt;

    public function findActiveAttempt(int $studentId, int $examTrainingId): ?ExamAttempt;

    public function createAttempt(int $studentId, int $examTrainingId, int $duration): ExamAttempt;

    public function autoFinishExpiredAttempts(): void;

    public function updateRemainingTime(int $attemptId, int $remainingSeconds): void;
}

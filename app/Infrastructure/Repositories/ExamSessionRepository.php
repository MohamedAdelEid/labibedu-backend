<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\Repositories\ExamSessionRepositoryInterface;
use App\Infrastructure\Models\ExamSession;

class ExamSessionRepository extends BaseRepository implements ExamSessionRepositoryInterface
{
    public function __construct(ExamSession $model)
    {
        parent::__construct($model);
    }

    public function startSession(int $studentId, int $examTrainingId): int
    {
        $session = $this->create([
            'student_id' => $studentId,
            'exam_training_id' => $examTrainingId,
            'started_at' => now(),
        ]);

        return $session->id;
    }

    public function completeSession(int $sessionId, array $stats): bool
    {
        $session = $this->findOrFail($sessionId);
        
        $session->update([
            'completed_at' => now(),
            'total_xp' => $stats['total_xp'],
            'total_coins' => $stats['total_coins'],
            'correct_answers' => $stats['correct_answers'],
            'total_questions' => $stats['total_questions'],
        ]);

        return true;
    }

    public function getActiveSession(int $studentId, int $examTrainingId): ?int
    {
        $session = $this->model
            ->where('student_id', $studentId)
            ->where('exam_training_id', $examTrainingId)
            ->whereNull('completed_at')
            ->first();

        return $session?->id;
    }
}
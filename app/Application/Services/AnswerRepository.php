<?php

namespace App\Infrastructure\Repositories;

use App\Infrastructure\Models\Answer;
use Illuminate\Support\Collection;

class AnswerRepository extends BaseRepository
{
    public function __construct(Answer $model)
    {
        parent::__construct($model);
    }

    public function findByStudentAndQuestion(int $studentId, int $questionId): ?Answer
    {
        return $this->model
            ->where('student_id', $studentId)
            ->where('question_id', $questionId)
            ->first();
    }

    public function getStudentAnswersForExam(int $studentId, int $examTrainingId): Collection
    {
        return $this->model
            ->whereHas('question', function ($query) use ($examTrainingId) {
                $query->where('exam_training_id', $examTrainingId);
            })
            ->where('student_id', $studentId)
            ->with('question')
            ->get();
    }
}
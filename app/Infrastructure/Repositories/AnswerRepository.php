<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\Repositories\AnswerRepositoryInterface;
use App\Infrastructure\Models\Answer;
use App\Infrastructure\Models\AnswerSelection;
use Illuminate\Support\Collection;

class AnswerRepository extends BaseRepository implements AnswerRepositoryInterface
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
            ->with(['selections.option', 'grade'])
            ->first();
    }

    public function getAnswersForStudentExam(int $studentId, int $examTrainingId): Collection
    {
        return $this->model
            ->where('student_id', $studentId)
            ->whereHas('question', function ($query) use ($examTrainingId) {
                $query->where('exam_training_id', $examTrainingId);
            })
            ->with(['question', 'selections.option', 'grade'])
            ->get();
    }

    public function createAnswer(array $data): Answer
    {
        return $this->model->create($data);
    }

    public function updateAnswer(int $id, array $data): Answer
    {
        $answer = $this->model->findOrFail($id);
        $answer->update($data);
        return $answer->fresh(['selections.option', 'grade']);
    }

    public function createSelection(array $data): AnswerSelection
    {
        return AnswerSelection::create($data);
    }

    public function deleteSelections(int $answerId): void
    {
        AnswerSelection::where('answer_id', $answerId)->delete();
    }

    public function getAnswersCount(int $studentId, array $questionIds): int
    {
        return $this->model
            ->where('student_id', $studentId)
            ->whereIn('question_id', $questionIds)
            ->count();
    }
}

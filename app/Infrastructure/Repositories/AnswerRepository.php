<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\Repositories\AnswerRepositoryInterface;
use App\Infrastructure\Models\Answer;
use App\Infrastructure\Models\AnswerPair;
use App\Infrastructure\Models\AnswerOrder;
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
            ->with(['option', 'pairs.leftOption', 'pairs.rightOption', 'orders.option', 'grade'])
            ->first();
    }

    public function getAnswersForStudentExam(int $studentId, int $examTrainingId): Collection
    {
        return $this->model
            ->where('student_id', $studentId)
            ->whereHas('question', function ($query) use ($examTrainingId) {
                $query->where('exam_training_id', $examTrainingId);
            })
            ->with(['question', 'option', 'pairs.leftOption', 'pairs.rightOption', 'orders.option', 'grade'])
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
        return $answer->fresh(['option', 'pairs.leftOption', 'pairs.rightOption', 'orders.option', 'grade']);
    }

    public function createPair(int $answerId, int $leftOptionId, int $rightOptionId): void
    {
        AnswerPair::create([
            'answer_id' => $answerId,
            'left_option_id' => $leftOptionId,
            'right_option_id' => $rightOptionId,
        ]);
    }

    public function createOrder(int $answerId, int $optionId, int $order): void
    {
        AnswerOrder::create([
            'answer_id' => $answerId,
            'option_id' => $optionId,
            'order' => $order,
        ]);
    }

    public function deletePairs(int $answerId): void
    {
        AnswerPair::where('answer_id', $answerId)->delete();
    }

    public function deleteOrders(int $answerId): void
    {
        AnswerOrder::where('answer_id', $answerId)->delete();
    }

    public function getAnswersCount(int $studentId, array $questionIds): int
    {
        return $this->model
            ->where('student_id', $studentId)
            ->whereIn('question_id', $questionIds)
            ->count();
    }
}

<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\Repositories\ExamTrainingRepositoryInterface;
use App\Infrastructure\Models\ExamTraining;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ExamTrainingRepository extends BaseRepository implements ExamTrainingRepositoryInterface
{
    public function __construct(ExamTraining $model)
    {
        parent::__construct($model);
    }

    public function getForStudent(int $studentId, ?string $type, int $perPage): LengthAwarePaginator
    {
        $query = $this->model->with(['subject', 'video', 'book', 'creator']);

        if ($type) {
            $query->where('type', $type);
        }

        return $query->paginate($perPage);
    }

    public function getDetailsWithQuestions(int $id, int $perPage): array
    {
        $examTraining = $this->model
            ->with(['subject', 'video', 'book', 'questions.options'])
            ->findOrFail($id);

        $questions = $examTraining->questions()->with('options')->paginate($perPage);

        return [
            'examTraining' => $examTraining,
            'questions' => $questions,
        ];
    }
}
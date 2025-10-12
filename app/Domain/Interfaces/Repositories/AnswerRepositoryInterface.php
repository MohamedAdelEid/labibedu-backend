<?php

namespace App\Domain\Interfaces\Repositories;

use App\Infrastructure\Models\Answer;
use App\Infrastructure\Models\AnswerSelection;
use Illuminate\Support\Collection;

interface AnswerRepositoryInterface
{
    public function findByStudentAndQuestion(int $studentId, int $questionId): ?Answer;
    
    public function getAnswersForStudentExam(int $studentId, int $examTrainingId): Collection;
    
    public function createAnswer(array $data): Answer;
    
    public function updateAnswer(int $id, array $data): Answer;

    public function createSelection(array $data): AnswerSelection;
    
    public function deleteSelections(int $answerId): void;
    
    public function getAnswersCount(int $studentId, array $questionIds): int;
}

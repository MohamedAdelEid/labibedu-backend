<?php

namespace App\Domain\Interfaces\Repositories;

interface AnswerRepositoryInterface extends BaseRepositoryInterface
{
    public function createWithSelections(array $answerData, array $selections): array;
    public function submitEntireExam(int $studentId, int $examTrainingId, array $answers): array;
    public function hasStudentTakenExam(int $studentId, int $examTrainingId): bool;
}
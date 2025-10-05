<?php

namespace App\Application\Services;

use App\Application\DTOs\Exam\SubmitAnswerDTO;
use App\Application\DTOs\Exam\SubmitEntireExamDTO;
use App\Domain\Interfaces\Repositories\AnswerRepositoryInterface;
use App\Domain\Interfaces\Repositories\ExamTrainingRepositoryInterface;

class ExamService
{
    public function __construct(
        private ExamTrainingRepositoryInterface $examTrainingRepository,
        private AnswerRepositoryInterface $answerRepository,
    ) {}

    public function getExamsAndTrainings(int $studentId, ?string $type, int $perPage)
    {
        return $this->examTrainingRepository->getForStudent($studentId, $type, $perPage);
    }

    public function getDetails(int $id, int $perPage): array
    {
        return $this->examTrainingRepository->getDetailsWithQuestions($id, $perPage);
    }

    public function submitAnswer(SubmitAnswerDTO $dto): array
    {
        return $this->answerRepository->createWithSelections(
            [
                'student_id' => $dto->studentId,
                'question_id' => $dto->questionId,
                'user_answer' => $dto->userAnswer ?? null,
            ],
            $dto->selections
        );
    }

    public function submitEntireExam(SubmitEntireExamDTO $dto): array
    {
        return $this->answerRepository->submitEntireExam(
            $dto->studentId,
            $dto->examTrainingId,
            $dto->answers
        );
    }
}
<?php

namespace App\Application\Services;

use App\Domain\Factories\AnswerCheckerFactory;
use App\Application\DTOs\Exam\SubmitAnswerDTO;
use App\Domain\Enums\QuestionType;
use App\Domain\Interfaces\Repositories\AnswerRepositoryInterface;
use App\Domain\Interfaces\Repositories\ExamAttemptRepositoryInterface;
use App\Domain\Interfaces\Repositories\QuestionRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Exception;

class AnswerService
{
    public function __construct(
        private AnswerRepositoryInterface $answerRepository,
        private QuestionRepositoryInterface $questionRepository,
        private ExamAttemptRepositoryInterface $examAttemptRepository,
        private AnswerCheckerFactory $answerCheckerFactory
    ) {}

    public function submitAnswer(SubmitAnswerDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            $question = $this->questionRepository->findOrFail($dto->questionId);
            $examTraining = $question->examTraining;

            $this->validateAnswerData($dto, $question);

            $attempt = null;

            if ($examTraining->isExam()) {
                $attempt = $this->validateAttempt($dto->studentId, $examTraining, $dto->timeSpent);
            }

            $existingAnswer = $this->answerRepository->findByStudentAndQuestion(
                $dto->studentId,
                $dto->questionId
            );

            $tempAnswer = $this->createTempAnswer($dto, $question);
            $isCorrect = $this->answerCheckerFactory->check($question, $tempAnswer);

            $gainedXp = $isCorrect && $examTraining->isTraining() ? $question->xp : 0;
            $gainedCoins = $isCorrect && $examTraining->isTraining() ? $question->coins : 0;

            if ($existingAnswer) {
                $this->deleteOldAnswerData($existingAnswer, $question->type);
                
                $answer = $this->answerRepository->updateAnswer(
                    $existingAnswer->id, 
                    $this->prepareAnswerData($dto, $question->type)
                );
            } else {
                $answer = $this->answerRepository->createAnswer(
                    array_merge(
                        [
                            'student_id' => $dto->studentId,
                            'question_id' => $dto->questionId,
                            'submitted_at' => now(),
                        ],
                        $this->prepareAnswerData($dto, $question->type)
                    )
                );
            }

            $this->saveAnswerRelations($answer, $question, $dto);

            return [
                'answer' => $answer->load(['option', 'pairs.leftOption', 'pairs.rightOption', 'orders.option']),
                'is_correct' => $isCorrect,
                'gained_xp' => $gainedXp,
                'gained_coins' => $gainedCoins,
                'remaining_seconds' => $attempt?->remaining_seconds,
            ];
        });
    }

    private function validateAnswerData(SubmitAnswerDTO $dto, $question): void
    {
        if ($dto->selectedOptionIds !== null) {
            foreach ($dto->selectedOptionIds as $optionId) {
                $optionExists = $question->options->contains('id', $optionId);
                if (!$optionExists) {
                    throw new Exception('Selected option does not belong to this question.');
                }
            }
        }

        if ($dto->connectPairs !== null) {
            foreach ($dto->connectPairs as $pair) {
                $leftExists = $question->options->contains('id', $pair['left_option_id']);
                $rightExists = $question->options->contains('id', $pair['right_option_id']);
                if (!$leftExists || !$rightExists) {
                    throw new Exception('Connect pair options do not belong to this question.');
                }
            }
        }

        if ($dto->arrangeOptionIds !== null) {
            foreach ($dto->arrangeOptionIds as $optionId) {
                $optionExists = $question->options->contains('id', $optionId);
                if (!$optionExists) {
                    throw new Exception('Arrange option does not belong to this question.');
                }
            }
        }

        if ($dto->trueFalseAnswer !== null && !in_array($dto->trueFalseAnswer, [true, false])) {
            throw new Exception('True/false answer must be a boolean value.');
        }
    }

    private function prepareAnswerData(SubmitAnswerDTO $dto, QuestionType $type): array
    {
        return match ($type) {
            QuestionType::CHOICE => [
                'option_id' => $dto->selectedOptionIds[0] ?? null,
                'true_false_answer' => null,
                'user_answer' => null,
            ],
            QuestionType::TRUE_FALSE => [
                'option_id' => null,
                'true_false_answer' => $dto->trueFalseAnswer,
                'user_answer' => null,
            ],
            QuestionType::WRITTEN => [
                'option_id' => null,
                'true_false_answer' => null,
                'user_answer' => $dto->writtenAnswer,
            ],
            QuestionType::CONNECT, QuestionType::ARRANGE => [
                'option_id' => null,
                'true_false_answer' => null,
                'user_answer' => null,
            ],
        };
    }

    private function deleteOldAnswerData($answer, QuestionType $type): void
    {
        match ($type) {
            QuestionType::CONNECT => $this->answerRepository->deletePairs($answer->id),
            QuestionType::ARRANGE => $this->answerRepository->deleteOrders($answer->id),
            default => null,
        };
    }

    private function saveAnswerRelations($answer, $question, SubmitAnswerDTO $dto): void
    {
        match ($question->type) {
            QuestionType::CONNECT => $this->saveConnectPairs($answer, $dto),
            QuestionType::ARRANGE => $this->saveArrangeOrders($answer, $dto),
            default => null,
        };
    }

    private function saveConnectPairs($answer, SubmitAnswerDTO $dto): void
    {
        foreach ($dto->connectPairs ?? [] as $pair) {
            $this->answerRepository->createPair(
                $answer->id,
                $pair['left_option_id'],
                $pair['right_option_id']
            );
        }
    }

    private function saveArrangeOrders($answer, SubmitAnswerDTO $dto): void
    {
        foreach ($dto->arrangeOptionIds ?? [] as $index => $optionId) {
            $this->answerRepository->createOrder(
                $answer->id,
                $optionId,
                $index + 1
            );
        }
    }

    private function validateAttempt(int $studentId, $examTraining, int $timeSpent)
    {
        $attempt = $this->examAttemptRepository->findActiveAttempt($studentId, $examTraining->id);

        if (!$attempt) {
            throw new Exception('No active exam attempt found. Please start the exam first.');
        }

        if ($attempt->isFinished()) {
            throw new Exception('Exam has already been submitted.');
        }

        if ($attempt->hasExpired()) {
            $attempt->markAsFinished();
            throw new Exception('Exam time has expired.');
        }

        if ($examTraining->hasEnded()) {
            $attempt->markAsFinished();
            throw new Exception('Exam has ended.');
        }

        $attempt->updateRemainingTime($timeSpent);

        return $attempt;
    }

    private function createTempAnswer(SubmitAnswerDTO $dto, $question)
    {
        $tempAnswer = new \stdClass();
        $tempAnswer->user_answer = $dto->writtenAnswer;
        $tempAnswer->option_id = $dto->selectedOptionIds[0] ?? null;
        $tempAnswer->true_false_answer = $dto->trueFalseAnswer;
        $tempAnswer->option = null;
        $tempAnswer->pairs = collect();
        $tempAnswer->orders = collect();
        $tempAnswer->grade = null;

        // Load the actual option if option_id is set
        if ($tempAnswer->option_id) {
            $tempAnswer->option = $question->options->firstWhere('id', $tempAnswer->option_id);
        }

        match ($question->type) {
            QuestionType::CONNECT => $tempAnswer->pairs = $this->buildTempPairs($dto->connectPairs ?? [], $question),
            QuestionType::ARRANGE => $tempAnswer->orders = $this->buildTempOrders($dto->arrangeOptionIds ?? []),
            default => null,
        };

        return $tempAnswer;
    }

    private function buildTempPairs(array $pairs, $question)
    {
        return collect($pairs)->map(function($pair) use ($question) {
            $leftOption = $question->options->firstWhere('id', $pair['left_option_id']);
            $rightOption = $question->options->firstWhere('id', $pair['right_option_id']);
            
            return (object) [
                'left_option_id' => $pair['left_option_id'],
                'right_option_id' => $pair['right_option_id'],
                'leftOption' => $leftOption,
                'rightOption' => $rightOption,
            ];
        });
    }

    private function buildTempOrders(array $optionIds)
    {
        return collect($optionIds)->map(fn($id, $index) => (object) [
            'option_id' => $id,
            'order' => $index + 1,
        ]);
    }
}

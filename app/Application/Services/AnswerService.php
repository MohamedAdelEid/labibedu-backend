<?php

namespace App\Application\Services;

use App\Domain\Factories\AnswerCheckerFactory;
use App\Application\DTOs\Exam\SubmitAnswerDTO;
use App\Domain\Enums\QuestionType;
use App\Domain\Interfaces\Repositories\AnswerRepositoryInterface;
use App\Domain\Interfaces\Repositories\ExamAttemptRepositoryInterface;
use App\Domain\Interfaces\Repositories\QuestionRepositoryInterface;
use App\Infrastructure\Models\Answer;
use App\Infrastructure\Models\AnswerSelection;
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

    /**
     * Submit an answer for a question
     */
    public function submitAnswer(SubmitAnswerDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            $question = $this->questionRepository->findOrFail($dto->questionId);
            $examTraining = $question->examTraining;

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
                $existingAnswer->selections()->delete();
                $answer = $this->answerRepository->updateAnswer($existingAnswer->id, [
                    'user_answer' => $dto->writtenAnswer,
                    'submitted_at' => now(),
                ]);
            } else {
                $answer = $this->answerRepository->createAnswer([
                    'student_id' => $dto->studentId,
                    'question_id' => $dto->questionId,
                    'user_answer' => $dto->writtenAnswer,
                    'submitted_at' => now(),
                ]);
            }

            $this->saveAnswerSelections($answer, $question, $dto, $isCorrect);

            return [
                'answer' => $answer->load('selections.option'),
                'is_correct' => $isCorrect,
                'gained_xp' => $gainedXp,
                'gained_coins' => $gainedCoins,
                'remaining_seconds' => $attempt?->remaining_seconds,
            ];
        });
    }

    /**
     * Validate exam attempt
     */
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

    /**
     * Create temporary answer object for checking
     */
    private function createTempAnswer(SubmitAnswerDTO $dto, $question)
    {
        $tempAnswer = new \stdClass();
        $tempAnswer->user_answer = $dto->writtenAnswer;
        $tempAnswer->selections = collect();

        match ($question->type) {
            QuestionType::CHOICE, QuestionType::TRUE_FALSE => 
                $tempAnswer->selections = collect($dto->selectedOptionIds ?? [])->map(fn($id) => (object)['option_id' => $id]),
            QuestionType::CONNECT => 
                $tempAnswer->selections = $this->buildConnectSelections($dto->connectPairs ?? []),
            QuestionType::ARRANGE => 
                $tempAnswer->selections = collect($dto->arrangeOptionIds ?? [])->map(fn($id, $index) => (object)['option_id' => $id, 'order' => $index + 1]),
            default => null,
        };

        return $tempAnswer;
    }

    /**
     * Build connect selections with order
     */
    private function buildConnectSelections(array $pairs)
    {
        $selections = collect();
        foreach ($pairs as $index => $pair) {
            $selections->push((object)['option_id' => $pair['left_option_id'], 'order' => $index * 2]);
            $selections->push((object)['option_id' => $pair['right_option_id'], 'order' => $index * 2 + 1]);
        }
        return $selections;
    }

    /**
     * Save answer selections to database
     */
    private function saveAnswerSelections(Answer $answer, $question, SubmitAnswerDTO $dto, bool $isCorrect): void
    {
        match ($question->type) {
            QuestionType::CHOICE, QuestionType::TRUE_FALSE => $this->saveChoiceSelections($answer, $dto),
            QuestionType::CONNECT => $this->saveConnectSelections($answer, $dto),
            QuestionType::ARRANGE => $this->saveArrangeSelections($answer, $dto),
            QuestionType::WRITTEN => null,
            default => null,
        };
    }

    private function saveChoiceSelections(Answer $answer, SubmitAnswerDTO $dto): void
    {
        foreach ($dto->selectedOptionIds ?? [] as $optionId) {
            AnswerSelection::create([
                'answer_id' => $answer->id,
                'option_id' => $optionId,
            ]);
        }
    }

    private function saveConnectSelections(Answer $answer, SubmitAnswerDTO $dto): void
    {
        foreach ($dto->connectPairs ?? [] as $index => $pair) {
            AnswerSelection::create([
                'answer_id' => $answer->id,
                'option_id' => $pair['left_option_id'],
                'order' => $index * 2,
            ]);
            
            AnswerSelection::create([
                'answer_id' => $answer->id,
                'option_id' => $pair['right_option_id'],
                'order' => $index * 2 + 1,
            ]);
        }
    }

    private function saveArrangeSelections(Answer $answer, SubmitAnswerDTO $dto): void
    {
        foreach ($dto->arrangeOptionIds ?? [] as $index => $optionId) {
            AnswerSelection::create([
                'answer_id' => $answer->id,
                'option_id' => $optionId,
                'order' => $index + 1,
            ]);
        }
    }
}

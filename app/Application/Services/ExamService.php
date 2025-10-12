<?php

namespace App\Application\Services;

use App\Application\Calculators\ExamPerformanceCalculator;
use App\Application\DTOs\Exam\SubmitAnswerDTO;
use App\Application\DTOs\Exam\SubmitEntireExamDTO;
use App\Domain\Interfaces\Repositories\AnswerRepositoryInterface;
use App\Domain\Interfaces\Repositories\ExamAttemptRepositoryInterface;
use App\Domain\Interfaces\Repositories\ExamTrainingRepositoryInterface;
use App\Domain\Interfaces\Repositories\QuestionRepositoryInterface;
use App\Domain\Services\AnswerEvaluationService;
use Illuminate\Support\Facades\DB;
use Exception;

class ExamService
{
    public function __construct(
        private ExamTrainingRepositoryInterface $examTrainingRepository,
        private QuestionRepositoryInterface $questionRepository,
        private ExamAttemptRepositoryInterface $examAttemptRepository,
        private AnswerRepositoryInterface $answerRepository,
        private AnswerService $answerService,
        private ExamPerformanceCalculator $performanceCalculator,
        private AnswerEvaluationService $answerEvaluationService
    ) {
    }

    public function getDetails(int $examId, int $studentId, int $perPage): array
    {
        $this->examAttemptRepository->autoFinishExpiredAttempts();

        $examTraining = $this->examTrainingRepository->findOrFail($examId);
        $questions = $this->questionRepository->getByExamTrainingId($examId, $perPage);

        $attemptData = null;
        $previousAnswers = collect();

        if ($examTraining->isExam()) {
            $attempt = $this->examAttemptRepository->findLatestAttempt($studentId, $examId);

            if ($attempt) {
                if ($attempt->isInProgress() && $attempt->hasExpired()) {
                    $attempt->markAsFinished();
                }

                $attemptData = $attempt;
                $previousAnswers = $this->answerRepository->getAnswersForStudentExam($studentId, $examId);
            }
        } else {
            $previousAnswers = $this->answerRepository->getAnswersForStudentExam($studentId, $examId);
        }

        $answersMap = $previousAnswers->keyBy('question_id');
        $evaluatedQuestions = $questions->map(function ($question) use ($answersMap, $attemptData) {
            $answer = $answersMap->get($question->id);
            return $this->answerEvaluationService->evaluateQuestion($question, $answer, $attemptData);
        });

        return [
            'examTraining' => $examTraining,
            'questions' => $questions,
            'attempt' => $attemptData,
            'previousAnswers' => $previousAnswers,
            'evaluatedQuestions' => $evaluatedQuestions,
        ];
    }

    public function startExam(int $examId, int $studentId): array
    {
        $examTraining = $this->examTrainingRepository->findOrFail($examId);

        if (!$examTraining->isExam()) {
            throw new Exception('This is not an exam.');
        }

        if (!$examTraining->hasStarted()) {
            throw new Exception('Exam has not started yet.');
        }

        if ($examTraining->hasEnded()) {
            throw new Exception('Exam has already ended.');
        }

        $existingAttempt = $this->examAttemptRepository->findLatestAttempt($studentId, $examId);

        if ($existingAttempt && $existingAttempt->isFinished()) {
            throw new Exception('You have already completed this exam.');
        }

        if ($existingAttempt && $existingAttempt->isInProgress()) {
            return [
                'attempt' => $existingAttempt,
                'message' => 'Exam already in progress.',
            ];
        }

        $attempt = $this->examAttemptRepository->createAttempt(
            $studentId,
            $examId,
            $examTraining->duration
        );

        return [
            'attempt' => $attempt,
            'message' => 'Exam started successfully.',
        ];
    }

    /**
     * Submit a single answer - delegates to AnswerService
     */
    public function submitAnswer(SubmitAnswerDTO $dto): array
    {
        return $this->answerService->submitAnswer($dto);
    }

    /**
     * Submit entire exam and calculate final performance
     */
    public function submitEntireExam(SubmitEntireExamDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            $examTraining = $this->examTrainingRepository->findOrFail($dto->examTrainingId);

            if (!$examTraining->isExam()) {
                throw new Exception('This is not an exam.');
            }

            $attempt = $this->examAttemptRepository->findActiveAttempt(
                $dto->studentId,
                $dto->examTrainingId
            );

            if (!$attempt) {
                throw new Exception('No active exam attempt found.');
            }

            if ($attempt->isFinished()) {
                throw new Exception('Exam has already been submitted.');
            }

            if ($examTraining->hasEnded()) {
                throw new Exception('Exam has ended.');
            }

            $attempt->markAsFinished();

            $questions = $examTraining->questions;
            $answers = $this->answerRepository->getAnswersForStudentExam($dto->studentId, $dto->examTrainingId);

            $performance = $this->performanceCalculator->calculate($questions, $answers);

            return [
                'total_questions' => $questions->count(),
                'correct_answers' => $performance['correct_answers'],
                'score_percentage' => $performance['score_percentage'],
                'total_xp' => $performance['earned_xp'],
                'total_coins' => $performance['earned_coins'],
            ];
        });
    }
}

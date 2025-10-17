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
        $evaluatedQuestions = collect($questions->items())->map(function ($question) use ($answersMap, $attemptData) {
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

        // For exams, check start/end dates
        if ($examTraining->isExam()) {
            if (!$examTraining->hasStarted()) {
                throw new Exception('Exam has not started yet.');
            }

            if ($examTraining->hasEnded()) {
                throw new Exception('Exam has already ended.');
            }
        }

        $existingAttempt = $this->examAttemptRepository->findLatestAttempt($studentId, $examId);

        if ($existingAttempt && $existingAttempt->isFinished()) {
            $message = $examTraining->isExam() ? 'You have already completed this exam.' : 'You have already completed this training.';
            throw new Exception($message);
        }

        if ($existingAttempt && $existingAttempt->isInProgress()) {
            $message = $examTraining->isExam() ? 'Exam already in progress.' : 'Training already in progress.';
            return [
                'attempt' => $existingAttempt,
                'message' => $message,
            ];
        }

        // For exams, use duration. For training, use 0 (unlimited time)
        $duration = $examTraining->isExam() ? $examTraining->duration : 0;

        $attempt = $this->examAttemptRepository->createAttempt(
            $studentId,
            $examId,
            $duration
        );

        $message = $examTraining->isExam() ? 'Exam started successfully.' : 'Training started successfully.';
        return [
            'attempt' => $attempt,
            'message' => $message,
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
     * Send heartbeat to update remaining time for active exam
     */
    public function sendHeartbeat(int $examId, int $studentId, int $timeSpent): array
    {
        $examTraining = $this->examTrainingRepository->findOrFail($examId);

        // Only for exams, not training
        if (!$examTraining->isExam()) {
            throw new Exception('Heartbeat is only available for exams.');
        }

        $attempt = $this->examAttemptRepository->findActiveAttempt($studentId, $examId);

        if (!$attempt) {
            throw new Exception('No active exam attempt found.');
        }

        if ($attempt->isFinished()) {
            throw new Exception('Exam has already been submitted.');
        }

        // Check if exam has ended
        if ($examTraining->hasEnded()) {
            $attempt->markAsFinished();
            throw new Exception('Exam has ended.');
        }

        // Check if time has expired
        if ($attempt->hasExpired()) {
            $attempt->markAsFinished();
            throw new Exception('Exam time has expired.');
        }

        // Update remaining time
        $attempt->updateRemainingTime($timeSpent);

        return [
            'remaining_seconds' => $attempt->remaining_seconds,
            'is_active' => true,
        ];
    }

    /**
     * Submit entire exam/training and calculate final performance
     */
    public function submitEntireExam(SubmitEntireExamDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            $examTraining = $this->examTrainingRepository->findOrFail($dto->examTrainingId);

            $attempt = $this->examAttemptRepository->findActiveAttempt(
                $dto->studentId,
                $dto->examTrainingId
            );

            if (!$attempt) {
                $message = $examTraining->isExam() ? 'No active exam attempt found.' : 'No active training attempt found.';
                throw new Exception($message);
            }

            if ($attempt->isFinished()) {
                $message = $examTraining->isExam() ? 'Exam has already been submitted.' : 'Training has already been submitted.';
                throw new Exception($message);
            }

            // For exams, check if exam has ended
            if ($examTraining->isExam() && $examTraining->hasEnded()) {
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

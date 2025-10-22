<?php

namespace App\Application\Services;

use App\Application\Calculators\ExamPerformanceCalculator;
use App\Application\Calculators\ExamScoringCalculator;
use App\Application\DTOs\Exam\SendHeartbeatDTO;
use App\Application\DTOs\Exam\SubmitAnswerDTO;
use App\Application\DTOs\Exam\SubmitEntireExamDTO;
use App\Domain\Interfaces\Repositories\VideoRepositoryInterface;
use App\Domain\Interfaces\Repositories\BookRepositoryInterface;
use App\Domain\Interfaces\Repositories\AnswerRepositoryInterface;
use App\Domain\Interfaces\Repositories\ExamAttemptRepositoryInterface;
use App\Domain\Interfaces\Repositories\ExamTrainingRepositoryInterface;
use App\Domain\Interfaces\Repositories\QuestionRepositoryInterface;
use App\Domain\Interfaces\Services\StudentServiceInterface;
use App\Domain\Interfaces\Services\BookServiceInterface;
use App\Domain\Interfaces\Services\VideoServiceInterface;
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
        private ExamScoringCalculator $scoringCalculator,
        private AnswerEvaluationService $answerEvaluationService,
        private StudentServiceInterface $studentService,
        private BookServiceInterface $bookService,
        private VideoServiceInterface $videoService,
        private VideoRepositoryInterface $videoRepository,
        private BookRepositoryInterface $bookRepository
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
    public function sendHeartbeat(SendHeartbeatDTO $dto): array
    {
        $examTraining = $this->examTrainingRepository->findOrFail($dto->examId);

        // Only for exams, not training
        if (!$examTraining->isExam()) {
            throw new Exception('Heartbeat is only available for exams.');
        }

        $attempt = $this->examAttemptRepository->findActiveAttempt($dto->studentId, $dto->examId);

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

        // Security check: remaining_seconds cannot be greater than current remaining_seconds
        if ($dto->remainingSeconds > $attempt->remaining_seconds) {
            throw new Exception('Invalid remaining_seconds value. Cannot have more time than remaining.');
        }

        // Check if remaining_seconds is negative
        if ($dto->remainingSeconds < 0) {
            throw new Exception('remaining_seconds cannot be negative.');
        }

        // Update remaining time using repository
        $this->examAttemptRepository->updateRemainingTime($attempt->id, $dto->remainingSeconds);

        // Refresh attempt to get updated data
        $attempt->refresh();

        // Check if time has expired after update
        if ($attempt->hasExpired()) {
            $attempt->markAsFinished();
            throw new Exception('Exam time has expired.');
        }

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

            // Process completion scoring with new flow
            $scoringResult = $this->processExamCompletionScoring($dto->studentId, $dto->examTrainingId);

            return [
                'total_questions' => $scoringResult['total_questions'],
                'correct_answers' => $scoringResult['correct_answers'],
                'score_percentage' => $scoringResult['score_percentage'],
                'total_xp' => $scoringResult['total_xp_gained'],
                'total_coins' => $scoringResult['total_coins_gained'],
                'scoring' => $scoringResult,
            ];

        });
    }

    /**
     * Process exam completion scoring with new flow
     * 1. Check if exam is related to video/book
     * 2. Verify completion and get scoring from related content
     * 3. Validate written questions evaluation
     * 4. Calculate exam scoring
     * 5. Combine scorings and update student
     */
    private function processExamCompletionScoring(int $studentId, int $examTrainingId): array
    {
        $examTraining = $this->examTrainingRepository->findOrFail($examTrainingId);

        // Step 1: Check for related content and get scoring
        $relatedContentScoring = $this->getRelatedContentScoring($studentId, $examTraining);

        // Step 2: Validate written questions evaluation before calculating exam scoring
        $this->scoringCalculator->validateWrittenQuestionsEvaluation($studentId, $examTrainingId);

        // Step 3: Calculate exam scoring using the new calculator
        $examScoring = $this->scoringCalculator->calculateExamScoring($studentId, $examTrainingId);

        // Step 4: Combine related content scoring with exam scoring
        $totalXp = $examScoring['xp'] + $relatedContentScoring['xp'];
        $totalCoins = $examScoring['coins'] + $relatedContentScoring['coins'];

        // Step 5: Update student's scoring
        $this->studentService->updateStudentScoring(
            $studentId,
            $totalXp,
            $totalCoins,
            "Exam completion: {$examTraining->title}"
        );

        return [
            'success' => true,
            'exam_scoring' => $examScoring,
            'related_content_scoring' => $relatedContentScoring,
            'total_xp_gained' => $totalXp,
            'total_coins_gained' => $totalCoins,
            'correct_answers' => $examScoring['correct_answers'],
            'total_questions' => $examScoring['total_questions'],
            'score_percentage' => $examScoring['score_percentage'],
        ];
    }

    /**
     * Get scoring from related book/video content
     * Updated flow: Check completion first, throw exception if not completed
     * Note: Videos and books have related_training_id pointing to exams_trainings
     */
    private function getRelatedContentScoring(int $studentId, $examTraining): array
    {
        $totalXp = 0;
        $totalCoins = 0;
        $relatedContent = [];

        // Check if there are videos related to this exam/training
        $relatedVideos = $this->videoRepository->getByRelatedTrainingId($examTraining->id);
        foreach ($relatedVideos as $video) {
            try {
                $videoScoring = $this->videoService->getVideoCompletionScoring($studentId, $video->id);
                $totalXp += $videoScoring['xp'];
                $totalCoins += $videoScoring['coins'];
                $relatedContent['videos'][] = $videoScoring;
            } catch (Exception $e) {
                throw new Exception("You must finish the video '{$video->title}' first: {$e->getMessage()}");
            }
        }

        // Check if there are books related to this exam/training
        $relatedBooks = $this->bookRepository->getByRelatedTrainingId($examTraining->id);
        foreach ($relatedBooks as $book) {
            try {
                $bookScoring = $this->bookService->getBookCompletionScoring($studentId, $book->id);
                $totalXp += $bookScoring['xp'];
                $totalCoins += $bookScoring['coins'];
                $relatedContent['books'][] = $bookScoring;
            } catch (Exception $e) {
                throw new Exception("You must finish the book '{$book->title}' first: {$e->getMessage()}");
            }
        }

        return [
            'xp' => $totalXp,
            'coins' => $totalCoins,
            'related_content' => $relatedContent,
        ];
    }

    /**
     * Check if all written questions for a student are properly evaluated
     * Delegates to ExamScoringCalculator for consistency
     */
    public function areAllWrittenQuestionsEvaluated(int $studentId, int $examTrainingId): bool
    {
        return $this->scoringCalculator->areAllWrittenQuestionsEvaluated($studentId, $examTrainingId);
    }
}

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
use App\Domain\Interfaces\Repositories\JourneyRepositoryInterface;
use App\Domain\Interfaces\Repositories\AssignmentRepositoryInterface;
use App\Domain\Interfaces\Services\StudentServiceInterface;
use App\Domain\Interfaces\Services\BookServiceInterface;
use App\Domain\Interfaces\Services\VideoServiceInterface;
use App\Domain\Services\AnswerEvaluationService;
use App\Domain\Enums\StudentStageStatus;
use App\Infrastructure\Models\StudentStageProgress;
use App\Infrastructure\Models\StageContent;
use App\Infrastructure\Models\Assignment;
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
        private BookRepositoryInterface $bookRepository,
        private JourneyRepositoryInterface $journeyRepository,
        private AssignmentRepositoryInterface $assignmentRepository
    ) {
    }

    public function getDetails(int $examId, int $studentId, int $perPage): array
    {
        $this->examAttemptRepository->autoFinishExpiredAttempts();

        $examTraining = $this->examTrainingRepository->findOrFail($examId);
        $examTraining->load('book', 'video');
        $questions = $this->questionRepository->getByExamTrainingId($examId, $perPage);

        $attemptData = null;
        $previousAnswers = collect();

        // Get attempt for both exams and trainings
        $attempt = $this->examAttemptRepository->findLatestAttempt($studentId, $examId);

        if ($attempt) {
            // For exams, check if expired and mark as finished
            if ($examTraining->isExam() && $attempt->isInProgress() && $attempt->hasExpired()) {
                $attempt->markAsFinished();
            }

            $attemptData = $attempt;
            $previousAnswers = $this->answerRepository->getAnswersForStudentExam($studentId, $examId);
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

            $scoringResult = $this->processExamCompletionScoring($dto->studentId, $dto->examTrainingId);

            if ($dto->source === 'journey' && $dto->sourceId) {
                $this->handleJourneyProgress($dto->studentId, $dto->examTrainingId);
            }

            if ($dto->source === 'assignment') {
                $this->handleAssignmentCompletion($dto->studentId, $dto->examTrainingId);
            }

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
                $videoTitle = $video->title_ar ?? $video->title_en ?? 'Video';
                throw new Exception("You must finish the video '{$videoTitle}' first: {$e->getMessage()}");
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

    /**
     * Check if an exam/training is completed by the student
     * An exam/training is completed when there's a finished attempt
     * 
     * @param int $studentId
     * @param int $examTrainingId
     * @return bool
     */
    public function isExamTrainingCompleted(int $studentId, int $examTrainingId): bool
    {
        $attempt = $this->examAttemptRepository->findLatestAttempt($studentId, $examTrainingId);

        return $attempt && $attempt->isFinished();
    }

    /**
     * Get exam/training statistics after submission
     * Returns quick stats: total questions, score percentage, time spent, XP and coins earned
     * 
     * @param int $examTrainingId
     * @param int $studentId
     * @return array
     */
    public function getStatistics(int $examTrainingId, int $studentId): array
    {
        $examTraining = $this->examTrainingRepository->findOrFail($examTrainingId);

        // Check if student has completed this exam/training
        $attempt = $this->examAttemptRepository->findLatestAttempt($studentId, $examTrainingId);

        if (!$attempt || !$attempt->isFinished()) {
            throw new Exception('Exam/Training has not been completed yet.');
        }

        // Get all questions for this exam/training
        $questions = $this->questionRepository->getAllByExamTrainingId($examTrainingId);

        // Get all answers for this student
        $answers = $this->answerRepository->getAnswersForStudentExam($studentId, $examTrainingId);

        // Calculate performance metrics
        $earnedXp = 0;
        $earnedCoins = 0;
        $correctCount = 0;

        // Map answers by question_id for easier lookup
        $answersMap = $answers->keyBy('question_id');

        foreach ($questions as $question) {
            $answer = $answersMap->get($question->id);
            $evaluation = $this->answerEvaluationService->evaluateQuestion($question, $answer, null);

            // Check if answer is correct
            $isCorrect = $evaluation['is_correct'] ?? false;

            if ($isCorrect) {
                $correctCount++;
                // Add XP and coins from question directly (same as ExamScoringCalculator)
                $earnedXp += $question->xp ?? 0;
                $earnedCoins += $question->coins ?? 0;
            }
        }

        // Calculate score percentage
        $totalQuestions = $questions->count();
        $scorePercentage = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100, 2) : 0;

        // Calculate time spent in seconds
        $timeSpentSeconds = 0;
        if ($attempt->start_time && $attempt->end_time) {
            $timeSpentSeconds = $attempt->start_time->diffInSeconds($attempt->end_time);
        }

        return [
            'examTraining' => $examTraining,
            'attempt' => $attempt,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctCount,
            'score_percentage' => $scorePercentage,
            'time_spent_seconds' => $timeSpentSeconds,
            'earned_xp' => $earnedXp,
            'earned_coins' => $earnedCoins,
        ];
    }

    /**
     * Get exam/training summary for a student
     * Returns total questions, score earned, total XP and coins, and all questions with answers
     * 
     * @param int $examTrainingId
     * @param int $studentId
     * @return array
     */
    public function getSummary(int $examTrainingId, int $studentId): array
    {
        $examTraining = $this->examTrainingRepository->findOrFail($examTrainingId);

        // Check if student has completed this exam/training
        $attempt = $this->examAttemptRepository->findLatestAttempt($studentId, $examTrainingId);

        if (!$attempt || !$attempt->isFinished()) {
            throw new Exception('Exam/Training has not been completed yet.');
        }

        // Get all questions for this exam/training
        $questions = $this->questionRepository->getAllByExamTrainingId($examTrainingId);

        // Get all answers for this student
        $answers = $this->answerRepository->getAnswersForStudentExam($studentId, $examTrainingId);

        // Calculate total marks available
        $totalMarks = $questions->count();

        // Calculate performance metrics
        $earnedMarks = 0;
        $earnedXp = 0;
        $earnedCoins = 0;
        $correctCount = 0;

        // Map answers by question_id for easier lookup
        $answersMap = $answers->keyBy('question_id');

        // Prepare questions with their answers
        $questionsWithAnswers = $questions->map(function ($question) use ($answersMap, &$earnedMarks, &$earnedXp, &$earnedCoins, &$correctCount) {
            $answer = $answersMap->get($question->id);
            $evaluation = $this->answerEvaluationService->evaluateQuestion($question, $answer, null);

            // Check if answer is correct
            $isCorrect = $evaluation['is_correct'] ?? false;

            if ($isCorrect) {
                $correctCount++;
                $earnedMarks++;
                // Add XP and coins from question directly (same as ExamScoringCalculator)
                $earnedXp += $question->xp ?? 0;
                $earnedCoins += $question->coins ?? 0;
            }

            return [
                'question' => $question,
                'answer' => $answer,
                'is_correct' => $isCorrect,
                'evaluation' => $evaluation,
            ];
        });

        return [
            'examTraining' => $examTraining,
            'attempt' => $attempt,
            'total_questions' => $questions->count(),
            'total_marks' => $totalMarks,
            'earned_marks' => $earnedMarks,
            'earned_xp' => $earnedXp,
            'earned_coins' => $earnedCoins,
            'correct_answers' => $correctCount,
            'wrong_answers' => $answers->count() - $correctCount,
            'questions_with_answers' => $questionsWithAnswers,
        ];
    }

    private function handleJourneyProgress(int $studentId, int $examTrainingId): void
    {
        $examTraining = $this->examTrainingRepository->findOrFail($examTrainingId);

        $relatedBook = $this->bookRepository->getByRelatedTrainingId($examTrainingId)->first();
        $relatedVideo = $this->videoRepository->getByRelatedTrainingId($examTrainingId)->first();

        $stageContent = null;
        $contentId = null;
        $contentType = null;

        if ($relatedBook) {
            $stageContent = StageContent::where('content_type', 'book')
                ->where('content_id', $relatedBook->id)
                ->first();
            if ($stageContent) {
                $contentId = $relatedBook->id;
                $contentType = 'book';
            }
        }

        if (!$stageContent && $relatedVideo) {
            $stageContent = StageContent::where('content_type', 'video')
                ->where('content_id', $relatedVideo->id)
                ->first();
            if ($stageContent) {
                $contentId = $relatedVideo->id;
                $contentType = 'video';
            }
        }

        if (!$stageContent) {
            $stageContent = StageContent::where('content_type', 'examTraining')
                ->where('content_id', $examTrainingId)
                ->first();
            if ($stageContent) {
                $contentId = $examTrainingId;
                $contentType = 'examTraining';
            }
        }

        if (!$stageContent) {
            return;
        }

        $stage = $stageContent->stage;
        $contents = $stage->contents()->orderBy('id', 'asc')->get();
        $lastContent = $contents->last();

        if (!$lastContent || $lastContent->id !== $stageContent->id) {
            return;
        }

        $currentProgress = $this->journeyRepository->getStudentStageProgress($studentId, $stage->id);

        if ($currentProgress) {
            $currentProgress->update([
                'status' => StudentStageStatus::COMPLETED,
            ]);
        }

        $levels = $this->journeyRepository->getAllLevelsWithStages();
        $nextStage = $this->findNextStage($levels, $stage);

        if ($nextStage) {
            $existingProgress = $this->journeyRepository->getStudentStageProgress($studentId, $nextStage->id);
            if (!$existingProgress) {
                StudentStageProgress::create([
                    'student_id' => $studentId,
                    'stage_id' => $nextStage->id,
                    'status' => StudentStageStatus::NOT_STARTED,
                    'earned_stars' => 0,
                ]);
            }
        }
    }

    private function findNextStage($levels, $currentStage)
    {
        foreach ($levels as $level) {
            $stages = $level->stages->sortBy('order');
            $currentIndex = $stages->search(fn($s) => $s->id === $currentStage->id);

            if ($currentIndex !== false) {
                $nextIndex = $currentIndex + 1;
                if ($nextIndex < $stages->count()) {
                    return $stages->values()[$nextIndex];
                }

                $levelIndex = $levels->search(fn($l) => $l->id === $level->id);
                if ($levelIndex !== false && ($levelIndex + 1) < $levels->count()) {
                    $nextLevel = $levels->values()[$levelIndex + 1];
                    return $nextLevel->stages->sortBy('order')->first();
                }
            }
        }

        return null;
    }

    private function handleAssignmentCompletion(int $studentId, int $examTrainingId): void
    {
        $examTraining = $this->examTrainingRepository->findOrFail($examTrainingId);

        $relatedBook = $this->bookRepository->getByRelatedTrainingId($examTrainingId)->first();
        $relatedVideo = $this->videoRepository->getByRelatedTrainingId($examTrainingId)->first();

        $assignment = null;

        if ($relatedBook) {
            dump('relatedBook', $relatedBook);
            $assignment = Assignment::where('assignable_type', 'book')
                ->where('assignable_id', $relatedBook->id)
                ->whereHas('students', function ($q) use ($studentId) {
                    $q->where('student_id', $studentId);
                })
                ->first();
        }

        if (!$assignment && $relatedVideo) {
            $assignment = Assignment::where('assignable_type', 'video')
                ->where('assignable_id', $relatedVideo->id)
                ->whereHas('students', function ($q) use ($studentId) {
                    $q->where('student_id', $studentId);
                })
                ->first();
        }

        if (!$assignment) {
            $assignment = Assignment::where('assignable_type', 'examTraining')
                ->where('assignable_id', $examTrainingId)
                ->whereHas('students', function ($q) use ($studentId) {
                    $q->where('student_id', $studentId);
                })
                ->first();
        }

        if ($assignment) {
            $this->assignmentRepository->completeAssignmentForStudent($assignment->id, $studentId);
        }
    }
}

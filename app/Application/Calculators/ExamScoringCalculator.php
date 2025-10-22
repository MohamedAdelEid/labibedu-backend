<?php

namespace App\Application\Calculators;

use App\Domain\Interfaces\Repositories\QuestionRepositoryInterface;
use App\Domain\Interfaces\Repositories\AnswerRepositoryInterface;
use App\Domain\Interfaces\Repositories\ExamTrainingRepositoryInterface;
use App\Domain\Services\AnswerEvaluationService;
use App\Domain\Enums\QuestionType;
use Exception;

class ExamScoringCalculator
{
    public function __construct(
        private QuestionRepositoryInterface $questionRepository,
        private AnswerRepositoryInterface $answerRepository,
        private ExamTrainingRepositoryInterface $examTrainingRepository,
        private AnswerEvaluationService $answerEvaluationService
    ) {
    }

    /**
     * Calculate complete scoring for an exam/training
     * This method handles fetching data via repositories and calculating all scoring metrics
     * 
     * @param int $studentId
     * @param int $examTrainingId
     * @return array
     * @throws Exception
     */
    public function calculateExamScoring(int $studentId, int $examTrainingId): array
    {
        // Fetch exam training data
        $examTraining = $this->examTrainingRepository->findOrFail($examTrainingId);

        // Fetch questions and answers via repositories
        $questionsPaginated = $this->questionRepository->getByExamTrainingId($examTrainingId, 1000);
        $questions = collect($questionsPaginated->items());
        $answers = $this->answerRepository->getAnswersForStudentExam($studentId, $examTrainingId);

        // Initialize scoring variables
        $totalXp = 0;
        $totalCoins = 0;
        $correctAnswers = 0;
        $totalQuestions = $questions->count();
        $answeredQuestions = $answers->count();

        // Process each answer
        foreach ($answers as $answer) {
            $question = $questions->firstWhere('id', $answer->question_id);
            if (!$question) {
                continue;
            }

            $evaluation = $this->answerEvaluationService->evaluateQuestion($question, $answer, null);
            $isCorrect = $evaluation['is_correct'] ?? false;

            if ($isCorrect) {
                $correctAnswers++;

                $totalXp += $question->xp ?? 0;
                $totalCoins += $question->coins ?? 0;
            }
        }

        // Calculate percentage score
        $scorePercentage = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0;

        return [
            'exam_training_id' => $examTrainingId,
            'exam_title' => $examTraining->title,
            'exam_type' => $examTraining->type->value,
            'xp' => $totalXp,
            'coins' => $totalCoins,
            'correct_answers' => $correctAnswers,
            'total_questions' => $totalQuestions,
            'answered_questions' => $answeredQuestions,
            'score_percentage' => $scorePercentage,
            'is_exam' => $examTraining->isExam(),
            'is_training' => $examTraining->isTraining(),
        ];
    }

    /**
     * Check if exam has written questions that need evaluation
     * 
     * @param int $examTrainingId
     * @return bool
     */
    public function hasWrittenQuestions(int $examTrainingId): bool
    {
        $questionsPaginated = $this->questionRepository->getByExamTrainingId($examTrainingId, 1000);
        $questions = collect($questionsPaginated->items());

        return $questions->where('type', QuestionType::WRITTEN)->isNotEmpty();
    }

    /**
     * Check if all written questions for a student are properly evaluated
     * 
     * @param int $studentId
     * @param int $examTrainingId
     * @return bool
     */
    public function areAllWrittenQuestionsEvaluated(int $studentId, int $examTrainingId): bool
    {
        $questionsPaginated = $this->questionRepository->getByExamTrainingId($examTrainingId, 1000);
        $questions = collect($questionsPaginated->items());
        $writtenQuestions = $questions->where('type', QuestionType::WRITTEN)->pluck('id');

        if ($writtenQuestions->isEmpty()) {
            return true; // No written questions, so they are all "evaluated"
        }

        // Check if all written question answers have been graded
        $ungradedAnswersCount = $this->answerRepository->getAnswersForStudentExam($studentId, $examTrainingId)
            ->whereIn('question_id', $writtenQuestions)
            ->whereDoesntHave('grade', function ($query) {
                $query->where('status', 'graded');
            })
            ->count();

        return $ungradedAnswersCount === 0;
    }

    /**
     * Validate that written questions are properly evaluated before scoring
     * 
     * @param int $studentId
     * @param int $examTrainingId
     * @throws Exception
     */
    public function validateWrittenQuestionsEvaluation(int $studentId, int $examTrainingId): void
    {
        if ($this->hasWrittenQuestions($examTrainingId)) {
            if (!$this->areAllWrittenQuestionsEvaluated($studentId, $examTrainingId)) {
                throw new Exception('Cannot process scoring: Written questions need to be evaluated by teacher first');
            }
        }
    }

    /**
     * Get detailed scoring breakdown for reporting
     * 
     * @param int $studentId
     * @param int $examTrainingId
     * @return array
     */
    public function getDetailedScoringBreakdown(int $studentId, int $examTrainingId): array
    {
        $basicScoring = $this->calculateExamScoring($studentId, $examTrainingId);

        $questionsPaginated = $this->questionRepository->getByExamTrainingId($examTrainingId, 1000);
        $questions = collect($questionsPaginated->items());
        $answers = $this->answerRepository->getAnswersForStudentExam($studentId, $examTrainingId);

        $questionBreakdown = [];
        foreach ($questions as $question) {
            $answer = $answers->firstWhere('question_id', $question->id);
            $evaluation = $this->answerEvaluationService->evaluateQuestion($question, $answer, null);

            $questionBreakdown[] = [
                'question_id' => $question->id,
                'question_type' => $question->type->value,
                'is_answered' => $answer !== null,
                'is_correct' => $evaluation['is_correct'] ?? false,
                'xp_value' => $question->xp ?? 0,
                'coins_value' => $question->coins ?? 0,
                'earned_xp' => ($evaluation['is_correct'] ?? false) ? ($question->xp ?? 0) : 0,
                'earned_coins' => ($evaluation['is_correct'] ?? false) ? ($question->coins ?? 0) : 0,
            ];
        }

        return array_merge($basicScoring, [
            'question_breakdown' => $questionBreakdown,
            'written_questions_count' => $questions->where('type', QuestionType::WRITTEN)->count(),
            'choice_questions_count' => $questions->where('type', QuestionType::CHOICE)->count(),
            'true_false_count' => $questions->where('type', QuestionType::TRUE_FALSE)->count(),
        ]);
    }
}

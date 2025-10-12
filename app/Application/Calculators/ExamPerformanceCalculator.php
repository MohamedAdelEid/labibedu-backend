<?php

namespace App\Application\Calculators;

use App\Domain\Factories\AnswerCheckerFactory;
use Illuminate\Support\Collection;

class ExamPerformanceCalculator
{
    public function __construct(
        private AnswerCheckerFactory $answerCheckerFactory
    ) {}

    /**
     * Calculate exam performance metrics
     * 
     * @param Collection $questions
     * @param Collection $answers
     * @return array
     */
    public function calculate(Collection $questions, Collection $answers): array
    {
        $totalQuestions = $questions->count();
        $answeredCount = $answers->count();
        $correctCount = 0;
        $earnedMarks = 0;
        $earnedXp = 0;
        $earnedCoins = 0;

        foreach ($answers as $answer) {
            $question = $questions->firstWhere('id', $answer->question_id);
            if (!$question) continue;

            $isCorrect = $this->answerCheckerFactory->check($question, $answer);
            
            if ($isCorrect) {
                $correctCount++;
                $earnedMarks += $question->marks ?? 0;
                $earnedXp += $question->xp ?? 0;
                $earnedCoins += $question->coins ?? 0;
            }
        }

        return [
            'earned_marks' => $earnedMarks,
            'earned_xp' => $earnedXp,
            'earned_coins' => $earnedCoins,
            'correct_answers' => $correctCount,
            'wrong_answers' => $answeredCount - $correctCount,
            'unanswered' => $totalQuestions - $answeredCount,
            'score_percentage' => $totalQuestions > 0 
                ? round(($correctCount / $totalQuestions) * 100, 2) 
                : 0,
        ];
    }
}

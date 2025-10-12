<?php

namespace App\Domain\Services\AnswerCheckers;

class ChoiceAnswerChecker implements AnswerCheckerInterface
{
    /**
     * Check if choice/true-false answer is correct
     * All correct options must be selected, no incorrect options
     */
    public function check($question, $answer): bool
    {
        $correctOptionIds = $question->options
            ->where('is_correct', true)
            ->pluck('id')
            ->sort()
            ->values();

        $selectedOptionIds = $answer->selections
            ->pluck('option_id')
            ->sort()
            ->values();
        
        return $correctOptionIds->toArray() === $selectedOptionIds->toArray();
    }
}

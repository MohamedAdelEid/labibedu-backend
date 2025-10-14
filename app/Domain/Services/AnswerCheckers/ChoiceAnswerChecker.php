<?php

namespace App\Domain\Services\AnswerCheckers;

class ChoiceAnswerChecker implements AnswerCheckerInterface
{
    /**
     * Check if multiple-choice answer is correct
     * Checks option_id against the correct option
     */
    public function check($question, $answer): bool
    {
        if (!$answer->option_id) {
            return false;
        }

        $correctOption = $question->options->where('is_correct', true)->first();

        if (!$correctOption) {
            return false;
        }

        return $answer->option_id === $correctOption->id;
    }
}

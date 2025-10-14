<?php

namespace App\Domain\Services\AnswerCheckers;

class TrueFalseAnswerChecker implements AnswerCheckerInterface
{
    /**
     * Check if true/false answer is correct
     * Checks true_false_answer field against the correct option's is_correct value
     */
    public function check($question, $answer): bool
    {
        if ($answer->true_false_answer === null) {
            return false;
        }

        $correctOption = $question->options->where('is_correct', true)->first();
        
        if (!$correctOption) {
            return false;
        }
        
        // Compare the student's boolean answer with the correct option's is_correct value
        return $answer->true_false_answer === $correctOption->is_correct;
    }
}

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

        // For true/false questions, there's only one option representing "True"
        // The is_correct value of this option indicates the correct answer:
        // - is_correct = true means the correct answer is "True"
        // - is_correct = false means the correct answer is "False"
        $option = $question->options->first();
        
        if (!$option) {
            return false;
        }
        
        // Compare the student's boolean answer with the option's is_correct value
        // If option.is_correct = true, correct answer is true
        // If option.is_correct = false, correct answer is false
        return $answer->true_false_answer === $option->is_correct;
    }
}

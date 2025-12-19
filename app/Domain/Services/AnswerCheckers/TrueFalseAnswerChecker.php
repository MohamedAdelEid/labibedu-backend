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
        $option = $question->options->first();

        if (!$option) {
            return false;
        }

        // Compare the student's boolean answer with the option's is_correct value
        return $answer->true_false_answer === $option->is_correct;
    }
}

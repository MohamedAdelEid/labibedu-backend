<?php

namespace App\Domain\Services\AnswerCheckers;

class WrittenAnswerChecker implements AnswerCheckerInterface
{
    /**
     * Check if written answer is correct
     * Requires manual grading by teacher
     */
    public function check($question, $answer): bool
    {
        return $answer->grade?->is_correct ?? false;
    }
}

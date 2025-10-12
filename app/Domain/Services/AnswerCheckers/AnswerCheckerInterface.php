<?php

namespace App\Domain\Services\AnswerCheckers;

interface AnswerCheckerInterface
{
    /**
     * Check if the student's answer is correct
     * 
     * @param mixed $question The question model with options
     * @param mixed $answer The answer model with selections
     * @return bool
     */
    public function check($question, $answer): bool;
}

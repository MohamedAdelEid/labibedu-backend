<?php

namespace App\Domain\Services\AnswerCheckers;

class ConnectAnswerChecker implements AnswerCheckerInterface
{
    /**
     * Check if connect answer is correct
     * All pairs must match the correct pairs defined in question_option_pairs
     */
    public function check($question, $answer): bool
    {
        $studentPairs = $answer->pairs;

        if ($studentPairs->isEmpty()) {
            return false;
        }

        // Get correct pairs from question_option_pairs table
        $correctPairs = $question->optionPairs;

        if (empty($correctPairs) || $correctPairs->isEmpty()) {
            return false;
        }

        // Check if counts match
        if ($studentPairs->count() !== $correctPairs->count()) {
            return false;
        }

        // Check each student pair against correct pairs
        foreach ($studentPairs as $studentPair) {
            $matchFound = $correctPairs->contains(function ($correctPair) use ($studentPair) {
                return $correctPair->left_option_id === $studentPair->left_option_id
                    && $correctPair->right_option_id === $studentPair->right_option_id;
            });

            if (!$matchFound) {
                return false;
            }
        }

        return true;
    }
}

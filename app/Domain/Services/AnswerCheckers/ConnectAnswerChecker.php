<?php

namespace App\Domain\Services\AnswerCheckers;

class ConnectAnswerChecker implements AnswerCheckerInterface
{
    /**
     * Check if connect answer is correct
     * All pairs must match correctly
     */
    public function check($question, $answer): bool
    {
        $selections = $answer->selections->sortBy('order');
        $pairs = $selections->chunk(2);

        foreach ($pairs as $pair) {
            if ($pair->count() !== 2) {
                return false;
            }
            
            $leftOption = $question->options->firstWhere('id', $pair->first()->option_id);
            $rightOptionId = $pair->last()->option_id;

            if (!$leftOption || $leftOption->match_id !== $rightOptionId) {
                return false;
            }
        }

        return true;
    }
}

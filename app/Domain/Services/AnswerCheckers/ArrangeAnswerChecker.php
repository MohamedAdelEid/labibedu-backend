<?php

namespace App\Domain\Services\AnswerCheckers;

class ArrangeAnswerChecker implements AnswerCheckerInterface
{
    /**
     * Check if arrange answer is correct
     * All options must be in correct order
     */
    public function check($question, $answer): bool
    {
        $selections = $answer->selections->sortBy('order');
        
        foreach ($selections as $index => $selection) {
            $option = $question->options->firstWhere('id', $selection->option_id);
            
            if (!$option || $option->arrange_order !== ($index + 1)) {
                return false;
            }
        }

        return true;
    }
}

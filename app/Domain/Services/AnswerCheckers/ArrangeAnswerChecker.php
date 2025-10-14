<?php

namespace App\Domain\Services\AnswerCheckers;

class ArrangeAnswerChecker implements AnswerCheckerInterface
{
    /**
     * Check if arrange answer is correct
     * The order must exactly match the correct arrange_order
     */
    public function check($question, $answer): bool
    {
        $studentOrders = $answer->orders->sortBy('order');

        if ($studentOrders->isEmpty()) {
            return false;
        }

        // Get correct order from question options
        $correctOrder = $question->options
            ->sortBy('arrange_order')
            ->pluck('id')
            ->toArray();

        if (empty($correctOrder)) {
            return false;
        }

        // Get student's order
        $studentOrder = $studentOrders->pluck('option_id')->toArray();

        // Check if counts match
        if (count($studentOrder) !== count($correctOrder)) {
            return false;
        }

        // Check if order matches exactly
        return $studentOrder === $correctOrder;
    }
}

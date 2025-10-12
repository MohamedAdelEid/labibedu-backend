<?php

namespace App\Domain\Factories;

use App\Domain\Services\AnswerCheckers\AnswerCheckerInterface;
use App\Domain\Services\AnswerCheckers\ChoiceAnswerChecker;
use App\Domain\Services\AnswerCheckers\ConnectAnswerChecker;
use App\Domain\Services\AnswerCheckers\ArrangeAnswerChecker;
use App\Domain\Services\AnswerCheckers\WrittenAnswerChecker;
use App\Domain\Enums\QuestionType;

class AnswerCheckerFactory
{
    private array $checkers = [];

    public function __construct(
        ChoiceAnswerChecker $choiceChecker,
        ConnectAnswerChecker $connectChecker,
        ArrangeAnswerChecker $arrangeChecker,
        WrittenAnswerChecker $writtenChecker
    ) {
        $this->checkers[QuestionType::CHOICE->value] = $choiceChecker;
        $this->checkers[QuestionType::TRUE_FALSE->value] = $choiceChecker;
        $this->checkers[QuestionType::CONNECT->value] = $connectChecker;
        $this->checkers[QuestionType::ARRANGE->value] = $arrangeChecker;
        $this->checkers[QuestionType::WRITTEN->value] = $writtenChecker;
    }

    /**
     * Check if an answer is correct based on question type
     * 
     * @param mixed $question
     * @param mixed $answer
     * @return bool
     */
    public function check($question, $answer): bool
    {
        $type = $question->type instanceof QuestionType 
            ? $question->type->value 
            : $question->type;

        $checker = $this->checkers[$type] ?? null;

        if (!$checker) {
            return false;
        }

        return $checker->check($question, $answer);
    }
}

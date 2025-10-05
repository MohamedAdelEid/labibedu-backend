<?php

namespace App\Domain\Enums;

enum QuestionType: string
{
    case CHOICE = 'choice';
    case TRUE_FALSE = 'true_false';
    case CONNECT = 'connect';
    case ARRANGE = 'arrange';
    case WRITTEN = 'written';
}
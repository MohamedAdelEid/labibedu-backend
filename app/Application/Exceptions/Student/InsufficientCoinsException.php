<?php

namespace App\Application\Exceptions\Student;

use App\Application\Exceptions\BaseException;

class InsufficientCoinsException extends BaseException
{
    protected function getDefaultMessage(): string
    {
        return __('exceptions.student.insufficient_coins');
    }

    protected function getDefaultStatusCode(): int
    {
        return 400;
    }
}

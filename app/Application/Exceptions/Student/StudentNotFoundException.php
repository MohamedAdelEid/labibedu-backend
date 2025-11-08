<?php

namespace App\Application\Exceptions\Student;

use App\Application\Exceptions\BaseException;

class StudentNotFoundException extends BaseException
{
    protected function getDefaultMessage(): string
    {
        return __('exceptions.student.not_found');
    }

    protected function getDefaultStatusCode(): int
    {
        return 404;
    }
}


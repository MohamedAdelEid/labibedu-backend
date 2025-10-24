<?php

namespace App\Application\Exceptions\Auth;

use App\Application\Exceptions\BaseException;

class InvalidTokenException extends BaseException
{
    protected function getDefaultMessage(): string
    {
        return __('exceptions.auth.invalid_token');
    }

    protected function getDefaultStatusCode(): int
    {
        return 401;
    }
}

<?php

namespace App\Application\Exceptions\Auth;

use App\Application\Exceptions\BaseException;

class InvalidCredentialsException extends BaseException
{
    protected function getDefaultMessage(): string
    {
        return __('exceptions.auth.invalid_credentials');
    }

    protected function getDefaultStatusCode(): int
    {
        return 401;
    }
}

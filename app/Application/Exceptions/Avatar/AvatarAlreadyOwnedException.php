<?php

namespace App\Application\Exceptions\Avatar;

use App\Application\Exceptions\BaseException;

class AvatarAlreadyOwnedException extends BaseException
{
    protected function getDefaultMessage(): string
    {
        return __('exceptions.avatar.already_owned');
    }

    protected function getDefaultStatusCode(): int
    {
        return 400;
    }
}

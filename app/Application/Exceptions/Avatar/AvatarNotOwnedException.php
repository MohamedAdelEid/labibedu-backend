<?php

namespace App\Application\Exceptions\Avatar;

use App\Application\Exceptions\BaseException;

class AvatarNotOwnedException extends BaseException
{
    protected function getDefaultMessage(): string
    {
        return __('exceptions.avatar.not_owned');
    }

    protected function getDefaultStatusCode(): int
    {
        return 403;
    }
}

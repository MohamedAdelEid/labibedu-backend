<?php

namespace App\Application\Exceptions\Avatar;

use App\Application\Exceptions\BaseException;

class AvatarNotFoundException extends BaseException
{
    protected function getDefaultMessage(): string
    {
        return __('exceptions.avatar.not_found');
    }

    protected function getDefaultStatusCode(): int
    {
        return 404;
    }
}

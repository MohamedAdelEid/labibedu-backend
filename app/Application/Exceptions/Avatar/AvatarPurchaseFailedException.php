<?php

namespace App\Application\Exceptions\Avatar;

use App\Application\Exceptions\BaseException;

class AvatarPurchaseFailedException extends BaseException
{
    protected function getDefaultMessage(): string
    {
        return __('exceptions.avatar.purchase_failed');
    }

    protected function getDefaultStatusCode(): int
    {
        return 400;
    }
}

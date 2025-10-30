<?php

namespace App\Application\Exceptions\Book;

use App\Application\Exceptions\BaseException;

class PageNotFoundException extends BaseException
{
    protected function getDefaultMessage(): string
    {
        return 'Page not found.';
    }

    protected function getDefaultStatusCode(): int
    {
        return 404;
    }
}


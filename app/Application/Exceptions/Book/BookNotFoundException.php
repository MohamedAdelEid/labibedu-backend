<?php

namespace App\Application\Exceptions\Book;

use App\Application\Exceptions\BaseException;

class BookNotFoundException extends BaseException
{
    protected function getDefaultMessage(): string
    {
        return 'Book not found.';
    }

    protected function getDefaultStatusCode(): int
    {
        return 404;
    }
}


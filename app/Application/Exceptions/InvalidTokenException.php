<?php

namespace App\Application\Exceptions;

use Exception;

class InvalidTokenException extends Exception
{
    protected $code = 401;
}
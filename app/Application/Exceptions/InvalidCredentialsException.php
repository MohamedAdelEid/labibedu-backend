<?php

namespace App\Application\Exceptions;

use Exception;

class InvalidCredentialsException extends Exception
{
    protected $code = 401;
}
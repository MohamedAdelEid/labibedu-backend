<?php

namespace App\Domain\Enums;

enum ActivityType: string
{
    case LOGIN = 'login';
    case API_REQUEST = 'api_request';
    case SESSION_END = 'session_end';
}

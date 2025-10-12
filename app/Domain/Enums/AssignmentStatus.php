<?php

namespace App\Domain\Enums;

enum AssignmentStatus: string
{
    case NOT_SUBMITTED = 'not_submitted';
    case PENDING = 'pending';
    case COMPLETED = 'completed';
}

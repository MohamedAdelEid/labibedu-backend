<?php

namespace App\Domain\Enums;

enum AssignmentStatus: string
{
    case NOT_STARTED = 'not_started';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case NOT_SUBMITTED = 'not_submitted';
}
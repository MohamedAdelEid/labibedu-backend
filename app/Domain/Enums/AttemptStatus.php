<?php

namespace App\Domain\Enums;

enum AttemptStatus: string
{
    case IN_PROGRESS = 'in_progress';
    case FINISHED = 'finished';
}
<?php

namespace App\Domain\Enums;

enum JourneyStageStatus: string
{
    case COMPLETED = 'COMPLETED';
    case CURRENT = 'CURRENT';
    case LOCKED = 'LOCKED';
}


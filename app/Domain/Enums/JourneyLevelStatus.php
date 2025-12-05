<?php

namespace App\Domain\Enums;

enum JourneyLevelStatus: string
{
    case LOCKED = 'LOCKED';
    case UNLOCKED = 'UNLOCKED';
}


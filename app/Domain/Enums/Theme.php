<?php

namespace App\Domain\Enums;

enum Theme: string
{
    case MINIMAL = 'minimal';
    case BLUE = 'blue';
    case PINK = 'pink';

    public function label(): string
    {
        return match ($this) {
            self::MINIMAL => 'Minimal',
            self::BLUE => 'Blue',
            self::PINK => 'Pink',
        };
    }
}


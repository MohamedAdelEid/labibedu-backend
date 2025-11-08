<?php

namespace App\Domain\Enums;

enum Language: string
{
    case ARABIC = 'ar';
    case ENGLISH = 'en';

    public function label(): string
    {
        return match ($this) {
            self::ARABIC => 'Arabic',
            self::ENGLISH => 'English',
        };
    }
}


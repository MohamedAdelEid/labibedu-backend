<?php

namespace App\Domain\Enums;

enum QuestionLanguage: string
{
    case ARABIC = 'ar';
    case ENGLISH = 'en';

    public function isRTL(): bool
    {
        return $this === self::ARABIC;
    }
}
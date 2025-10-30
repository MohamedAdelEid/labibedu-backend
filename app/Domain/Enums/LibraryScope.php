<?php

namespace App\Domain\Enums;

enum LibraryScope: string
{
    case ALL = 'all';
    case MINE = 'mine';
    case FAVORITE = 'fav';

    /**
     * Get all valid scope values as an array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Try to create enum from string with alias support
     * Also accepts 'favorite' as an alias for 'fav'
     */
    public static function fromRequest(string $value): self
    {
        // Handle alias 'favorite' -> 'fav'
        if ($value === 'favorite') {
            $value = 'fav';
        }

        return self::tryFrom($value) ?? self::ALL;
    }
}


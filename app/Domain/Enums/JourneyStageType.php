<?php

namespace App\Domain\Enums;

enum JourneyStageType: string
{
    case EXAM_TRAIN = 'EXAM_TRAIN';
    case BOOK = 'BOOK';
    case VIDEO = 'VIDEO';

    public static function fromDatabaseType(string $type): self
    {
        return match ($type) {
            'examTraining' => self::EXAM_TRAIN,
            'book' => self::BOOK,
            'video' => self::VIDEO,
            default => throw new \ValueError("Unknown stage type: {$type}"),
        };
    }

    public function toDatabaseType(): string
    {
        return match ($this) {
            self::EXAM_TRAIN => 'examTraining',
            self::BOOK => 'book',
            self::VIDEO => 'video',
        };
    }
}


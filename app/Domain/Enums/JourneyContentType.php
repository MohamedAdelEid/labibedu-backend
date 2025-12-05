<?php

namespace App\Domain\Enums;

enum JourneyContentType: string
{
    case VIDEO = 'video';
    case EXAM = 'exam';
    case TRAINING = 'training';
    case BOOK = 'book';

    public static function fromDatabaseType(string $type, ?bool $isExam = null): self
    {
        if ($type === 'examTraining' && $isExam !== null) {
            return $isExam ? self::EXAM : self::TRAINING;
        }

        return match ($type) {
            'book' => self::BOOK,
            'video' => self::VIDEO,
            'examTraining' => self::EXAM,
            default => throw new \ValueError("Unknown content type: {$type}"),
        };
    }
}


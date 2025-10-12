<?php

namespace App\Domain\Enums;

enum AssignmentType: string
{
    case EXAM = 'exam';
    case TRAINING = 'training';
    case VIDEO = 'video';
    case BOOK = 'book';

    public function isExamOrTraining(): bool
    {
        return in_array($this, [self::EXAM, self::TRAINING]);
    }

    public function isVideo(): bool
    {
        return $this === self::VIDEO;
    }

    public function isBook(): bool
    {
        return $this === self::BOOK;
    }
}

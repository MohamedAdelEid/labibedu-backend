<?php

namespace App\Domain\Enums;

enum AssignmentType: string
{
    case EXAM = 'examTraining';
    case VIDEO = 'video';
    case BOOK = 'book';

    public function isExam(): bool
    {
        return $this === self::EXAM;
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

<?php

namespace App\Domain\Enums;

enum AssignmentType: string
{
    case EXAM_TRAINING = 'examTraining';
    case VIDEO = 'video';
    case BOOK = 'book';

    public function isExamTraining(): bool
    {
        return $this === self::EXAM_TRAINING;
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

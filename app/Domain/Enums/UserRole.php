<?php

namespace App\Domain\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case STUDENT = 'student';
    case TEACHER = 'teacher';

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrator',
            self::STUDENT => 'Student',
            self::TEACHER => 'Teacher',
        };
    }
}
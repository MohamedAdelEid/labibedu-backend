<?php

namespace App\Application\DTOs\Exam;

readonly class SendHeartbeatDTO
{
    public function __construct(
        public int $examId,
        public int $studentId,
        public int $remainingSeconds,
    ) {
    }
}

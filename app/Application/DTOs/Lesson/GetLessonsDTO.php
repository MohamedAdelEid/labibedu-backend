<?php

namespace App\Application\DTOs\Lesson;

class GetLessonsDTO
{
    public function __construct(
        public readonly int $studentId,
        public readonly int $subjectId,
        public readonly ?string $search = null,
        public readonly int $page = 1,
        public readonly int $perPage = 10,
    ) {
    }

    public static function fromRequest(array $data, int $studentId): self
    {
        return new self(
            studentId: $studentId,
            subjectId: $data['subject_id'],
            search: $data['search'] ?? null,
            page: $data['page'] ?? 1,
            perPage: $data['per_page'] ?? 10,
        );
    }
}


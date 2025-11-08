<?php

namespace App\Application\DTOs\Student;

class FirstSetupDTO
{
    public function __construct(
        public readonly string $name,
        public readonly int $ageGroupId,
        public readonly string $gender,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            ageGroupId: $data['age_group_id'],
            gender: $data['gender'],
        );
    }
}


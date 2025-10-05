<?php

namespace App\Application\DTOs\Auth;

class ForgetPasswordDTO
{
    public function __construct(
        public readonly string $email,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            email: $data['email'],
        );
    }
}
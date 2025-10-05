<?php

namespace App\Application\DTOs\Auth;

class LoginDTO
{
    public function __construct(
        public readonly string $userName,
        public readonly string $password,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            userName: $data['user_name'],
            password: $data['password'],
        );
    }
}
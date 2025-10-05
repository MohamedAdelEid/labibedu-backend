<?php

namespace App\Application\DTOs\Auth;    

class ConfirmOtpDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $otp,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            email: $data['email'],
            otp: $data['otp'],
        );
    }
}
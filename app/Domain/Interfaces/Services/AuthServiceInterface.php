<?php

namespace App\Domain\Interfaces\Services;

use App\Application\DTOs\Auth\LoginDTO;
use App\Application\DTOs\Auth\ForgetPasswordDTO;
use App\Application\DTOs\Auth\ResetPasswordDTO;
use App\Application\DTOs\Auth\ConfirmOtpDTO;

interface AuthServiceInterface
{
    public function login(LoginDTO $dto, ?string $ipAddress = null, ?string $userAgent = null): array;
    public function refresh(string $refreshToken): array;
    public function logout(): bool;
    public function forgetPassword(ForgetPasswordDTO $dto): bool;
    public function resetPassword(ResetPasswordDTO $dto): bool;
    public function confirmOtp(ConfirmOtpDTO $dto, ?string $ipAddress = null, ?string $userAgent = null): array;
    public function resendOtp(string $email): bool;
}
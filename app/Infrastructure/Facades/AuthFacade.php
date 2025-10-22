<?php

namespace App\Infrastructure\Facades;

use App\Application\DTOs\Auth\LoginDTO;
use App\Application\DTOs\Auth\ForgetPasswordDTO;
use App\Application\DTOs\Auth\ResetPasswordDTO;
use App\Application\DTOs\Auth\ConfirmOtpDTO;
use App\Domain\Interfaces\Services\AuthServiceInterface;

class AuthFacade
{
    public function __construct(
        private AuthServiceInterface $authService
    ) {
    }

    public function login(LoginDTO $dto, ?string $ipAddress = null, ?string $userAgent = null): array
    {
        return $this->authService->login($dto, $ipAddress, $userAgent);
    }

    public function refresh(string $refreshToken): array
    {
        return $this->authService->refresh($refreshToken);
    }

    public function logout(): bool
    {
        return $this->authService->logout();
    }

    public function forgetPassword(ForgetPasswordDTO $dto): bool
    {
        return $this->authService->forgetPassword($dto);
    }

    public function resetPassword(ResetPasswordDTO $dto): bool
    {
        return $this->authService->resetPassword($dto);
    }

    public function confirmOtp(ConfirmOtpDTO $dto, ?string $ipAddress = null, ?string $userAgent = null): array
    {
        return $this->authService->confirmOtp($dto, $ipAddress, $userAgent);
    }

    public function resendOtp(string $email): bool
    {
        return $this->authService->resendOtp($email);
    }
}
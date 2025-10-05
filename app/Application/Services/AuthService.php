<?php

namespace App\Application\Services;

use App\Application\DTOs\Auth\LoginDTO;
use App\Application\DTOs\Auth\ForgetPasswordDTO;
use App\Application\DTOs\Auth\ResetPasswordDTO;
use App\Application\DTOs\Auth\ConfirmOtpDTO;
use App\Application\Exceptions\InvalidCredentialsException;
use App\Application\Exceptions\InvalidTokenException;
use App\Domain\Interfaces\Repositories\UserRepositoryInterface;
use App\Domain\Interfaces\Services\AuthServiceInterface;
use App\Infrastructure\Models\Otp;
use App\Infrastructure\Models\PasswordResetTokens;
use App\Presentation\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function login(LoginDTO $dto): array
    {
        $user = $this->userRepository->findByUserName($dto->userName);

        if (!$user || !Hash::check($dto->password, $user->password)) {
            throw new InvalidCredentialsException(__('auth.failed'));
        }

        $accessToken = JWTAuth::fromUser($user);
        $refreshToken = $this->generateRefreshToken($user);

        return [
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken,
            'expiresIn' => (int) config('jwt.ttl') * 60,
            'user' => new UserResource($user),
        ];
    }

    public function refresh(string $refreshToken): array
    {
        $user = $this->validateRefreshToken($refreshToken);

        if (!$user) {
            throw new InvalidTokenException(__('auth.invalid_token'));
        }

        $newAccessToken = JWTAuth::fromUser($user);
        $newRefreshToken = $this->generateRefreshToken($user);

        // Invalidate old refresh token
        $user->refresh_token = null;
        $user->save();

        return [
            'accessToken' => $newAccessToken,
            'refreshToken' => $newRefreshToken,
            'expiresIn' => (int) config('jwt.ttl') * 60,
        ];
    }

    public function logout(): bool
    {
        $user = Auth::user();

        if ($user) {
            $user->refresh_token = null;
            $user->save();
        }

        JWTAuth::invalidate(JWTAuth::getToken());

        return true;
    }

    public function forgetPassword(ForgetPasswordDTO $dto): bool
    {
        $user = $this->userRepository->findByEmail($dto->email);

        if (!$user) {
            return false;
        }

        $token = Str::random(64);

        // <CHANGE> Use user_id instead of email
        PasswordResetTokens::updateOrCreate(
            ['user_id' => $user->id],
            [
                'token' => Hash::make($token),
                'created_at' => Carbon::now(),
            ]
        );

        // Send email with reset link
        Mail::send('emails.reset-password', ['token' => $token, 'email' => $dto->email], function ($message) use ($dto) {
            $message->to($dto->email);
            $message->subject(__('auth.reset_password_subject'));
        });

        return true;
    }

    public function resetPassword(ResetPasswordDTO $dto): bool
    {
        $user = $this->userRepository->findByEmail($dto->email);

        if (!$user) {
            throw new InvalidCredentialsException(__('auth.user_not_found'));
        }

        // <CHANGE> Use user_id instead of email
        $passwordReset = PasswordResetTokens::where('user_id', $user->id)->first();

        if (!$passwordReset || !Hash::check($dto->token, $passwordReset->token)) {
            throw new InvalidTokenException(__('auth.invalid_token'));
        }

        // Check if token is expired (24 hours)
        if (Carbon::parse($passwordReset->created_at)->addHours(24)->isPast()) {
            throw new InvalidTokenException(__('auth.token_expired'));
        }

        $user->password = Hash::make($dto->password);
        $user->save();

        PasswordResetTokens::where('user_id', $user->id)->delete();

        return true;
    }

    public function confirmOtp(ConfirmOtpDTO $dto): array
    {
        $user = $this->userRepository->findByEmail($dto->email);

        if (!$user) {
            throw new InvalidCredentialsException(__('auth.user_not_found'));
        }

        // <CHANGE> Use user_id instead of email
        $otp = Otp::where('user_id', $user->id)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otp || !Hash::check($dto->otp, $otp->otp)) {
            throw new InvalidTokenException(__('auth.invalid_otp'));
        }

        // Mark email as verified if needed
        if (!$user->email_verified_at) {
            $user->email_verified_at = Carbon::now();
            $user->save();
        }

        // Delete used OTP
        $otp->delete();

        // Return login data
        $accessToken = JWTAuth::fromUser($user);
        $refreshToken = $this->generateRefreshToken($user);

        return [
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken,
            'expiresIn' => (int) config('jwt.ttl') * 60,
            'user' => new UserResource($user),
        ];
    }

    public function resendOtp(string $email): bool
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            return false;
        }

        $otpCode = rand(100000, 999999);

        // <CHANGE> Use user_id instead of email
        Otp::updateOrCreate(
            ['user_id' => $user->id],
            [
                'otp' => Hash::make($otpCode),
                'expires_at' => Carbon::now()->addMinutes((int) config('auth.otp_expiration_minutes', 10)),
            ]
        );

        // Send OTP via email
        Mail::send('emails.otp', ['otp' => $otpCode], function ($message) use ($email) {
            $message->to($email);
            $message->subject(__('auth.otp_subject'));
        });

        return true;
    }

    private function generateRefreshToken($user): string
    {
        $refreshToken = Str::random(64);
        $user->refresh_token = Hash::make($refreshToken);
        $user->save();

        return $refreshToken;
    }

    private function validateRefreshToken(string $token)
    {
        $users = $this->userRepository->all();

        foreach ($users as $user) {
            if ($user->refresh_token && Hash::check($token, $user->refresh_token)) {
                return $user;
            }
        }

        return null;
    }
}
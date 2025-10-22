<?php

namespace App\Presentation\Http\Controllers\Api;

use App\Application\DTOs\Auth\ConfirmOtpDTO;
use App\Application\DTOs\Auth\ForgetPasswordDTO;
use App\Application\DTOs\Auth\LoginDTO;
use App\Application\DTOs\Auth\ResetPasswordDTO;
use App\Application\Services\CookieService;
use App\Infrastructure\Facades\AuthFacade;
use App\Infrastructure\Helpers\ApiResponse;
use App\Presentation\Http\Requests\Auth\ConfirmOtpRequest;
use App\Presentation\Http\Requests\Auth\ForgetPasswordRequest;
use App\Presentation\Http\Requests\Auth\LoginRequest;
use App\Presentation\Http\Requests\Auth\RefreshTokenRequest;
use App\Presentation\Http\Requests\Auth\ResetPasswordRequest;
use App\Presentation\Http\Requests\Auth\ResendOtpRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private AuthFacade $authFacade,
        private CookieService $cookieService
    ) {
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $dto = LoginDTO::fromRequest($request->validated());
        $result = $this->authFacade->login($dto, $request->ip(), $request->userAgent());

        $cookie = $this->cookieService->createRefreshTokenCookie($result['refreshToken']);

        return ApiResponse::success([
            'accessToken' => $result['accessToken'],
            'expiresIn' => $result['expiresIn'],
            'user' => $result['user'],
        ], __('auth.login_success'))->withCookie($cookie);
    }

    public function refresh(RefreshTokenRequest $request): JsonResponse
    {
        /** @var Request $request */
        $refreshToken = $request->cookie('refresh_token');

        if (!$refreshToken) {
            return ApiResponse::error(__('auth.no_refresh_token'), null, 401);
        }

        $result = $this->authFacade->refresh($refreshToken);

        $cookie = $this->cookieService->createRefreshTokenCookie($result['refreshToken']);

        return ApiResponse::success([
            'accessToken' => $result['accessToken'],
            'expiresIn' => $result['expiresIn'],
        ], __('auth.token_refreshed'))->withCookie($cookie);
    }

    public function logout(): JsonResponse
    {
        $this->authFacade->logout();

        $cookie = $this->cookieService->forgetRefreshTokenCookie();

        return ApiResponse::success(null, __('auth.logout_success'))->withCookie($cookie);
    }

    public function forgetPassword(ForgetPasswordRequest $request): JsonResponse
    {
        $dto = ForgetPasswordDTO::fromRequest($request->validated());
        $this->authFacade->forgetPassword($dto);

        return ApiResponse::success(null, __('auth.reset_link_sent'));
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $dto = ResetPasswordDTO::fromRequest($request->validated());
        $this->authFacade->resetPassword($dto);

        return ApiResponse::success(null, __('auth.password_reset_success'));
    }

    public function confirmOtp(ConfirmOtpRequest $request): JsonResponse
    {
        $dto = ConfirmOtpDTO::fromRequest($request->validated());
        $result = $this->authFacade->confirmOtp($dto, $request->ip(), $request->userAgent());

        $cookie = $this->cookieService->createRefreshTokenCookie($result['refreshToken']);

        return ApiResponse::success([
            'accessToken' => $result['accessToken'],
            'expiresIn' => $result['expiresIn'],
            'user' => $result['user'],
        ], __('auth.otp_confirmed'))->withCookie($cookie);
    }

    public function resendOtp(ResendOtpRequest $request): JsonResponse
    {
        $this->authFacade->resendOtp($request->validated()['email']);

        return ApiResponse::success(null, __('auth.otp_resent'));
    }
}
<?php

namespace App\Application\Services;

use Symfony\Component\HttpFoundation\Cookie;

class CookieService
{
    public function createRefreshTokenCookie(string $refreshToken): Cookie
    {
        return cookie(
            'refresh_token',
            $refreshToken,
            (int) config('jwt.refresh_ttl'),
            '/',
            null,
            config('session.secure', true),
            true,
            false,
            'strict'
        );
    }

    public function forgetRefreshTokenCookie(): Cookie
    {
        return cookie()->forget('refresh_token');
    }
}
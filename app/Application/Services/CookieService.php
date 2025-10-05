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
            config('session.secure', true), // Secure in production
            true, // HttpOnly
            false,
            'strict' // SameSite
        );
    }

    public function forgetRefreshTokenCookie(): Cookie
    {
        return cookie()->forget('refresh_token');
    }
}
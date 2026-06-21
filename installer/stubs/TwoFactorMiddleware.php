<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->hasTwoFactorEnabled()) {
            $sessionKey = 'two_factor_verified_at_'.$user->id;

            if (! $request->session()->has($sessionKey)) {
                return redirect()->route('filament.admin.auth.two-factor-challenge');
            }
        }

        return $next($request);
    }
}

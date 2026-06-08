<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorMiddleware
{
    /**
     * Handle an incoming request to ensure 2FA verification for admin users.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only apply to admin panel requests
        if (!Filament::getCurrentPanel() || Filament::getCurrentPanel()->getId() !== 'admin') {
            return $next($request);
        }

        $user = auth()->user();

        // Skip if not authenticated or user doesn't have 2FA enabled
        if (!$user || !$user instanceof User || !$user->hasTwoFactorEnabled()) {
            return $next($request);
        }

        // Skip if already verified in this session
        if (session()->get('two_factor_verified_at')) {
            return $next($request);
        }

        // Skip if already on the 2FA challenge page
        if ($request->routeIs('filament.admin.pages.two-factor-challenge')) {
            return $next($request);
        }

        // Store user in session and redirect to 2FA challenge
        session()->put('two_factor_pending_user', $user);
        
        // Log out the user temporarily to prevent access
        auth()->logout();

        return redirect()->route('filament.admin.pages.two-factor-challenge');
    }
}

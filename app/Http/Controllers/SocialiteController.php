<?php

namespace App\Http\Controllers;

use App\Enums\SocialiteProvider;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect(string $provider): RedirectResponse
    {
        $socialiteProvider = $this->resolveProvider($provider);

        /** @var RedirectResponse $response */
        $response = Socialite::driver($socialiteProvider->value)->redirect();

        return $response;
    }

    public function callback(string $provider): RedirectResponse
    {
        $socialiteProvider = $this->resolveProvider($provider);

        try {
            $socialUser = Socialite::driver($socialiteProvider->value)->user();
        } catch (\Exception) {
            return redirect('/')->with('error', 'Authentication was cancelled or failed.');
        }

        $email = $socialUser->getEmail();

        if (! $email) {
            return redirect('/')->with('error', 'Email address is required for authentication.');
        }

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'User',
                'socialite_id' => (string) $socialUser->getId(),
                'socialite_type' => $socialiteProvider->value,
                'email_verified_at' => now(),
            ],
        );

        Auth::login($user, remember: true);

        return redirect()->intended('/');
    }

    private function resolveProvider(string $provider): SocialiteProvider
    {
        $socialiteProvider = SocialiteProvider::tryFrom($provider);

        if (! $socialiteProvider || ! $socialiteProvider->isConfigured()) {
            abort(404);
        }

        return $socialiteProvider;
    }
}

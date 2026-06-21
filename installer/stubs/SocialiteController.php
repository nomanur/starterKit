<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Enums\SocialiteProvider;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController
{
    public function redirect(string $provider)
    {
        $providerEnum = SocialiteProvider::from($provider);

        if (! $providerEnum->isConfigured()) {
            return redirect()->route('login')->withErrors([
                'email' => "{$providerEnum->label()} login is not configured.",
            ]);
        }

        session()->put('socialite_provider', $provider);

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider)
    {
        $providerEnum = SocialiteProvider::from($provider);

        if (! $providerEnum->isConfigured()) {
            return redirect()->route('login')->withErrors([
                'email' => "{$providerEnum->label()} login is not configured.",
            ]);
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Could not authenticate with '.$providerEnum->label().'. Please try again.',
            ]);
        }

        $user = User::where('email', $socialUser->getEmail())->first();

        if (! $user) {
            $name = $socialUser->getName() ?? $socialUser->getNickname() ?? $socialUser->getEmail();

            $user = User::create([
                'name' => $name,
                'email' => $socialUser->getEmail(),
                'password' => bcrypt(Str::password()),
                'socialite_id' => $socialUser->getId(),
                'socialite_type' => $providerEnum,
            ]);
        }

        if ($user->socialite_id === null) {
            $user->update([
                'socialite_id' => $socialUser->getId(),
                'socialite_type' => $providerEnum,
            ]);
        }

        Auth::login($user, true);

        return redirect()->intended('/admin');
    }
}

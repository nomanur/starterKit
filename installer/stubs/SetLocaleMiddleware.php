<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use DevRabbiul\GeoGenius\Services\GeoGenius;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    private const COUNTRY_LOCALE_MAP = [
        'BD' => 'bn',
        'IN' => 'hi',
        'US' => 'en',
        'GB' => 'en',
        'ES' => 'es',
        'MX' => 'es',
        'FR' => 'fr',
        'DE' => 'de',
        'IT' => 'it',
        'PT' => 'pt',
        'BR' => 'pt',
        'RU' => 'ru',
        'JP' => 'ja',
        'CN' => 'zh',
        'KR' => 'ko',
        'AE' => 'ar',
        'SA' => 'ar',
        'TR' => 'tr',
        'NL' => 'nl',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if ($locale = $request->session()->get('locale')) {
            App::setLocale($locale);

            return $next($request);
        }

        $locale = $this->detectLocale($request);

        App::setLocale($locale);

        return $next($request);
    }

    private function detectLocale(Request $request): string
    {
        try {
            $geoGenius = app(GeoGenius::class);
            $countryCode = $geoGenius->getCountryCode();

            if ($countryCode && isset(self::COUNTRY_LOCALE_MAP[$countryCode])) {
                return self::COUNTRY_LOCALE_MAP[$countryCode];
            }
        } catch (\Exception $e) {
            //
        }

        $browserLocale = $request->getPreferredLanguage();

        if ($browserLocale) {
            $locale = substr($browserLocale, 0, 2);

            if (in_array($locale, ['en', 'es', 'fr', 'de', 'it', 'pt', 'ru', 'ja', 'zh', 'ko', 'ar', 'tr', 'nl', 'bn', 'hi'])) {
                return $locale;
            }
        }

        return config('app.fallback_locale', 'en');
    }
}

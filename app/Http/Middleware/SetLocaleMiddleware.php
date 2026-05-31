<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    /**
     * Comprehensive map matching ISO country codes to their primary languages.
     * You can easily expand this array as your starter kit supports more regions.
     *
     * @var array<string, string>
     */
    protected array $countryToLocaleMap = [
        'BD' => 'bn', // Bangladesh -> Bengali
        'IN' => 'hi', // India -> Hindi
        'US' => 'en', // United States -> English
        'GB' => 'en', // United Kingdom -> English
        'CA' => 'en', // Canada -> English
        'AU' => 'en', // Australia -> English
        'ES' => 'es', // Spain -> Spanish
        'MX' => 'es', // Mexico -> Spanish
        'FR' => 'fr', // France -> French
        'DE' => 'de', // Germany -> German
        'IT' => 'it', // Italy -> Italian
        'BR' => 'pt', // Brazil -> Portuguese
        'PT' => 'pt', // Portugal -> Portuguese
        'RU' => 'ru', // Russia -> Russian
        'CN' => 'zh', // China -> Chinese
        'JP' => 'ja', // Japan -> Japanese
        'KR' => 'ko', // South Korea -> Korean
        'SA' => 'ar', // Saudi Arabia -> Arabic
        'AE' => 'ar', // United Arab Emirates -> Arabic
        'TR' => 'tr', // Turkey -> Turkish
        'NL' => 'nl', // Netherlands -> Dutch
        'ID' => 'id', // Indonesia -> Indonesian
    ];

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->has('locale')) {
            $locale = $request->session()->get('locale');
        } else {
            try {
                // Get the country code dynamically from your geo package
                $countryCode = strtoupper(laravelGeoGenius()->geo()->getCountryCode() ?? '');

                // Lookup the locale from the map, fallback to browser preference or app default
                $locale = $this->countryToLocaleMap[$countryCode]
                    ?? $request->getPreferredLanguage(config('app.supported_locales', ['en']))
                    ?? config('app.locale', 'en');
            } catch (\Throwable $e) {
                // Smart fallback to browser headers if geo service fails
                $locale = $request->getPreferredLanguage(config('app.supported_locales', ['en']))
                    ?? config('app.locale', 'en');
            }

            $request->session()->put('locale', $locale);
        }

        app()->setLocale($locale);

        return $next($request);
    }
}

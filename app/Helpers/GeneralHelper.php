<?php

namespace App\Helpers;

class GeneralHelper
{
    /**
     * Format a number with locale-aware separators.
     */
    public static function formatNumber(float|int $number, int $decimals = 0): string
    {
        return number_format((float) $number, $decimals, '.', ',');
    }

    /**
     * Format an amount as currency.
     */
    public static function formatMoney(float|int $amount, ?string $currency = null, ?string $locale = null): string
    {
        $currency = $currency ?? config('app.currency', 'USD');
        $locale = $locale ?? config('app.locale', 'en_US');

        $formatter = new \NumberFormatter($locale.'@currency='.$currency, \NumberFormatter::CURRENCY);

        return $formatter->formatCurrency((float) $amount, $currency);
    }

    /**
     * Calculate percentage safely.
     */
    public static function percentage(float|int $part, float|int $total, int $precision = 2): float
    {
        if ($total == 0) {
            return 0.0;
        }

        return round(($part / $total) * 100, $precision);
    }

    /**
     * Get the client's IP address.
     */
    public static function getClientIp(): ?string
    {
        $ipKeys = [
            'HTTP_CF_CONNECTING_IP', // Cloudflare
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'REMOTE_ADDR',
        ];

        foreach ($ipKeys as $key) {
            if (! empty($_SERVER[$key])) {
                $ip = explode(',', $_SERVER[$key])[0];
                if (filter_var(trim($ip), FILTER_VALIDATE_IP)) {
                    return trim($ip);
                }
            }
        }

        return $_SERVER['REMOTE_ADDR'] ?? null;
    }

    /**
     * Get the user agent string.
     */
    public static function getUserAgent(): ?string
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? null;
    }

    /**
     * Check if the request is from a mobile device.
     */
    public static function isMobile(): bool
    {
        $userAgent = self::getUserAgent();

        if (! $userAgent) {
            return false;
        }

        return (bool) preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $userAgent);
    }

    /**
     * Generate a random unique string.
     */
    public static function generateRandomString(int $length = 32): string
    {
        return bin2hex(random_bytes($length / 2));
    }

    /**
     * Parse and return video ID from YouTube or Vimeo URL.
     */
    public static function parseVideoId(string $url): ?string
    {
        // YouTube
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $url, $matches)) {
            return $matches[1];
        }

        // Vimeo
        if (preg_match('/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/(?:\w+\/)?|album\/(?:\d+\/)?video\/|)(\d+)/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Convert a hex color to RGB array.
     */
    public static function hexToRgb(string $hex): ?array
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) {
            $hex = str_repeat(substr($hex, 0, 1), 2).
                   str_repeat(substr($hex, 1, 1), 2).
                   str_repeat(substr($hex, 2, 1), 2);
        }

        if (strlen($hex) !== 6 || ! ctype_xdigit($hex)) {
            return null;
        }

        return [
            'r' => hexdec(substr($hex, 0, 2)),
            'g' => hexdec(substr($hex, 2, 2)),
            'b' => hexdec(substr($hex, 4, 2)),
        ];
    }

    /**
     * Get active class for navigation based on current route.
     */
    public static function isActiveRoute(array|string $routes, string $activeClass = 'active'): string
    {
        $routes = is_array($routes) ? $routes : [$routes];

        foreach ($routes as $route) {
            if (request()->routeIs($route) || request()->fullUrlIs($route)) {
                return $activeClass;
            }
        }

        return '';
    }

    /**
     * Sanitize input data.
     */
    public static function sanitize(mixed $data): mixed
    {
        if (is_array($data)) {
            return array_map([self::class, 'sanitize'], $data);
        }

        if (is_string($data)) {
            return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
        }

        return $data;
    }
}

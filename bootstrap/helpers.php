<?php

declare(strict_types=1);

use Illuminate\Support\Number;
use Illuminate\Support\Str;

if (! function_exists('formatDate')) {
    function formatDate(DateTimeInterface|string|null $date, string $format = 'M d, Y'): ?string
    {
        if ($date === null) {
            return null;
        }

        if (is_string($date)) {
            $date = new DateTimeImmutable($date);
        }

        return $date->format($format);
    }
}

if (! function_exists('formatCurrency')) {
    function formatCurrency(float|int $amount, ?string $currency = null, ?string $locale = null): string
    {
        return Number::currency($amount, $currency ?? 'USD', $locale ?? app()->getLocale());
    }
}

if (! function_exists('strLimit')) {
    function strLimit(?string $value, int $limit = 100, string $end = '...'): string
    {
        if ($value === null) {
            return '';
        }

        return Str::limit($value, $limit, $end);
    }
}

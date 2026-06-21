<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Format a date consistently based on application settings.
     */
    public static function format(mixed $date, string $format = 'M d, Y'): string
    {
        if (! $date) {
            return '';
        }

        return Carbon::parse($date)->format($format);
    }

    /**
     * Get human-readable time difference (e.g., "2 hours ago").
     */
    public static function timeAgo(mixed $timestamp): string
    {
        if (! $timestamp) {
            return '';
        }

        return Carbon::parse($timestamp)->diffForHumans();
    }

    /**
     * Convert a date to the user's timezone.
     */
    public static function toUserTimezone(mixed $date, ?string $timezone = null): Carbon
    {
        $timezone = $timezone ?? config('app.timezone');

        return Carbon::parse($date)->timezone($timezone);
    }

    /**
     * Check if a date is in the past.
     */
    public static function isPast(mixed $date): bool
    {
        return Carbon::parse($date)->isPast();
    }

    /**
     * Check if a date is in the future.
     */
    public static function isFuture(mixed $date): bool
    {
        return Carbon::parse($date)->isFuture();
    }

    /**
     * Get the age from a birth date.
     */
    public static function age(mixed $birthDate): int
    {
        return Carbon::parse($birthDate)->age;
    }
}

<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Format a date consistently based on application settings.
     *
     * @param mixed $date
     * @param string $format
     * @return string
     */
    public static function format($date, string $format = 'M d, Y'): string
    {
        if (!$date) {
            return '';
        }

        return Carbon::parse($date)->format($format);
    }

    /**
     * Get human-readable time difference (e.g., "2 hours ago").
     *
     * @param mixed $timestamp
     * @return string
     */
    public static function timeAgo($timestamp): string
    {
        if (!$timestamp) {
            return '';
        }

        return Carbon::parse($timestamp)->diffForHumans();
    }

    /**
     * Convert a date to the user's timezone.
     *
     * @param mixed $date
     * @param string|null $timezone
     * @return Carbon
     */
    public static function toUserTimezone($date, ?string $timezone = null): Carbon
    {
        $timezone = $timezone ?? config('app.timezone');
        return Carbon::parse($date)->timezone($timezone);
    }

    /**
     * Check if a date is in the past.
     *
     * @param mixed $date
     * @return bool
     */
    public static function isPast($date): bool
    {
        return Carbon::parse($date)->isPast();
    }

    /**
     * Check if a date is in the future.
     *
     * @param mixed $date
     * @return bool
     */
    public static function isFuture($date): bool
    {
        return Carbon::parse($date)->isFuture();
    }

    /**
     * Get the age from a birth date.
     *
     * @param mixed $birthDate
     * @return int
     */
    public static function age($birthDate): int
    {
        return Carbon::parse($birthDate)->age;
    }
}

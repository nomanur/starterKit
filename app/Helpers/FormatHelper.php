<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class FormatHelper
{
    /**
     * Strip HTML tags while allowing specific ones.
     */
    public static function cleanHtml(string $html, array $allowedTags = ['p', 'br', 'strong', 'em', 'a']): string
    {
        if (empty($html)) {
            return '';
        }

        $tagString = '<'.implode('><', $allowedTags).'>';

        return strip_tags($html, $tagString);
    }

    /**
     * Generate a unique slug from a title.
     */
    public static function generateSlug(string $title, string $separator = '-'): string
    {
        $slug = Str::slug($title, $separator);

        // Ensure uniqueness if needed (basic implementation)
        // In real apps, check against database here
        return $slug ?: 'untitled';
    }

    /**
     * Truncate text to a specified length with ellipsis.
     */
    public static function truncate(string $text, int $limit = 100, string $ending = '...'): string
    {
        if (strlen($text) <= $limit) {
            return $text;
        }

        return rtrim(substr($text, 0, $limit - strlen($ending))).$ending;
    }

    /**
     * Mask sensitive string data (e.g., emails, phone numbers).
     */
    public static function maskString(string $string, int $visibleChars = 3, string $maskChar = '*'): string
    {
        $length = strlen($string);

        if ($length <= $visibleChars) {
            return str_repeat($maskChar, $length);
        }

        $visiblePart = substr($string, 0, $visibleChars);
        $maskedPart = str_repeat($maskChar, $length - $visibleChars);

        return $visiblePart.$maskedPart;
    }

    /**
     * Extract plain text from HTML.
     */
    public static function htmlToText(string $html): string
    {
        return trim(strip_tags($html));
    }

    /**
     * Convert an array to a comma-separated string.
     */
    public static function arrayToString(array $array, string $glue = ', '): string
    {
        return implode($glue, $array);
    }
}

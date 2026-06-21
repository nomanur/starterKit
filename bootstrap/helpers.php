<?php

use App\Helpers\DateHelper;
use App\Helpers\FormatHelper;
use App\Helpers\GeneralHelper;
use App\Helpers\ImageHelper;
use App\Helpers\SeoHelper;

/**
 * Global Helper Functions
 *
 * These functions provide convenient shortcuts to the helper classes.
 * They are automatically loaded via composer.json autoload files.
 */
if (!function_exists("format_date")) {
    /**
     * Format a date consistently.
     */
    function format_date(mixed $date, string $format = "M d, Y"): string
    {
        return DateHelper::format($date, $format);
    }
}

if (!function_exists("time_ago")) {
    /**
     * Get human-readable time difference.
     */
    function time_ago(mixed $timestamp): string
    {
        return DateHelper::timeAgo($timestamp);
    }
}

if (!function_exists("clean_html")) {
    /**
     * Strip HTML tags while allowing specific ones.
     */
    function clean_html(
        string $html,
        array $allowedTags = ["p", "br", "strong", "em", "a"],
    ): string {
        return FormatHelper::cleanHtml($html, $allowedTags);
    }
}

if (!function_exists("generate_slug")) {
    /**
     * Generate a unique slug from a title.
     */
    function generate_slug(string $title, string $separator = "-"): string
    {
        return FormatHelper::generateSlug($title, $separator);
    }
}

if (!function_exists("truncate_text")) {
    /**
     * Truncate text to a specified length.
     */
    function truncate_text(
        string $text,
        int $limit = 100,
        string $ending = "...",
    ): string {
        return FormatHelper::truncate($text, $limit, $ending);
    }
}

if (!function_exists("mask_string")) {
    /**
     * Mask sensitive string data.
     */
    function mask_string(
        string $string,
        int $visibleChars = 3,
        string $maskChar = "*",
    ): string {
        return FormatHelper::maskString($string, $visibleChars, $maskChar);
    }
}

if (!function_exists("get_avatar")) {
    /**
     * Get user avatar URL with fallback.
     */
    function get_avatar(mixed $user, int $size = 100): string
    {
        return ImageHelper::getAvatar($user, $size);
    }
}

if (!function_exists("storage_url")) {
    /**
     * Get a safe storage URL for a file.
     */
    function storage_url(?string $path): string
    {
        return ImageHelper::storageUrl($path);
    }
}

if (!function_exists("file_size_human")) {
    /**
     * Convert bytes to human-readable file size.
     */
    function file_size_human(int $bytes, int $precision = 2): string
    {
        return ImageHelper::fileSizeHuman($bytes, $precision);
    }
}

if (!function_exists("format_money")) {
    /**
     * Format an amount as currency.
     */
    function format_money(
        float|int $amount,
        ?string $currency = null,
        ?string $locale = null,
    ): string {
        return GeneralHelper::formatMoney($amount, $currency, $locale);
    }
}

if (!function_exists("format_number")) {
    /**
     * Format a number with locale-aware separators.
     */
    function format_number(float|int $number, int $decimals = 0): string
    {
        return GeneralHelper::formatNumber($number, $decimals);
    }
}

if (!function_exists("calculate_percentage")) {
    /**
     * Calculate percentage safely.
     */
    function calculate_percentage(
        float|int $part,
        float|int $total,
        int $precision = 2,
    ): float {
        return GeneralHelper::percentage($part, $total, $precision);
    }
}

if (!function_exists("get_client_ip")) {
    /**
     * Get the client's IP address.
     */
    function get_client_ip(): ?string
    {
        return GeneralHelper::getClientIp();
    }
}

if (!function_exists("is_mobile")) {
    /**
     * Check if the request is from a mobile device.
     */
    function is_mobile(): bool
    {
        return GeneralHelper::isMobile();
    }
}

if (!function_exists("active_route")) {
    /**
     * Get active class for navigation based on current route.
     */
    function active_route(
        array|string $routes,
        string $activeClass = "active",
    ): string {
        return GeneralHelper::isActiveRoute($routes, $activeClass);
    }
}

if (!function_exists("set_seo")) {
    /**
     * Set default SEO meta tags for a page.
     */
    function set_seo(
        string $title,
        ?string $description = null,
        array $keywords = [],
        ?string $image = null,
    ): void {
        SeoHelper::setDefault($title, $description, $keywords, $image);
    }
}

if (!function_exists("set_article_seo")) {
    /**
     * Set SEO data for an article or blog post.
     */
    function set_article_seo(
        string $title,
        string $description,
        string $url,
        ?string $image = null,
        ?string $publishedTime = null,
        ?string $author = null,
    ): void {
        SeoHelper::setArticle(
            $title,
            $description,
            $url,
            $image,
            $publishedTime,
            $author,
        );
    }
}

if (!function_exists("structured_data")) {
    /**
     * Add structured data (JSON-LD) for rich snippets.
     */
    function structured_data(array $data): string
    {
        return SeoHelper::addStructuredData($data);
    }
}

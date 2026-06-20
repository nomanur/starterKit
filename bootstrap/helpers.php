<?php

/**
 * Global Helper Functions
 * 
 * These functions provide convenient shortcuts to the helper classes.
 * They are automatically loaded via composer.json autoload files.
 */

if (!function_exists('format_date')) {
    /**
     * Format a date consistently.
     *
     * @param mixed $date
     * @param string $format
     * @return string
     */
    function format_date($date, string $format = 'M d, Y'): string
    {
        return \App\Helpers\DateHelper::format($date, $format);
    }
}

if (!function_exists('time_ago')) {
    /**
     * Get human-readable time difference.
     *
     * @param mixed $timestamp
     * @return string
     */
    function time_ago($timestamp): string
    {
        return \App\Helpers\DateHelper::timeAgo($timestamp);
    }
}

if (!function_exists('clean_html')) {
    /**
     * Strip HTML tags while allowing specific ones.
     *
     * @param string $html
     * @param array $allowedTags
     * @return string
     */
    function clean_html(string $html, array $allowedTags = ['p', 'br', 'strong', 'em', 'a']): string
    {
        return \App\Helpers\FormatHelper::cleanHtml($html, $allowedTags);
    }
}

if (!function_exists('generate_slug')) {
    /**
     * Generate a unique slug from a title.
     *
     * @param string $title
     * @param string $separator
     * @return string
     */
    function generate_slug(string $title, string $separator = '-'): string
    {
        return \App\Helpers\FormatHelper::generateSlug($title, $separator);
    }
}

if (!function_exists('truncate_text')) {
    /**
     * Truncate text to a specified length.
     *
     * @param string $text
     * @param int $limit
     * @param string $ending
     * @return string
     */
    function truncate_text(string $text, int $limit = 100, string $ending = '...'): string
    {
        return \App\Helpers\FormatHelper::truncate($text, $limit, $ending);
    }
}

if (!function_exists('mask_string')) {
    /**
     * Mask sensitive string data.
     *
     * @param string $string
     * @param int $visibleChars
     * @param string $maskChar
     * @return string
     */
    function mask_string(string $string, int $visibleChars = 3, string $maskChar = '*'): string
    {
        return \App\Helpers\FormatHelper::maskString($string, $visibleChars, $maskChar);
    }
}

if (!function_exists('get_avatar')) {
    /**
     * Get user avatar URL with fallback.
     *
     * @param mixed $user
     * @param int $size
     * @return string
     */
    function get_avatar($user, int $size = 100): string
    {
        return \App\Helpers\ImageHelper::getAvatar($user, $size);
    }
}

if (!function_exists('storage_url')) {
    /**
     * Get a safe storage URL for a file.
     *
     * @param string|null $path
     * @return string
     */
    function storage_url(?string $path): string
    {
        return \App\Helpers\ImageHelper::storageUrl($path);
    }
}

if (!function_exists('file_size_human')) {
    /**
     * Convert bytes to human-readable file size.
     *
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    function file_size_human(int $bytes, int $precision = 2): string
    {
        return \App\Helpers\ImageHelper::fileSizeHuman($bytes, $precision);
    }
}

if (!function_exists('format_money')) {
    /**
     * Format an amount as currency.
     *
     * @param float|int $amount
     * @param string|null $currency
     * @param string|null $locale
     * @return string
     */
    function format_money($amount, ?string $currency = null, ?string $locale = null): string
    {
        return \App\Helpers\GeneralHelper::formatMoney($amount, $currency, $locale);
    }
}

if (!function_exists('format_number')) {
    /**
     * Format a number with locale-aware separators.
     *
     * @param float|int $number
     * @param int $decimals
     * @return string
     */
    function format_number($number, int $decimals = 0): string
    {
        return \App\Helpers\GeneralHelper::formatNumber($number, $decimals);
    }
}

if (!function_exists('calculate_percentage')) {
    /**
     * Calculate percentage safely.
     *
     * @param float|int $part
     * @param float|int $total
     * @param int $precision
     * @return float
     */
    function calculate_percentage($part, $total, int $precision = 2): float
    {
        return \App\Helpers\GeneralHelper::percentage($part, $total, $precision);
    }
}

if (!function_exists('get_client_ip')) {
    /**
     * Get the client's IP address.
     *
     * @return string|null
     */
    function get_client_ip(): ?string
    {
        return \App\Helpers\GeneralHelper::getClientIp();
    }
}

if (!function_exists('is_mobile')) {
    /**
     * Check if the request is from a mobile device.
     *
     * @return bool
     */
    function is_mobile(): bool
    {
        return \App\Helpers\GeneralHelper::isMobile();
    }
}

if (!function_exists('active_route')) {
    /**
     * Get active class for navigation based on current route.
     *
     * @param string|array $routes
     * @param string $activeClass
     * @return string
     */
    function active_route($routes, string $activeClass = 'active'): string
    {
        return \App\Helpers\GeneralHelper::isActiveRoute($routes, $activeClass);
    }
}

if (!function_exists('set_seo')) {
    /**
     * Set default SEO meta tags for a page.
     *
     * @param string $title
     * @param string|null $description
     * @param array $keywords
     * @param string|null $image
     * @return void
     */
    function set_seo(
        string $title,
        ?string $description = null,
        array $keywords = [],
        ?string $image = null
    ): void {
        \App\Helpers\SeoHelper::setDefault($title, $description, $keywords, $image);
    }
}

if (!function_exists('set_article_seo')) {
    /**
     * Set SEO data for an article or blog post.
     *
     * @param string $title
     * @param string $description
     * @param string $url
     * @param string|null $image
     * @param string|null $publishedTime
     * @param string|null $author
     * @return void
     */
    function set_article_seo(
        string $title,
        string $description,
        string $url,
        ?string $image = null,
        ?string $publishedTime = null,
        ?string $author = null
    ): void {
        \App\Helpers\SeoHelper::setArticle($title, $description, $url, $image, $publishedTime, $author);
    }
}

if (!function_exists('structured_data')) {
    /**
     * Add structured data (JSON-LD) for rich snippets.
     *
     * @param array $data
     * @return string
     */
    function structured_data(array $data): string
    {
        return \App\Helpers\SeoHelper::addStructuredData($data);
    }
}

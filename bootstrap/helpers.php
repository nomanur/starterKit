<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Str;

if (! function_exists('getAuthUser')) {
    function getAuthUser(): ?User
    {
        $user = auth()->user();

        return $user instanceof User ? $user : null;
    }
}

if (! function_exists('slug')) {
    function slug(string $title): string
    {
        return Str::slug($title);
    }
}

if (! function_exists('getAuthId')) {
    function getAuthId(): int|string|null
    {
        return auth()->id();
    }
}

if (! function_exists('isAdmin')) {
    function isAdmin(): bool
    {
        return getAuthUser()?->is_admin ?? false;
    }
}

if (! function_exists('format_date')) {
    function format_date(string $date, string $format = 'd M, Y'): string
    {
        return date($format, strtotime($date));
    }
}

if (! function_exists('format_datetime')) {
    function format_datetime(string $datetime, string $format = 'd M, Y h:i A'): string
    {
        return date($format, strtotime($datetime));
    }
}

if (! function_exists('getInitials')) {
    function getInitials(string $name): string
    {
        $words = explode(' ', $name);
        $initials = '';

        foreach ($words as $word) {
            $initials .= strtoupper($word[0] ?? '');
        }

        return $initials;
    }
}

if (! function_exists('truncate_text')) {
    function truncate_text(string $text, int $length = 100): string
    {
        return Str::limit($text, $length);
    }
}

if (! function_exists('isAppDebug')) {
    function isAppDebug(): bool
    {
        return config('app.debug') === true;
    }
}

if (! function_exists('generateUuid')) {
    function generateUuid(): string
    {
        return (string) Str::uuid();
    }
}

if (! function_exists('isLocalEnvironment')) {
    function isLocalEnvironment(): bool
    {
        return app()->environment('local');
    }
}

if (! function_exists('isProductionEnvironment')) {
    function isProductionEnvironment(): bool
    {
        return app()->environment('production');
    }
}

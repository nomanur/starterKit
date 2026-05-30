<?php

declare(strict_types=1);

namespace App\Filament\Forms\Components;

use Closure;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

class Translatable
{
    public static function getLocales(): array
    {
        $locales = config('app.translatable_locales');

        if (! is_array($locales)) {
            $locales = explode(',', (string) ($locales ?? 'en,es'));
        }

        return array_filter(array_map('trim', $locales));
    }

    public static function getLocaleLabel(string $locale): string
    {
        if (function_exists('locale_get_display_name')) {
            $name = locale_get_display_name($locale, app()->getLocale());

            if (filled($name)) {
                return $name;
            }
        }

        return strtoupper($locale);
    }

    /**
     * @param  Closure(string): array  $fields
     */
    public static function make(Closure $fields): Tabs
    {
        $locales = static::getLocales();

        return Tabs::make('Translations')
            ->tabs(
                collect($locales)->map(function (string $locale) use ($fields): Tab {
                    $resolved = $fields($locale);

                    return Tab::make(static::getLocaleLabel($locale))
                        ->schema($resolved);
                })->toArray(),
            );
    }
}

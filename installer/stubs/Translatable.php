<?php

declare(strict_types=1);

namespace App\Filament\Forms\Components;

use Closure;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;

class Translatable
{
    public static function make(Closure $fieldFactory, array $locales = []): Tabs
    {
        $locales = $locales ?: config('app.translatable_locales', ['en', 'es']);

        $tabs = [];

        foreach ($locales as $locale) {
            $tabs[] = Tab::make(strtoupper($locale))
                ->schema([
                    $fieldFactory($locale),
                ]);
        }

        return Tabs::make('translations')
            ->tabs($tabs);
    }
}

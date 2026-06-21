<?php

declare(strict_types=1);

namespace App\Filament\Resources\Posts\Schemas;

use App\Filament\Forms\Components\Translatable;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Schema;
use Nomanur\FilamentSeoPro\Forms\SeoTab;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Translatable::make(
                    fn (string $locale) => RichEditor::make("content.{$locale}")
                        ->label("Content ({$locale})")
                        ->columnSpanFull(),
                ),
            ])
            ->tabs([
                SeoTab::make(),
            ]);
    }
}

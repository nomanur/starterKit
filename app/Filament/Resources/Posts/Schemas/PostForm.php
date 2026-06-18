<?php

declare(strict_types=1);

namespace App\Filament\Resources\Posts\Schemas;

use App\Filament\Forms\Components\Translatable;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Nomanur\FilamentSeoPro\Forms\SeoSection;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Translatable::make(function (string $locale): array {
                    $suffix = ' ('.strtoupper($locale).')';

                    return [
                        TextInput::make("title.{$locale}")
                            ->label('Title'.$suffix)
                            ->required()
                            ->maxLength(255),
                        Textarea::make("content.{$locale}")
                            ->label('Content'.$suffix)
                            ->rows(5),
                    ];
                }),
                SeoSection::make(),
            ]);
    }
}

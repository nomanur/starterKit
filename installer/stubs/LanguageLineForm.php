<?php

declare(strict_types=1);

namespace App\Filament\Resources\LanguageLines\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LanguageLineForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('group')
                    ->required()
                    ->maxLength(255),
                TextInput::make('key')
                    ->required()
                    ->maxLength(255),
                KeyValue::make('text')
                    ->label('Translations')
                    ->required(),
            ]);
    }
}

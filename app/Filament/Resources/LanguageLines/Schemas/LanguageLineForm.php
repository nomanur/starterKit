<?php

declare(strict_types=1);

namespace App\Filament\Resources\LanguageLines\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LanguageLineForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('group')
                    ->label('Group')
                    ->required()
                    ->default('*')
                    ->placeholder('*')
                    ->maxLength(255),
                TextInput::make('key')
                    ->label('Key')
                    ->required()
                    ->maxLength(255),
                TextInput::make('text.en')
                    ->label('Translation (EN)')
                    ->required(),
                TextInput::make('text.bn')
                    ->label('Translation (BN)')
                    ->required(),
            ]);
    }
}

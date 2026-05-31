<?php

declare(strict_types=1);

namespace App\Filament\Resources\LanguageLines;

use App\Filament\Resources\LanguageLines\Pages\CreateLanguageLine;
use App\Filament\Resources\LanguageLines\Pages\EditLanguageLine;
use App\Filament\Resources\LanguageLines\Pages\ListLanguageLines;
use App\Filament\Resources\LanguageLines\Schemas\LanguageLineForm;
use App\Filament\Resources\LanguageLines\Tables\LanguageLinesTable;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Spatie\TranslationLoader\LanguageLine;

class LanguageLineResource extends Resource
{
    protected static ?string $model = LanguageLine::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-language';

    protected static string|\UnitEnum|null $navigationGroup = 'System';

    protected static ?string $modelLabel = 'Language Line';

    protected static ?string $pluralModelLabel = 'Language Lines';

    public static function form(Schema $schema): Schema
    {
        return LanguageLineForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LanguageLinesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLanguageLines::route('/'),
            'create' => CreateLanguageLine::route('/create'),
            'edit' => EditLanguageLine::route('/{record}/edit'),
        ];
    }
}

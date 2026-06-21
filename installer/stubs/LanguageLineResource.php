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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\TranslationLoader\LanguageLine;

class LanguageLineResource extends Resource
{
    protected static ?string $model = LanguageLine::class;

    protected static ?string $recordTitleAttribute = 'key';

    protected static int $globalSearchResultsLimit = 10;

    protected static ?int $globalSearchSort = 3;

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
        return [];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['group', 'key', 'text'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [
            'Group' => $record->group,
        ];

        if (is_array($record->text)) {
            $details['Locales'] = implode(', ', array_keys($record->text));
        }

        return $details;
    }

    protected static function applyGlobalSearchAttributeConstraint(Builder $query, string $search, array $searchAttributes, bool &$isFirst): Builder
    {
        foreach ($searchAttributes as $searchAttribute) {
            $whereClause = $isFirst ? 'where' : 'orWhere';

            $query->{$whereClause}(
                $query->qualifyColumn($searchAttribute),
                'like',
                "%{$search}%",
            );

            $isFirst = false;
        }

        return $query;
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

<?php

declare(strict_types=1);

namespace App\Filament\Resources\LanguageLines\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LanguageLinesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('group')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('key')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('text.en')
                    ->label('English')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('text->en', 'like', "%{$search}%");
                    }),
                TextColumn::make('text.bn')
                    ->label('Bengali')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('text->bn', 'like', "%{$search}%");
                    }),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

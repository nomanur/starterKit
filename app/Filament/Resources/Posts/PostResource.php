<?php

declare(strict_types=1);

namespace App\Filament\Resources\Posts;

use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\EditPost;
use App\Filament\Resources\Posts\Pages\ListPosts;
use App\Filament\Resources\Posts\Schemas\PostForm;
use App\Filament\Resources\Posts\Tables\PostsTable;
use App\Models\Post;
use App\Traits\ExportImport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PostResource extends Resource
{
    use ExportImport;

    protected static ?string $model = Post::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static int $globalSearchResultsLimit = 10;

    protected static ?int $globalSearchSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PostForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PostsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'content'];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['seo']);
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->title;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        if (! $record->relationLoaded('seo') || $record->seo === null) {
            return [];
        }

        return array_filter([
            'SEO Score' => $record->seo->seo_score,
            'Keyword' => $record->seo->focus_keyword,
        ]);
    }

    protected static function applyGlobalSearchAttributeConstraint(Builder $query, string $search, array $searchAttributes, bool &$isFirst): Builder
    {
        foreach ($searchAttributes as $searchAttribute) {
            $whereClause = $isFirst ? 'where' : 'orWhere';

            if (in_array($searchAttribute, ['title', 'content'])) {
                $query->{$whereClause}(
                    $searchAttribute.'->'.app()->getLocale(),
                    'like',
                    "%{$search}%",
                );
            } else {
                $query->{$whereClause}(
                    $query->qualifyColumn($searchAttribute),
                    'like',
                    "%{$search}%",
                );
            }

            $isFirst = false;
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),
        ];
    }

    /**
     * Customize export columns for Post resource.
     */
    public static function getExportColumns(): array
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}

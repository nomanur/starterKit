<?php

declare(strict_types=1);

namespace App\Traits;

use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use League\Csv\Reader;
use League\Csv\Writer;
use SplTempFileObject;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

trait ExportImport
{
    public static function getExportAction(): Action
    {
        return Action::make('export')
            ->label('Export')
            ->icon('heroicon-o-arrow-down-tray')
            ->form(fn (Form $form): Form => $form->schema([
                CheckboxList::make('columns')
                    ->label('Select Columns')
                    ->options(fn (): array => static::getExportColumns())
                    ->default(fn (): array => array_keys(static::getExportColumns()))
                    ->columns(2)
                    ->required(),
            ]))
            ->action(function (array $data): BinaryFileResponse {
                $csv = Writer::createFromFileObject(new SplTempFileObject);

                $columns = $data['columns'];
                $headers = array_intersect_key(static::getExportColumns(), array_flip($columns));
                $csv->insertOne(array_values($headers));

                static::getExportQuery()->chunk(100, function (Collection $records) use ($csv, $columns): void {
                    $records->each(function (Model $record) use ($csv, $columns): void {
                        $row = [];
                        foreach ($columns as $column) {
                            $row[] = $record->{$column} ?? '';
                        }
                        $csv->insertOne($row);
                    });
                });

                $filename = static::getModelLabel().'-export-'.now()->format('Y-m-d-His').'.csv';

                return response()->streamDownload(function () use ($csv): void {
                    echo $csv->toString();
                }, $filename, ['Content-Type' => 'text/csv']);
            });
    }

    public static function getImportAction(): Action
    {
        return Action::make('import')
            ->label('Import')
            ->icon('heroicon-o-arrow-up-tray')
            ->form(fn (Form $form): Form => $form->schema([
                FileUpload::make('file')
                    ->label('CSV File')
                    ->acceptedFileTypes(['text/csv', 'text/plain'])
                    ->maxSize(10240)
                    ->required(),
            ]))
            ->action(function (array $data): void {
                $path = storage_path('app/'.$data['file']);
                $csv = Reader::createFromPath($path, 'r');
                $csv->setHeaderOffset(0);

                $headers = $csv->getHeader();
                $columns = static::getExportColumns();

                foreach ($csv->getRecords() as $record) {
                    $data = [];
                    foreach ($headers as $header) {
                        $column = array_search($header, $columns);
                        if ($column !== false) {
                            $data[$column] = $record[$header];
                        }
                    }

                    if (! empty($data)) {
                        static::getModel()::create($data);
                    }
                }
            });
    }

    public static function getExportColumns(): array
    {
        return [];
    }

    protected static function getExportQuery(): Builder
    {
        return static::getModel()::query();
    }
}

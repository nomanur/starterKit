<?php

declare(strict_types=1);

namespace App\Traits;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Trait ExportImport
 * 
 * Adds Excel/CSV import and export functionality to Filament resources.
 * 
 * Usage: Add `use ExportImport;` to your Filament Resource class.
 */
trait ExportImport
{
    /**
     * Get the columns to export.
     * Override this method in your resource to customize exported columns.
     *
     * @return array<string, string>
     */
    public static function getExportColumns(): array
    {
        // Default: export all model columns
        if (static::$model) {
            $model = new static::$model();
            return array_combine(
                $model->getFillable(),
                $model->getFillable()
            );
        }

        return [];
    }

    /**
     * Get the query for exporting records.
     * Override this method to customize which records are exported.
     */
    public static function getExportQuery(): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
    {
        return static::getModel()::query();
    }

    /**
     * Create the export action for the table header.
     */
    public static function getExportAction(): Action
    {
        return Action::make('export')
            ->label('Export')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('success')
            ->modalHeading('Export Data')
            ->modalDescription('Choose your preferred format and columns to export.')
            ->form([
                \Filament\Forms\Components\Select::make('format')
                    ->label('Format')
                    ->options([
                        'csv' => 'CSV',
                        'excel' => 'Excel (XLSX)',
                    ])
                    ->default('csv')
                    ->required(),
                \Filament\Forms\Components\CheckboxList::make('columns')
                    ->label('Columns')
                    ->options(static::getExportColumns())
                    ->default(array_keys(static::getExportColumns()))
                    ->required()
                    ->columns(2),
            ])
            ->action(function (array $data): StreamedResponse {
                $format = $data['format'];
                $columns = $data['columns'];
                
                $query = static::getExportQuery();
                $records = $query->get();

                // Prepare data for export
                $exportData = [];
                $headers = [];

                foreach ($columns as $column) {
                    $headers[] = ucfirst(str_replace('_', ' ', $column));
                }

                foreach ($records as $record) {
                    $row = [];
                    foreach ($columns as $column) {
                        $value = $record->{$column};
                        
                        // Handle relationships and accessors
                        if (str_contains($column, '.')) {
                            $parts = explode('.', $column);
                            $relation = $parts[0];
                            $field = $parts[1];
                            
                            if ($record->{$relation}) {
                                $value = $record->{$relation}->{$field} ?? '';
                            } else {
                                $value = '';
                            }
                        }
                        
                        $row[] = $value ?? '';
                    }
                    $exportData[] = $row;
                }

                if ($format === 'csv') {
                    return static::downloadAsCsv($headers, $exportData);
                }

                return static::downloadAsExcel($headers, $exportData);
            });
    }

    /**
     * Download data as CSV file.
     */
    protected static function downloadAsCsv(array $headers, array $data): StreamedResponse
    {
        $filename = strtolower(class_basename(static::class)) . '_export_' . date('Y-m-d_H-i-s') . '.csv';

        return response()->streamDownload(function () use ($headers, $data) {
            $output = fopen('php://output', 'w');

            // Add BOM for UTF-8 encoding
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

            // Write headers
            fputcsv($output, $headers);

            // Write data rows
            foreach ($data as $row) {
                fputcsv($output, $row);
            }

            fclose($output);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Download data as Excel file.
     * Note: Requires phpoffice/phpspreadsheet package
     */
    protected static function downloadAsExcel(array $headers, array $data): StreamedResponse
    {
        $filename = strtolower(class_basename(static::class)) . '_export_' . date('Y-m-d_H-i-s') . '.xlsx';

        // Check if PhpSpreadsheet is available
        if (!class_exists(\PhpOffice\PhpSpreadsheet\Spreadsheet::class)) {
            Notification::make()
                ->title('Excel library not installed')
                ->body('Please install phpoffice/phpspreadsheet to export as Excel.')
                ->danger()
                ->send();

            // Fallback to CSV
            return static::downloadAsCsv($headers, $data);
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();

        // Set headers
        $worksheet->fromArray($headers, null, 'A1');

        // Set data
        $worksheet->fromArray($data, null, 'A2');

        // Auto-size columns
        foreach (range('A', $worksheet->getHighestDataColumn()) as $col) {
            $worksheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Set headers style
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'CCCCCC'],
            ],
        ];
        $worksheet->getStyle('A1:' . $worksheet->getHighestDataColumn() . '1')->applyFromArray($headerStyle);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Create the import action for the table header.
     */
    public static function getImportAction(): Action
    {
        return Action::make('import')
            ->label('Import')
            ->icon('heroicon-o-arrow-up-tray')
            ->color('primary')
            ->modalHeading('Import Data')
            ->modalDescription('Upload a CSV or Excel file to import records.')
            ->form([
                \Filament\Forms\Components\FileUpload::make('file')
                    ->label('File')
                    ->acceptedFileTypes(['text/csv', 'text/plain', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->required(),
                \Filament\Forms\Components\Select::make('format')
                    ->label('Format')
                    ->options([
                        'csv' => 'CSV',
                        'excel' => 'Excel (XLSX)',
                    ])
                    ->default('csv')
                    ->required(),
            ])
            ->action(function (array $data): void {
                $file = $data['file'];
                $format = $data['format'];

                // Get the file path
                $filePath = $file instanceof \Illuminate\Http\UploadedFile 
                    ? $file->getRealPath() 
                    : $file;

                $records = [];

                if ($format === 'csv') {
                    $records = static::parseCsv($filePath);
                } else {
                    $records = static::parseExcel($filePath);
                }

                $imported = 0;
                $failed = 0;

                foreach ($records as $record) {
                    try {
                        static::getModel()::create($record);
                        $imported++;
                    } catch (\Exception $e) {
                        $failed++;
                    }
                }

                Notification::make()
                    ->title('Import Complete')
                    ->body("Successfully imported {$imported} records. Failed: {$failed}")
                    ->success()
                    ->send();
            });
    }

    /**
     * Parse CSV file and return array of records.
     */
    protected static function parseCsv(string $filePath): array
    {
        $records = [];
        $handle = fopen($filePath, 'r');

        if ($handle === false) {
            return [];
        }

        // Get headers from first row
        $headers = fgetcsv($handle);

        if ($headers === false) {
            fclose($handle);
            return [];
        }

        // Clean headers (remove BOM, trim whitespace)
        $headers = array_map(function ($header) {
            return strtolower(trim(str_replace(chr(0xEF).chr(0xBB).chr(0xBF), '', $header)));
        }, $headers);

        // Read data rows
        while (($row = fgetcsv($handle)) !== false) {
            $record = [];
            foreach ($headers as $index => $header) {
                if (isset($row[$index])) {
                    $record[$header] = $row[$index];
                }
            }
            $records[] = $record;
        }

        fclose($handle);

        return $records;
    }

    /**
     * Parse Excel file and return array of records.
     * Note: Requires phpoffice/phpspreadsheet package
     */
    protected static function parseExcel(string $filePath): array
    {
        if (!class_exists(\PhpOffice\PhpSpreadsheet\IOFactory::class)) {
            throw new \RuntimeException('Please install phpoffice/phpspreadsheet to import Excel files.');
        }

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        if (empty($rows)) {
            return [];
        }

        // First row is headers
        $headers = array_map(function ($header) {
            return strtolower(trim((string) $header));
        }, array_shift($rows));

        $records = [];
        foreach ($rows as $row) {
            $record = [];
            foreach ($headers as $index => $header) {
                if (isset($row[$index]) && $row[$index] !== '') {
                    $record[$header] = $row[$index];
                }
            }
            $records[] = $record;
        }

        return $records;
    }
}

# Export/Import Trait for Filament Resources

This trait adds Excel/CSV import and export functionality to any Filament resource.

## Installation

The trait is located at `app/Traits/ExportImport.php`.

### Optional: Install PhpSpreadsheet for Excel Support

For Excel (XLSX) file support, install the required package:

```bash
composer require phpoffice/phpspreadsheet
```

Without this package, the system will still work but will fallback to CSV format for Excel exports.

## Usage

### 1. Add the Trait to Your Resource

```php
use App\Traits\ExportImport;

class PostResource extends Resource
{
    use ExportImport;
    
    // ... rest of your resource
}
```

### 2. Add Actions to Your Table

In your table configuration file (e.g., `PostsTable.php`):

```php
use App\Filament\Resources\Posts\PostResource;

public static function configure(Table $table): Table
{
    return $table
        ->toolbarActions([
            PostResource::getExportAction(),
            PostResource::getImportAction(),
            // ... other actions
        ]);
}
```

### 3. Customize Export Columns (Optional)

Override the `getExportColumns()` method in your resource:

```php
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
```

### 4. Customize Export Query (Optional)

Override the `getExportQuery()` method to filter which records are exported:

```php
public static function getExportQuery()
{
    return static::getModel()::query()->where('status', 'published');
}
```

## Features

### Export
- **Formats**: CSV and Excel (XLSX)
- **Column Selection**: Users can choose which columns to export
- **UTF-8 Support**: CSV files include BOM for proper UTF-8 encoding
- **Styled Excel**: Excel files include styled headers with bold text and background color

### Import
- **Formats**: CSV and Excel (XLSX)
- **Automatic Mapping**: Headers are automatically mapped to database columns
- **Error Handling**: Failed imports are counted and reported
- **Notifications**: Success/failure notifications after import completes

## Example CSV Format

For importing posts, create a CSV with headers matching your model's fillable fields:

```csv
id,title,content,created_at,updated_at
1,My First Post,This is the content...,2024-01-01 00:00:00,2024-01-01 00:00:00
2,My Second Post,More content here...,2024-01-02 00:00:00,2024-01-02 00:00:00
```

## Notes

- The import function creates new records. For updating existing records, customize the import action.
- For large datasets, consider implementing queued exports/imports.
- Relationship fields can be exported using dot notation (e.g., `author.name`).

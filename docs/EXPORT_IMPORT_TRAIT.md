# Export/Import Trait for Filament Resources

This trait (`app/Traits/ExportImport.php`) adds Excel/CSV import and export functionality to any Filament resource. It uses native PHP `fputcsv`/`fgetcsv` for CSV handling and `PhpOffice\PhpSpreadsheet` for XLSX support.

---

## Installation

The trait is located at `app/Traits/ExportImport.php`. No additional setup is required for CSV support.

### Excel (XLSX) Support

For Excel support, install PhpSpreadsheet:

```bash
composer require phpoffice/phpspreadsheet
```

Without this package, Excel export falls back to CSV with a notification. Excel import throws a clear error message.

---

## Quick Start

### 1. Add the Trait to Your Resource

```php
use App\Traits\ExportImport;

class PostResource extends Resource
{
    use ExportImport;

    protected static ?string $model = Post::class;
}
```

### 2. Wire Actions to Your Table

In your table configuration:

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

This adds **Export** and **Import** buttons above the table.

---

## How Export Works

1. User clicks **Export** → a modal opens with:
   - **Format** select: CSV or Excel (XLSX)
   - **Columns** checklist: populated from `getExportColumns()`

2. User selects format + columns and confirms.

3. The trait fetches records via `getExportQuery()` and generates the file.

4. The browser downloads the file as a `StreamedResponse`.

### Overridable Methods

#### `getExportColumns(): array`

Returns `array<string, string>` mapping column names to display labels.

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

Default: if the model has `$fillable`, returns the fillable columns as both key and label.

#### `getExportQuery(): Builder`

Returns the query builder for records to export.

```php
public static function getExportQuery()
{
    return static::getModel()::query()->where('status', 'published');
}
```

Default: `Model::query()` (exports all records).

---

## How Import Works

1. User clicks **Import** → a modal opens with:
   - **File upload**: accepts `.csv`, `.xlsx` (MIME-typed)
   - **Format** select: CSV or Excel (XLSX)

2. User uploads a file and selects format, then confirms.

3. The trait parses the file:
   - First row = column headers (auto-mapped to database columns)
   - Each subsequent row = one record

4. Records are created one by one with individual try/catch blocks.

5. A notification reports the count of successful and failed imports.

---

## Architecture

| Layer | Detail |
|---|---|
| **Trait** | `App\Traits\ExportImport` — reusable across any Filament resource |
| **CSV Engine** | Native PHP `fputcsv` / `fgetcsv` (zero dependencies) |
| **Excel Engine** | `PhpOffice\PhpSpreadsheet` (composer optional) |
| **Model Resolution** | Via `static::getModel()` from the owning Filament Resource |
| **Export Delivery** | `Symfony\Component\HttpFoundation\StreamedResponse` |
| **Import Flow** | File upload → parse → `Model::create()` per row |

### File Structure

```
app/
├── Traits/
│   └── ExportImport.php          # Core trait
└── Filament/
    └── Resources/
        └── Posts/
            ├── PostResource.php   # Uses ExportImport trait
            └── Tables/
                └── PostsTable.php # Wires export/import actions
```

---

## Export Format Details

### CSV
- UTF-8 BOM prepended for correct Excel encoding
- Headers as the first row
- One record per row
- Content-Type: `text/csv; charset=UTF-8`
- Filename pattern: `{resource}_export_{date}.csv`

### Excel (XLSX)
- Auto-sized column widths
- Bold header row with gray (`#CCCCCC`) background fill
- Content-Type: `application/vnd.openxmlformats-officedocument.spreadsheetml.sheet`
- Filename pattern: `{resource}_export_{date}.xlsx`

---

## Import File Format

Headers must match your model's database columns (or fillable attributes).

**Example CSV (`posts.csv`):**
```csv
title,content
My First Post,This is the content of the post.
My Second Post,More content here.
```

**Example Excel:**
| title | content |
|---|---|
| My First Post | This is the content of the post. |
| My Second Post | More content here. |

---

## Handling Translatable Columns

When exporting models that use Spatie `laravel-translatable`, the JSON-encoded translation value is exported as-is. For imports, provide the JSON value in the expected format:

```csv
title,content
"{"en":""Hello"",""es"":""Hola""}","{"en"":""English content"",""es"":""Contenido español""}"
```

---

## Adding to a New Resource

To add export/import to another resource:

1. Add `use ExportImport;` to the resource class.
2. Optionally override `getExportColumns()` and `getExportQuery()`.
3. Add `Resource::getExportAction()` and `Resource::getImportAction()` to the table's `toolbarActions`.

---

## Notes

- Import **creates** new records only. For updating existing records, customize the import action.
- The trait uses `static::getModel()` to resolve the model. Ensure `$model` is set on your resource.
- Relationship fields can be exported using dot notation (e.g. `author.name`).
- For large datasets, consider implementing queued exports/imports.

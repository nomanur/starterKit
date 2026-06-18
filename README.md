<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://packagist.org/packages/nomanur/nomanur-starter-kit"><img src="https://img.shields.io/packagist/v/nomanur/nomanur-starter-kit" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/nomanur/nomanur-starter-kit"><img src="https://img.shields.io/packagist/l/nomanur/nomanur-starter-kit" alt="License"></a>
  <a href="https://github.com/nomanur/starterKit"><img src="https://img.shields.io/github/actions/workflow/status/nomanur/starterKit/tests.yml?branch=main" alt="Build Status"></a>
  <a href="https://nomanur.github.io/starterKit/"><img src="https://img.shields.io/badge/docs-homepage-blue" alt="Homepage"></a>
</p>

---

# Nomanur Laravel Starter Kit 🚀

Welcome to the **Nomanur Laravel Starter Kit**—a pre-configured, battle-tested, and production-ready Laravel application blueprint. Designed to kickstart your next application, this starter kit compiles modern admin panel tooling, dynamic role-based access control, real-time background queues monitoring, and clean testing architectures out of the box.

---

## Key Features & Stack 📦

This starter kit comes pre-integrated with premium, industry-standard packages, configured and wired to work seamlessly together:

*   **Laravel 13 & PHP 8.4**: Operating on the state-of-the-art core framework.
*   **Filament PHP v5**: A beautiful, blazing-fast, and responsive administrative panel.
*   **Filament Shield v4**: Granular role-based access control (RBAC) powered by Spatie Laravel Permission, managed entirely from the Filament dashboard.
*   **Filament Jobs Monitor v4**: Real-time visualization and log tracking of background jobs and queue workers.
*   **Livewire v4 (V3 Class Format)**: Utilizing the stable and familiar Livewire V3 class-based component structure for dynamic interfaces.
*   **Pre-Built User Resource**: A custom, fully secure admin interface for user accounts. Displays active roles as styled primary badges in the user table, and allows assigning/revoking roles with a multi-select interface in the user editor form.
*   **Pest Testing 4**: Fully configured test suite with type coverage, architecture rules, and functional feature tests.
*   **Laravel Boost v2**: Advanced AI agent capabilities for streamlined copilot development.
*   **Spatie Laravel Media Library v11**: Battle-tested file attachments, uploads, and media collection associations on Eloquent models (e.g. User Profile Avatars).
*   **Opcodes Log Viewer v3**: A beautiful, highly secure log-monitoring interface integrated into the Filament sidebar. Restricts access natively to Super Admins.
*   **CSV/Excel Export & Import**: A reusable trait that adds export/import buttons to any Filament resource table, supporting CSV and XLSX formats with column selection and styled output.
*   **Two-Factor Authentication (2FA)**: Secure TOTP-based 2FA protecting the admin panel with recovery codes and inline SVG QR code modal.
*   **Profile Validation Rules Trait**: Centralized validation rules for consistent email and password rules across registration, edit profile, and password reset flows.

---

## 🚀 Recent Updates

*   **Role Management Integration**: Successfully integrated **Filament Shield**, enabling dynamic permission and role management directly through the UI.
*   **Queue Monitoring**: Added **Filament Jobs Monitor** to provide a real-time dashboard for background job execution and failure tracking.
*   **Enhanced User Administration**: Refactored the User Resource to support multi-role assignments and improved visual representation of user status in the dashboard.
*   **Documentation Site**: Initialized a local documentation site accessible via `docs/index.html`.
*   **Livewire Modernization**: Standardized on the **Livewire V3 class format** to ensure long-term maintainability and performance.
*   **Media Library Demo Page**: Fully functional Livewire and Spatie Media Library upload/gallery demo integrated at `/photo` with a premium glassmorphic UI.
*   **Secure Log Viewer**: Integrated the **Opcodes Log Viewer** package, exposing it only to `super_admin` users via dynamic navigation sidebar elements in the Filament admin panel.
*   **CSV/Excel Export & Import**: Added a reusable `ExportImport` trait for Filament resources. Export supports CSV/XLSX with column selection; import parses uploaded files into new records with success/failure notifications.
*   **Two-Factor Authentication (2FA)**: Added robust TOTP-based 2FA for admin panels with SVG QR code rendering and session-persistent protection.
*   **Profile Validation Rules**: CENTRALIZED email and password validations in the reusable `ProfileValidationRules` trait.

---

## 📂 Spatie Media Library Integration & Demo 📸

The starter kit comes with Spatie Media Library pre-configured and automated out of the box. The symbolic storage link (`php artisan storage:link`) is automatically created during installation via composer scripts.

### Live Demo
Visit the route `/photo` on your local server to experience a premium, glassmorphic dark-theme UI featuring real-time drag-and-drop file uploads, image previews, size/mime inspections, and deletion hooks.

---

## ✂️ Image Cropping System

The starter kit ships with a fully integrated **client-side image cropping system** powered by [Cropper.js](https://github.com/fengyuanchen/cropperjs) and Alpine.js. Users can select an image, crop it to a desired aspect ratio, and upload the cropped result — all before the file touches the server.

### 🚀 Key Features

- **Client-Side Cropping**: Images are cropped entirely in the browser using Canvas API — no server round-trip needed.
- **Alpine.js Integration**: The cropping logic is encapsulated in a reusable `imageCropper` Alpine component, making it trivial to add to any Livewire form.
- **Cropper.js Powered**: Full-featured crop control with drag handles, zoom, aspect ratio locking, and rotation support.
- **Custom Aspect Ratio**: Pass any aspect ratio (e.g., `1` for square, `16/9` for landscape) via the Alpine component config.
- **Seamless Livewire Upload**: The cropped file is dispatched as a standard `File` blob via a custom `image-cropped` event, which Livewire's `@this.upload()` picks up effortlessly.
- **Beautiful Crop Modal**: A glassmorphic dark-theme modal overlay with smooth transitions for the crop interface.

### 🎨 How It Works

1. User selects an image file via `<input type="file">`.
2. The `imageCropper` Alpine component reads the file as a Data URL and initializes Cropper.js on it inside a full-screen modal.
3. User adjusts the crop area and clicks **Apply Crop**.
4. `saveCrop()` uses `Cropper.getCroppedCanvas().toBlob()` to produce a cropped JPEG `File` blob.
5. The component dispatches an `image-cropped` event with the cropped file.
6. Livewire's `@this.upload('photo', $event.detail.file)` uploads the cropped temporary file to the server.
7. The Livewire `save()` method validates and persists the file to Spatie Media Library.

### ⚡ Quick Usage

Drop the Alpine component into any Livewire Blade view. The minimal setup:

```blade
<div
    x-data="imageCropper({ cropping: true, aspectRatio: 1 })"
    @image-cropped.window="@this.upload('photo', $event.detail.file)"
    wire:ignore
>
    <input type="file" x-ref="fileInput" @change="onFileChange" />

    <x-cropping-modal />
</div>
```

> The crop modal is a reusable Blade component at [`resources/views/components/cropping-modal.blade.php`](resources/views/components/cropping-modal.blade.php) with customizable `title`, `cancelText`, and `applyText` props.

### 🧩 Alpine Component API

The `imageCropper` component accepts a config object and exposes the following:

| Config       | Type    | Default | Description                              |
|-------------|---------|---------|------------------------------------------|
| `cropping`   | boolean | `false` | Whether to show the crop modal on load   |
| `aspectRatio`| number  | `NaN`   | Aspect ratio for the crop box (freeform) |

| Method       | Description                                      |
|-------------|--------------------------------------------------|
| `onFileChange(event)` | Read a file input and show the crop modal      |
| `saveCrop()` | Apply the crop, dispatch `image-cropped` event, close modal |
| `cancelCrop()` | Discard the crop, close modal, clear input       |
| `clearInput()` | Reset the file input value                       |

| Event               | Detail                          | Description                            |
|---------------------|---------------------------------|----------------------------------------|
| `image-cropped`     | `{ file: File, dataUrl: string }` | Dispatched after crop is applied       |

### 🗺️ Livewire Integration

In your Livewire component, listen for the `image-cropped` event and save to Spatie Media Library:

```php
use Livewire\WithFileUploads;
use Livewire\Component;

class Test extends Component
{
    use WithFileUploads;

    public ?TemporaryUploadedFile $photo = null;

    public function save(): void
    {
        $this->validate([
            'photo' => ['required', 'image', 'max:5120'],
        ]);

        $this->user->addMedia($this->photo->getRealPath())
            ->usingFileName($this->photo->getClientOriginalName())
            ->toMediaCollection('avatars');
    }
}
```

### 🔧 Under the Hood

**Alpine Plugin** (`resources/js/alpine-cropper.js`):
- Registers an `imageCropper` Alpine data component.
- Manages `cropping`, `imageUrl`, and `cropper` reactive state.
- `init()` watches `cropping` — when `true` and `imageUrl` is set, it calls `initCropper()` which instantiates a new `Cropper` instance on the `<img x-ref="cropperImage">` element.
- `onFileChange(event)` reads the selected file via `FileReader`, sets `imageUrl`, and triggers the crop modal.
- `saveCrop()` calls `cropper.getCroppedCanvas()`, converts to blob, creates a `File`, then dispatches the `image-cropped` custom event with `{ file, dataUrl }`.
- `cancelCrop()` destroys the cropper, hides the modal, and clears the input.

**JavaScript Bootstrap** (`resources/js/app.js`):
- Imports and registers the `imageCropperPlugin` via `Alpine.plugin(imageCropperPlugin)`.

**Blade Component** (`resources/views/components/cropping-modal.blade.php`):
- Encapsulates the glassmorphic crop modal as a reusable Laravel Blade component.
- Accepts customizable props: `title` (default: "Crop Your Profile Photo"), `cancelText` (default: "Cancel"), `applyText` (default: "Apply Crop").
- Wire it into any form with `<x-cropping-modal />` inside the Alpine `x-data="imageCropper(...)"` scope.

**NPM Dependency** (`package.json`):
- `"cropperjs": "^1.6.2"` — the underlying crop library.

### 🖼️ Live Demo

Visit `/photo` on your local server to see the full cropping + upload workflow in action with a polished glassmorphic UI.

### Implementation Guide & Example

#### 1. Prepare your Eloquent Model
To allow a model to accept file attachments, implement `HasMedia` and use the `InteractsWithMedia` trait:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    
    // Optional: Define collections
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatars')
            ->singleFile(); // Replaces old avatars with new uploads automatically
    }
}
```

#### 2. Livewire Component Usage
Handle incoming temporary uploads and attach them directly to your model's media collection:

```php
namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;

class Test extends Component
{
    use WithFileUploads;

    public $photo; // Holds the TemporaryUploadedFile
    public User $user;

    public function save(): void
    {
        $this->validate([
            'photo' => ['required', 'image', 'max:5120'], // 5MB Limit
        ]);

        // Attach file to Spatie Media Library
        $this->user->addMedia($this->photo->getRealPath())
            ->usingFileName($this->photo->getClientOriginalName())
            ->toMediaCollection('avatars');

        $this->photo = null; // Clear state
    }
}
```

---

## 🪵 Opcodes Log Viewer Integration 📄

The starter kit comes with **Opcodes Log Viewer** pre-installed and secured. It enables real-time visual inspection of application logs directly inside the Filament Admin Sidebar or at `/log-viewer`.

### Security & Authentication
By default, the route is secured in `AppServiceProvider` and only accessible to users with the `super_admin` role. In addition, the Sidebar Navigation link is governed by Filament Shield's granular permission framework.

*   **Custom Permission**: `view_log_viewer` is registered in Filament Shield config.
*   **Sidebar Visibility**: The navigation link is hidden unless the user has the explicit `view_log_viewer` permission (which Super Admins inherit implicitly).

### Direct Access
Log in to Filament as the default administrator (`admin@admin.com`) and navigate to **System > Log Viewer** in the sidebar, or go directly to:
`http://127.0.0.1:8000/log-viewer`

---

## 🌐 Laravel Socialite OAuth Integration 🔑

The starter kit comes with **Laravel Socialite** pre-installed and seamlessly integrated to support modern OAuth authentication. Out of the box, we support a wide range of social login providers with fully-secured backend controller handling, a dynamic `SocialiteProvider` Enum, and a premium Livewire utility component that auto-detects active integrations.

### Supported Providers
*   **GitHub** (`github`)
*   **Google** (`google`)
*   **Facebook** (`facebook`)
*   **X / Twitter** (`twitter-oauth-2`)
*   **LinkedIn** (`linkedin-openid`)
*   **GitLab** (`gitlab`)
*   **Bitbucket** (`bitbucket`)
*   **Slack** (`slack-openid`)

---

### 🚀 Setup & Activation in 2 Steps

#### 1. Add API Credentials to `.env`
Uncomment and populate the client IDs and secrets in your local `.env` file (based on the templates provided in `.env.example`):
```env
# Example: Enabling GitHub Login
GITHUB_CLIENT_ID=your_github_client_id
GITHUB_CLIENT_SECRET=your_github_client_secret
```

#### 2. Uncomment Services Configuration
Open [services.php](file:///Users/nomanur/Herd/starterKit/config/services.php) and uncomment the relevant provider array. The starter kit automatically manages redirect paths to standard callback endpoints (`/auth/{provider}/callback`):
```php
'github' => [
    'client_id' => env('GITHUB_CLIENT_ID'),
    'client_secret' => env('GITHUB_CLIENT_SECRET'),
    'redirect' => '/auth/github/callback',
],
```

---

### 🎨 Livewire Component Usage
To display a stunning, fully-styled list of all configured and activated social login buttons, simply drop the Livewire component into any Blade login view:

```blade
<livewire:socialite />
```

#### Customization Options:
Customize visual layouts directly via Livewire properties:
```blade
<livewire:socialite 
    heading="Login with your social profile" 
    :showLabels="true" 
    :columns="2" 
    size="lg" 
/>
```

---

### 🛠️ Architecture & Under the Hood
Key database updates and unified routing patterns power the passwordless authentication lifecycle cleanly. Active integrations are listed dynamically in view elements.

---

## 🌍 Multilingual Spatie Translatable & Localization System 📚

The starter kit features a pre-wired, high-performance multilingual translation and localization architecture. Combining Spatie's `laravel-translatable` with automated Geo-IP services and browser-header detection, administrators can manage content dynamically in the Filament Panel, while visitors are automatically served pages in their native tongue based on their location or browser preference.

---

### 🚀 Key Features

*   **Geo-IP Language Detection**: The application queries the visitor's IP address dynamically using `devrabiul/laravel-geo-genius` to detect their country and auto-serve pages in their native language (e.g., visitors from Bangladesh automatically see Bengali content).
*   **Preferred Browser Fallback**: If the geolocation lookup is inconclusive, the system seamlessly inspects browser headers to detect preferred languages, falling back gracefully to the application default (`en`).
*   **Session-Persistent Overrides**: Manual language switching is fully supported. Toggling a language updates the user's session locale (`locale`), which takes full precedence over automatic detection.
*   **Tabbed Form Interface**: The custom `Translatable` component automatically partitions inputs (like Title, Content) into beautiful language tabs based on active locales.
*   **Settings-Driven Locales**: Manage the list of active translation languages dynamically inside `.env` or from **System > Settings > Translatable Locales** in the Filament Sidebar.
*   **Locale-Scoped Search**: Column search is fully localized. Searching in Filament tables scopes filters to the current user's active locale's JSON database path (`title->en`), preventing invalid language matches and SQL performance issues.

---

### 🎨 How to Use

#### 1. Define Translatable Models
Use the `HasTranslations` trait and specify which columns support translations in your Eloquent model:
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Post extends Model
{
    use HasTranslations;

    protected $fillable = ['title', 'content'];

    // Define translatable columns
    public array $translatable = ['title', 'content'];
}
```

#### 2. Configure Form Fields
Wrap your input fields inside the custom `Translatable::make` component in your Filament form schema:
```php
use App\Filament\Forms\Components\Translatable;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

Translatable::make(function (string $locale): array {
    $suffix = ' (' . strtoupper($locale) . ')';

    return [
        TextInput::make("title.{$locale}")
            ->label('Title' . $suffix)
            ->required()
            ->maxLength(255),
        Textarea::make("content.{$locale}")
            ->label('Content' . $suffix)
            ->rows(5),
    ];
})
```

#### 3. Enable Locale-Scoped Search in Tables
To ensure high-performance, locale-isolated table searches, configure the `searchable` method with a scoped query:
```php
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

TextColumn::make('title')
    ->searchable(query: function (Builder $query, string $search): Builder {
        return $query->where('title->' . app()->getLocale(), 'like', "%{$search}%");
    })
```

#### 4. Automatic Localization Middleware (`SetLocaleMiddleware`)
The global `SetLocaleMiddleware` is automatically appended to the `web` middleware group inside `bootstrap/app.php`. It handles country-to-locale mapping, browser header inspections, and session persistence:
```php
// app/Http/Middleware/SetLocaleMiddleware.php
protected array $countryToLocaleMap = [
    'BD' => 'bn', // Bangladesh -> Bengali
    'IN' => 'hi', // India -> Hindi
    'US' => 'en', // United States -> English
    // ... easily expandable country-to-locale array
];
```

#### 5. Implementing a Manual Language Selector
To allow users to manually switch languages on the frontend, define a session-setting route and display selector links:

**Step A: Define the route in `routes/web.php`:**
```php
use Illuminate\Support\Facades\Route;

Route::get('/language/{locale}', function (string $locale) {
    if (in_array($locale, config('app.translatable_locales', ['en']))) {
        session(['locale' => $locale]);
    }
    return back();
})->name('language.switch');
```

**Step B: Add a language selector component in your blade template:**
```html
<div class="flex gap-4 items-center">
    <a href="{{ route('language.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'font-bold' : '' }}">English</a>
    <a href="{{ route('language.switch', 'bn') }}" class="{{ app()->getLocale() === 'bn' ? 'font-bold' : '' }}">বাংলা</a>
</div>
```
The `SetLocaleMiddleware` automatically intercepts all incoming requests, detects the session key, and sets the active application locale globally.

---

## 📥 CSV/Excel Export & Import 📤

The starter kit includes a reusable `ExportImport` trait (`app/Traits/ExportImport.php`) that adds export and import buttons to any Filament resource table. It supports **CSV** and **Excel (XLSX)** formats with styled output and column selection.

### 🚀 Key Features

*   **Export**: Modal dialog with format selection (CSV/XLSX) and column checklist. Exported rows are streamed directly to the browser.
*   **Import**: File upload modal supporting CSV and XLSX files. Headers auto-map to database columns. Records are created with per-row error handling.
*   **Customizable Columns**: Override `getExportColumns()` to define which columns appear in the export modal.
*   **Customizable Query**: Override `getExportQuery()` to filter which records are exported (e.g. only published posts).
*   **UTF-8 & Styling**: CSV files include a UTF-8 BOM; Excel files get auto-sized columns with styled headers (bold + gray background).
*   **Excel Fallback**: If `phpoffice/phpspreadsheet` is not installed, Excel exports gracefully fall back to CSV.

### 🎨 How to Use

#### 1. Add the Trait to Your Resource

```php
use App\Traits\ExportImport;

class PostResource extends Resource
{
    use ExportImport;
}
```

#### 2. Wire Actions to Your Table

```php
use App\Filament\Resources\Posts\PostResource;

public static function configure(Table $table): Table
{
    return $table
        ->toolbarActions([
            PostResource::getExportAction(),
            PostResource::getImportAction(),
        ]);
}
```

#### 3. Customize Export Columns (Optional)

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

#### 4. Customize Export Query (Optional)

```php
public static function getExportQuery()
{
    return static::getModel()::query()->where('status', 'published');
}
```

### 📄 Import CSV Format

Headers in your CSV/XLSX file should match the database column names. Example:

```csv
title,content
My First Post,This is the content of the post.
My Second Post,More content here.
```

---

## 🛡️ Two-Factor Authentication (2FA) 🔐

The starter kit features a secure, industry-standard **Time-based One-time Password (TOTP) Two-Factor Authentication** system for admin panel protection.

### Key Features
*   **Secure QR Code Generation**: Generates inline SVG QR codes inside the Filament modal, preventing OS-level scheme interception.
*   **Recovery Codes**: Automatically generates 8 secure, encrypted recovery codes for backup access.
*   **Session-Persistent Authentication**: Once verified, the user's 2FA status is stored securely in the session.
*   **Panel Protection Middleware**: The `TwoFactorMiddleware` protects all admin routes, redirecting unverified users to the challenge page while keeping their Laravel session intact.

### How to Enable & Use
1.  Log into the admin panel and go to **Users > Edit User**.
2.  Click the green **Enable 2FA** button at the top-right.
3.  Scan the rendered QR code with your authenticator app (e.g. Google Authenticator, Authy, or 1Password) and enter the 6-digit code to confirm.
4.  Copy and save your backup recovery codes.
5.  On your next login, you will be intercepted and redirected to `/admin/two-factor-challenge` to verify your 2FA code before accessing the dashboard.

---

## 📝 Profile Validation Rules Trait

The `ProfileValidationRules` trait (`app/Traits/ProfileValidationRules.php`) centralizes password and email validation rules to ensure consistency and clean architecture across registration, edit profile, and password reset forms.

### Centralized Validation Rules
*   **Centralized Email Rules**: Standardizes validation patterns, including required, email format, maximum length, and dynamic unique constraints (allowing ignoring specific user IDs during profile updates).
*   **Centralized Password Rules**: Standardizes password rules, including minimum length (8 characters), required, and confirmation matching.

### How to Use
Use the trait in any controller, livewire component, or form request class:
```php
use App\Traits\ProfileValidationRules;

class RegisterController extends Controller
{
    use ProfileValidationRules;

    public function register(Request $request)
    {
        $request->validate([
            'email' => $this->emailRules(),
            'password' => $this->passwordRules(),
        ]);
    }
}
```
For updating an existing user's profile:
```php
$request->validate([
    'email' => $this->emailRules($user->id), // dynamically ignores the current user's ID
]);
```

---

## ⚡ Quick Installation

You can create a new project using this starter kit with a single Composer command:

```bash
composer create-project nomanur/nomanur-starter-kit my-app
```

During installation, the project **automatically runs the setup scripts** to:
1. Copy the `.env.example` file to `.env` dynamically.
2. Generate the application encryption key.
3. Automatically touch and initialize a local SQLite database (`database/database.sqlite`).
4. Run all database migrations and seeds, including Filament Shield registration, creating default admin credentials, and seeding fake users.

---

## 🔑 Default Credentials

Once the installation finishes, you can immediately log into the Filament administrative dashboard at `/admin` using these default credentials:

*   **Dashboard URL**: `http://127.0.0.1:8000/admin`
*   **Username**: `admin@admin.com`
*   **Password**: `12345678`

*Note: In addition to the main administrator, the setup seeder automatically seeds **2 additional fake users** for immediate local data visualization in the User Resource table.*

---

## 🛠️ Developer Commands

Use these built-in Composer script commands to manage your local environment:

### Run Local Development Server
Start the HTTP server, queue listener, log tailing, and frontend bundlers concurrently:
```bash
composer run dev
```

### Run Tests and Quality Audits
Verify Pint formatting, Rector refactoring rules, PHPStan static analyses, type coverage, and all unit/feature tests:
```bash
composer test
```

### Auto-Format Code Styles
Run Laravel Pint to automatically clean up and align formatting:
```bash
composer lint
```

### Auto-Refactor Code
Run Rector to safely upgrade PHP codebases in line with modern programming features:
```bash
composer refactor
```

---

## 🛡️ License

The Nomanur Laravel Starter Kit is open-sourced software licensed under the [MIT license](LICENSE).

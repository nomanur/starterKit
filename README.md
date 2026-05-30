<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://packagist.org/packages/nomanur/nomanur-starter-kit"><img src="https://img.shields.io/packagist/v/nomanur/nomanur-starter-kit" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/nomanur/nomanur-starter-kit"><img src="https://img.shields.io/packagist/l/nomanur/nomanur-starter-kit" alt="License"></a>
  <a href="https://github.com/nomanur/starterKit"><img src="https://img.shields.io/github/actions/workflow/status/nomanur/starterKit/tests.yml?branch=main" alt="Build Status"></a>
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

---

## 🚀 Recent Updates

*   **Role Management Integration**: Successfully integrated **Filament Shield**, enabling dynamic permission and role management directly through the UI.
*   **Queue Monitoring**: Added **Filament Jobs Monitor** to provide a real-time dashboard for background job execution and failure tracking.
*   **Enhanced User Administration**: Refactored the User Resource to support multi-role assignments and improved visual representation of user status in the dashboard.
*   **Documentation Site**: Initialized a local documentation site accessible via `docs/index.html`.
*   **Livewire Modernization**: Standardized on the **Livewire V3 class format** to ensure long-term maintainability and performance.
*   **Media Library Demo Page**: Fully functional Livewire and Spatie Media Library upload/gallery demo integrated at `/photo` with a premium glassmorphic UI.

---

## 📂 Spatie Media Library Integration & Demo 📸

The starter kit comes with Spatie Media Library pre-configured and automated out of the box. The symbolic storage link (`php artisan storage:link`) is automatically created during installation via composer scripts.

### Live Demo
Visit the route `/photo` on your local server to experience a premium, glassmorphic dark-theme UI featuring real-time drag-and-drop file uploads, image previews, size/mime inspections, and deletion hooks.

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

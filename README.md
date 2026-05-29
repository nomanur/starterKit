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
*   **Pre-Built User Resource**: A custom, fully secure admin interface for user accounts. Displays active roles as styled primary badges in the user table, and allows assigning/revoking roles with a multi-select interface in the user editor form.
*   **Pest Testing 4**: Fully configured test suite with type coverage, architecture rules, and functional feature tests.
*   **Laravel Boost v2**: Advanced AI agent capabilities for streamlined copilot development.

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

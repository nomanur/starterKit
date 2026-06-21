<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Starter Kit Docs — {{ config('app.name', 'Laravel') }}</title>
    @fonts
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            *,:before,:after{--tw-border-style:solid;border-style:solid;border-width:0;box-sizing:border-box}:before,:after{--tw-content:""}html{line-height:1.5;-webkit-text-size-adjust:100%;-moz-tab-size:4;-o-tab-size:4;tab-size:4;font-family:ui-sans-serif,system-ui,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";font-feature-settings:normal;font-variation-settings:normal}body{margin:0;line-height:inherit}h1,h2,h3{font-size:inherit;font-weight:inherit}a{color:inherit;-webkit-text-decoration:inherit;text-decoration:inherit}b,strong{font-weight:bolder}code{font-family:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace;font-size:1em}table{text-indent:0;border-color:inherit;border-collapse:collapse}button,input,select{font-family:inherit;font-feature-settings:inherit;font-variation-settings:inherit;font-size:100%;font-weight:inherit;line-height:inherit;letter-spacing:inherit;color:inherit;margin:0;padding:0}button,select{text-transform:none}button{-webkit-appearance:button}::-webkit-inner-spin-button{height:auto}::-webkit-search-decoration{-webkit-appearance:none}blockquote,dl,dd,h1,h2,h3,hr,figure,p,pre{margin:0}fieldset{margin:0;padding:0}legend{padding:0}ol,ul{list-style:none;margin:0;padding:0}textarea{resize:vertical}input::-moz-placeholder,textarea::-moz-placeholder{opacity:1;color:#9ca3af}input::placeholder,textarea::placeholder{opacity:1;color:#9ca3af}button{cursor:pointer}canvas,img,svg,video{display:block;vertical-align:middle}img,video{max-width:100%;height:auto}
            :root{--font-sans:"Instrument Sans",ui-sans-serif,system-ui,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji"}*,:before,:after,::backdrop{--tw-translate-x:0;--tw-translate-y:0;--tw-translate-z:0;--tw-rotate-x:initial;--tw-rotate-y:initial;--tw-rotate-z:initial;--tw-skew-x:initial;--tw-skew-y:initial;--tw-space-x-reverse:0;--tw-border-style:solid;--tw-leading:initial;--tw-font-weight:initial;--tw-tracking:initial;--tw-shadow:0 0 #0000;--tw-shadow-color:initial;--tw-shadow-alpha:100%;--tw-inset-shadow:0 0 #0000;--tw-inset-shadow-color:initial;--tw-inset-shadow-alpha:100%;--tw-ring-color:initial;--tw-ring-shadow:0 0 #0000;--tw-inset-ring-color:initial;--tw-inset-ring-shadow:0 0 #0000;--tw-ring-inset:initial;--tw-ring-offset-width:0px;--tw-ring-offset-color:#fff;--tw-ring-offset-shadow:0 0 #0000;--tw-blur:initial;--tw-brightness:initial;--tw-contrast:initial;--tw-grayscale:initial;--tw-hue-rotate:initial;--tw-invert:initial;--tw-opacity:initial;--tw-saturate:initial;--tw-sepia:initial;--tw-drop-shadow:initial;--tw-drop-shadow-color:initial;--tw-drop-shadow-alpha:100%;--tw-drop-shadow-size:initial;--tw-duration:initial;--tw-ease:initial;--tw-content:""}
            body{font-family:var(--font-sans);-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}
        </style>
    @endif
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] antialiased">
    <div class="min-h-screen flex flex-col">
        {{-- Nav --}}
        <header class="w-full max-w-5xl mx-auto px-6 py-6 flex items-center justify-between">
            <a href="{{ url('/') }}" class="text-sm font-medium hover:opacity-70 transition-opacity">
                &larr; Back to app
            </a>
            <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">v{{ app()->version() }}</span>
        </header>

        {{-- Main --}}
        <main class="flex-1 w-full max-w-3xl mx-auto px-6 pb-20">
            {{-- Hero --}}
            <section class="mb-16">
                <h1 class="text-4xl font-semibold tracking-tight mb-4">Starter Kit Documentation</h1>
                <p class="text-lg text-[#706f6c] dark:text-[#A1A09A] leading-relaxed">
                    A production-ready Laravel blueprint with a choose-your-own-adventure installer.
                    Pick the features you need — everything else stays out of your way.
                </p>
            </section>

            {{-- Quick Start --}}
            <section class="mb-14">
                <h2 class="text-2xl font-semibold mb-4">Quick Start</h2>
                <div class="bg-[#F4F4F0] dark:bg-[#161615] rounded-lg p-6 mb-4">
                    <pre class="text-sm leading-relaxed overflow-x-auto"><code>composer create-project nomanur/nomanur-starter-kit my-app
cd my-app
composer run setup</code></pre>
                </div>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                    The <code class="text-[#f53003] dark:text-[#FF4433] text-xs font-medium bg-[#F4F4F0] dark:bg-[#161615] px-1.5 py-0.5 rounded">setup</code> script installs dependencies, copies your <code class="text-[#f53003] dark:text-[#FF4433] text-xs font-medium bg-[#F4F4F0] dark:bg-[#161615] px-1.5 py-0.5 rounded">.env</code>, generates an app key, and launches the interactive feature installer — then runs migrations and builds frontend assets.
                </p>
            </section>

            {{-- Interactive Installer --}}
            <section class="mb-14">
                <h2 class="text-2xl font-semibold mb-4">Interactive Installer</h2>
                <p class="text-[#706f6c] dark:text-[#A1A09A] leading-relaxed mb-4">
                    The installer uses Laravel Prompts to present a checklist of available features.
                    Select what you need, and it handles dependency resolution, file generation, and configuration.
                </p>

                <div class="bg-[#F4F4F0] dark:bg-[#161615] rounded-lg p-6 mb-4">
                    <pre class="text-sm leading-relaxed overflow-x-auto"><code>php artisan starter-kit:install</code></pre>
                </div>

                <h3 class="text-lg font-semibold mb-3">How it works</h3>
                <ol class="list-decimal list-inside space-y-2 text-sm text-[#706f6c] dark:text-[#A1A09A] leading-relaxed mb-6">
                    <li>A <code class="text-xs font-medium bg-[#F4F4F0] dark:bg-[#161615] px-1.5 py-0.5 rounded">multiselect</code> prompt shows all available features (pre-checked by default)</li>
                    <li>Dependencies are resolved automatically — selecting RBAC also installs the Admin Panel</li>
                    <li>A summary screen shows everything that will be installed</li>
                    <li>Confirm to proceed — each feature installs with a progress spinner</li>
                    <li>The Admin Panel Provider is dynamically generated — only includes plugins and middleware for selected features</li>
                    <li>Post-install steps publish package configs as needed</li>
                    <li>A final table shows installed features and next steps</li>
                </ol>

                <div class="border-l-4 border-[#f53003] dark:border-[#FF4433] bg-[#FFF5F5] dark:bg-[#1D0002] rounded-r-lg px-5 py-4 text-sm">
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">
                        <strong class="text-[#1b1b18] dark:text-[#EDEDEC]">Tip:</strong>
                        Pass <code class="text-xs font-medium bg-[#F4F4F0] dark:bg-[#161615] px-1.5 py-0.5 rounded">--force</code> to overwrite existing files during re-installation.
                    </p>
                </div>
            </section>

            {{-- Features --}}
            <section class="mb-14">
                <h2 class="text-2xl font-semibold mb-6">Available Features</h2>

                <div class="space-y-6">
                    {{-- Admin Panel --}}
                    <div class="border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg p-5">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-xs font-medium uppercase tracking-wider text-[#706f6c] dark:text-[#A1A09A]">Core</span>
                            <h3 class="text-lg font-semibold">Admin Panel</h3>
                        </div>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-3">Filament admin panel with dashboard, user management, and Amber theme. Always installed.</p>
                        <div class="flex flex-wrap gap-2 text-xs">
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">Filament v5</span>
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">Livewire v4</span>
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">Tailwind v4</span>
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">Alpine.js</span>
                        </div>
                    </div>

                    {{-- RBAC --}}
                    <div class="border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg p-5">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-xs font-medium uppercase tracking-wider text-[#f53003] dark:text-[#FF4433]">Depends on Admin Panel</span>
                            <h3 class="text-lg font-semibold">Role-Based Access Control</h3>
                        </div>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-3">Granular role/permission management via Filament Shield. Creates <code class="text-xs font-medium bg-[#F4F4F0] dark:bg-[#161615] px-1.5 py-0.5 rounded">super_admin</code> and <code class="text-xs font-medium bg-[#F4F4F0] dark:bg-[#161615] px-1.5 py-0.5 rounded">panel_user</code> roles with a Filament UI for managing permissions on all resources, pages, and widgets.</p>
                        <div class="flex flex-wrap gap-2 text-xs">
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">Filament Shield v4</span>
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">Spatie Permission v7</span>
                        </div>
                    </div>

                    {{-- Two-Factor Auth --}}
                    <div class="border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg p-5">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-xs font-medium uppercase tracking-wider text-[#f53003] dark:text-[#FF4433]">Depends on Admin Panel</span>
                            <h3 class="text-lg font-semibold">Two-Factor Authentication</h3>
                        </div>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-3">TOTP-based 2FA with inline SVG QR codes, 8 one-time recovery codes, and session-persistent middleware. Uses Google2FA with Bacon QR Code generation.</p>
                        <div class="flex flex-wrap gap-2 text-xs">
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">Google2FA</span>
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">Bacon QR Code</span>
                        </div>
                    </div>

                    {{-- Socialite --}}
                    <div class="border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg p-5">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-xs font-medium uppercase tracking-wider text-[#f53003] dark:text-[#FF4433]">Depends on Admin Panel</span>
                            <h3 class="text-lg font-semibold">Socialite OAuth Login</h3>
                        </div>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-3">Multi-provider social login with 8 pre-configured providers. A dynamic <code class="text-xs font-medium bg-[#F4F4F0] dark:bg-[#161615] px-1.5 py-0.5 rounded">SocialiteProvider</code> Enum auto-detects which providers are configured via <code class="text-xs font-medium bg-[#F4F4F0] dark:bg-[#161615] px-1.5 py-0.5 rounded">services.php</code>.</p>
                        <div class="flex flex-wrap gap-2 text-xs mb-3">
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">GitHub</span>
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">Google</span>
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">Facebook</span>
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">X (Twitter)</span>
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">LinkedIn</span>
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">GitLab</span>
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">Bitbucket</span>
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">Slack</span>
                        </div>
                        <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">All 8 providers are ready in <code class="text-xs font-medium bg-[#F4F4F0] dark:bg-[#161615] px-1.5 py-0.5 rounded">config/services.php</code>, commented out by default. Uncomment and add your credentials to enable each one.</p>
                    </div>

                    {{-- API Starter Kit --}}
                    <div class="border-2 border-[#f53003]/30 dark:border-[#FF4433]/30 rounded-lg p-5 bg-[#FFF5F5] dark:bg-[#1D0002]">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-xs font-medium uppercase tracking-wider text-[#f53003] dark:text-[#FF4433]">Separate Package</span>
                            <h3 class="text-lg font-semibold">API Starter Kit</h3>
                        </div>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-3">
                            Adds a full REST API layer with Laravel Sanctum token authentication, API resource classes,
                            versioned route structure (<code class="text-xs font-medium bg-[#F4F4F0] dark:bg-[#161615] px-1.5 py-0.5 rounded">api/v1/</code>), form request validation patterns,
                            and rate limiting. No dependency on the admin panel.
                        </p>
                        <div class="bg-[#F4F4F0] dark:bg-[#161615] rounded p-3 mb-3 text-sm">
                            <pre class="leading-relaxed"><code>composer require nomanur/api-starter-kit
php artisan api-starter-kit:install --sanctum --migrations</code></pre>
                        </div>
                        <div class="flex flex-wrap gap-2 text-xs">
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">Laravel Sanctum</span>
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">API Resources</span>
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">Rate Limiting</span>
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">Form Requests</span>
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">Versioning</span>
                        </div>
                        <p class="text-xs mt-3 text-[#706f6c] dark:text-[#A1A09A]">
                            Selecting this in the installer runs <code class="text-xs font-medium bg-[#F4F4F0] dark:bg-[#161615] px-1.5 py-0.5 rounded">composer require nomanur/api-starter-kit</code> via Symfony Process,
                            then executes <code class="text-xs font-medium bg-[#F4F4F0] dark:bg-[#161615] px-1.5 py-0.5 rounded">php artisan api-starter-kit:install --sanctum --migrations</code> automatically.
                        </p>
                    </div>

                    {{-- Multilingual --}}
                    <div class="border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg p-5">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-xs font-medium uppercase tracking-wider text-[#706f6c] dark:text-[#A1A09A]">Standalone</span>
                            <h3 class="text-lg font-semibold">Multilingual & Localization</h3>
                        </div>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-3">Spatie Translatable for Eloquent models, database-driven translation management via Spatie Translation Loader, and auto locale detection through Geo-IP with an 18+ country map. A <code class="text-xs font-medium bg-[#F4F4F0] dark:bg-[#161615] px-1.5 py-0.5 rounded">SetLocaleMiddleware</code> handles Geo-IP, browser header fallback, and session-persistent manual overrides.</p>
                        <div class="flex flex-wrap gap-2 text-xs">
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">Spatie Translatable v6</span>
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">Translation Loader v2</span>
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">Geo-Genius v1</span>
                        </div>
                    </div>

                    {{-- Media Library --}}
                    <div class="border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg p-5">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-xs font-medium uppercase tracking-wider text-[#f53003] dark:text-[#FF4433]">Depends on Admin Panel</span>
                            <h3 class="text-lg font-semibold">Media Library & Image Cropping</h3>
                        </div>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-3">Spatie Media Library with avatar collections (single-file), client-side Cropper.js integration via a reusable Alpine.js component, automatic image optimization, and a demo page at <code class="text-xs font-medium bg-[#F4F4F0] dark:bg-[#161615] px-1.5 py-0.5 rounded">/photo</code>.</p>
                        <div class="flex flex-wrap gap-2 text-xs">
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">Spatie Media Library v11</span>
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">Cropper.js</span>
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">Alpine.js</span>
                        </div>
                    </div>

                    {{-- SEO --}}
                    <div class="border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg p-5">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-xs font-medium uppercase tracking-wider text-[#f53003] dark:text-[#FF4433]">Depends on Admin Panel</span>
                            <h3 class="text-lg font-semibold">SEO Tools</h3>
                        </div>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-3">SEO analysis for Filament resources with meta tags, schema.org types, robots directives, and focus keyword tracking. Includes a <code class="text-xs font-medium bg-[#F4F4F0] dark:bg-[#161615] px-1.5 py-0.5 rounded">Post</code> model with translatable fields and SEO metadata, plus sitemap generation via <code class="text-xs font-medium bg-[#F4F4F0] dark:bg-[#161615] px-1.5 py-0.5 rounded">php artisan sitemap:generate</code>.</p>
                        <div class="flex flex-wrap gap-2 text-xs">
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">SEO Pro v0.1</span>
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">Spatie Sitemap v8</span>
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">Spatie Translatable v6</span>
                        </div>
                    </div>

                    {{-- Queue Monitor --}}
                    <div class="border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg p-5">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-xs font-medium uppercase tracking-wider text-[#f53003] dark:text-[#FF4433]">Depends on Admin Panel</span>
                            <h3 class="text-lg font-semibold">Queue Monitoring</h3>
                        </div>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-3">Real-time Filament dashboard for background jobs with configurable pruning (7-day retention). Monitor queue workers, failed jobs, and processing times directly from the admin panel.</p>
                        <div class="flex flex-wrap gap-2 text-xs">
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">Filament Jobs Monitor v4</span>
                        </div>
                    </div>

                    {{-- Log Viewer --}}
                    <div class="border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg p-5">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-xs font-medium uppercase tracking-wider text-[#f53003] dark:text-[#FF4433]">Depends on Admin Panel</span>
                            <h3 class="text-lg font-semibold">Log Viewer</h3>
                        </div>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-3">Opcodes Log Viewer integrated into the Filament sidebar under a "System" group. Access is protected by the <code class="text-xs font-medium bg-[#F4F4F0] dark:bg-[#161615] px-1.5 py-0.5 rounded">view_log_viewer</code> permission — Super Admins only.</p>
                        <div class="flex flex-wrap gap-2 text-xs">
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">Opcodes Log Viewer v3</span>
                        </div>
                    </div>

                    {{-- Import/Export --}}
                    <div class="border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg p-5">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-xs font-medium uppercase tracking-wider text-[#f53003] dark:text-[#FF4433]">Depends on Admin Panel</span>
                            <h3 class="text-lg font-semibold">Data Import / Export</h3>
                        </div>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-3">Reusable <code class="text-xs font-medium bg-[#F4F4F0] dark:bg-[#161615] px-1.5 py-0.5 rounded">ExportImport</code> trait for Filament resources. Export to CSV/XLSX with column selection, import from CSV/XLSX with auto-mapping. Import and export actions appear in the Filament table toolbar.</p>
                        <div class="flex flex-wrap gap-2 text-xs">
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">CSV Export</span>
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">CSV Import</span>
                            <span class="bg-[#F4F4F0] dark:bg-[#161615] px-2 py-1 rounded">League CSV</span>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Setup Script --}}
            <section class="mb-14">
                <h2 class="text-2xl font-semibold mb-4">The Setup Script</h2>
                <p class="text-[#706f6c] dark:text-[#A1A09A] leading-relaxed mb-4">
                    <code class="text-xs font-medium bg-[#F4F4F0] dark:bg-[#161615] px-1.5 py-0.5 rounded">composer run setup</code> orchestrates the full project initialisation:
                </p>

                <div class="space-y-3 text-sm">
                    <div class="flex items-start gap-3">
                        <span class="text-[#f53003] dark:text-[#FF4433] font-medium shrink-0 w-6">1.</span>
                        <div><code class="text-xs font-medium bg-[#F4F4F0] dark:bg-[#161615] px-1.5 py-0.5 rounded">composer install</code> — Install all PHP dependencies</div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="text-[#f53003] dark:text-[#FF4433] font-medium shrink-0 w-6">2.</span>
                        <div>Copy <code class="text-xs font-medium bg-[#F4F4F0] dark:bg-[#161615] px-1.5 py-0.5 rounded">.env.example</code> to <code class="text-xs font-medium bg-[#F4F4F0] dark:bg-[#161615] px-1.5 py-0.5 rounded">.env</code> if it doesn't exist</div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="text-[#f53003] dark:text-[#FF4433] font-medium shrink-0 w-6">3.</span>
                        <div><code class="text-xs font-medium bg-[#F4F4F0] dark:bg-[#161615] px-1.5 py-0.5 rounded">php artisan key:generate</code> — Generate application key</div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="text-[#f53003] dark:text-[#FF4433] font-medium shrink-0 w-6">4.</span>
                        <div><code class="text-xs font-medium bg-[#F4F4F0] dark:bg-[#161615] px-1.5 py-0.5 rounded">php artisan starter-kit:install</code> — Launch interactive feature installer</div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="text-[#f53003] dark:text-[#FF4433] font-medium shrink-0 w-6">5.</span>
                        <div><code class="text-xs font-medium bg-[#F4F4F0] dark:bg-[#161615] px-1.5 py-0.5 rounded">php artisan migrate --force</code> — Run all database migrations</div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="text-[#f53003] dark:text-[#FF4433] font-medium shrink-0 w-6">6.</span>
                        <div><code class="text-xs font-medium bg-[#F4F4F0] dark:bg-[#161615] px-1.5 py-0.5 rounded">npm install && npm run build</code> — Install and build frontend assets</div>
                    </div>
                </div>
            </section>

            {{-- Architecture --}}
            <section class="mb-14">
                <h2 class="text-2xl font-semibold mb-4">Architecture</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                                <th class="text-left py-3 pr-4 font-medium">Layer</th>
                                <th class="text-left py-3 pr-4 font-medium">Technology</th>
                                <th class="text-left py-3 font-medium">Version</th>
                            </tr>
                        </thead>
                        <tbody class="text-[#706f6c] dark:text-[#A1A09A]">
                            <tr class="border-b border-[#e3e3e0] dark:border-[#3E3E3A]"><td class="py-3 pr-4">PHP</td><td class="py-3 pr-4">PHP</td><td class="py-3">^8.3</td></tr>
                            <tr class="border-b border-[#e3e3e0] dark:border-[#3E3E3A]"><td class="py-3 pr-4">Framework</td><td class="py-3 pr-4">Laravel</td><td class="py-3">^13.8</td></tr>
                            <tr class="border-b border-[#e3e3e0] dark:border-[#3E3E3A]"><td class="py-3 pr-4">Admin Panel</td><td class="py-3 pr-4">Filament</td><td class="py-3">^5.6</td></tr>
                            <tr class="border-b border-[#e3e3e0] dark:border-[#3E3E3A]"><td class="py-3 pr-4">Live Components</td><td class="py-3 pr-4">Livewire</td><td class="py-3">^4.3</td></tr>
                            <tr class="border-b border-[#e3e3e0] dark:border-[#3E3E3A]"><td class="py-3 pr-4">Frontend</td><td class="py-3 pr-4">Alpine.js</td><td class="py-3">^3.15</td></tr>
                            <tr class="border-b border-[#e3e3e0] dark:border-[#3E3E3A]"><td class="py-3 pr-4">CSS</td><td class="py-3 pr-4">Tailwind CSS</td><td class="py-3">^4.0</td></tr>
                            <tr class="border-b border-[#e3e3e0] dark:border-[#3E3E3A]"><td class="py-3 pr-4">Bundler</td><td class="py-3 pr-4">Vite</td><td class="py-3">^8.0</td></tr>
                            <tr class="border-b border-[#e3e3e0] dark:border-[#3E3E3A]"><td class="py-3 pr-4">Database</td><td class="py-3 pr-4">SQLite (default)</td><td class="py-3">—</td></tr>
                            <tr class="border-b border-[#e3e3e0] dark:border-[#3E3E3A]"><td class="py-3 pr-4">Testing</td><td class="py-3 pr-4">Pest</td><td class="py-3">^4.7</td></tr>
                            <tr class="border-b border-[#e3e3e0] dark:border-[#3E3E3A]"><td class="py-3 pr-4">Static Analysis</td><td class="py-3 pr-4">PHPStan</td><td class="py-3">^2.2</td></tr>
                            <tr class="border-b border-[#e3e3e0] dark:border-[#3E3E3A]"><td class="py-3 pr-4">Code Style</td><td class="py-3 pr-4">Pint</td><td class="py-3">^1.27</td></tr>
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Development --}}
            <section class="mb-14">
                <h2 class="text-2xl font-semibold mb-4">Development</h2>

                <h3 class="text-lg font-semibold mb-2">Dev Server</h3>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">Run the full dev environment (server, queue worker, logs, and Vite) concurrently:</p>
                <div class="bg-[#F4F4F0] dark:bg-[#161615] rounded-lg p-4 mb-6">
                    <pre class="text-sm leading-relaxed overflow-x-auto"><code>composer run dev</code></pre>
                </div>

                <h3 class="text-lg font-semibold mb-2">Running Tests</h3>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">The full test suite runs linting, static analysis, type coverage (100% min), and Pest tests:</p>
                <div class="bg-[#F4F4F0] dark:bg-[#161615] rounded-lg p-4 mb-6">
                    <pre class="text-sm leading-relaxed overflow-x-auto"><code>composer test</code></pre>
                </div>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Individual test commands are also available:</p>
                <div class="bg-[#F4F4F0] dark:bg-[#161615] rounded-lg p-4">
                    <pre class="text-sm leading-relaxed overflow-x-auto"><code>composer test:lint        # Pint
composer test:refactor    # Rector (dry-run)
composer test:types       # PHPStan
composer test:arch        # Pest architecture tests
composer test:type-coverage  # 100% type coverage
composer test:unit        # Pest with coverage</code></pre>
                </div>
            </section>

            {{-- Footer --}}
            <footer class="border-t border-[#e3e3e0] dark:border-[#3E3E3A] pt-6 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                <p>Built with Laravel v{{ app()->version() }}. Licensed under MIT.</p>
            </footer>
        </main>
    </div>
</body>
</html>

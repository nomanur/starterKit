<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\outro;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\task;
use function Laravel\Prompts\warning;

class InstallStarterKit extends Command
{
    protected $signature = 'starter-kit:install {--force : Overwrite existing files without asking}';

    protected $description = 'Interactive starter kit feature installer';

    private Filesystem $filesystem;

    private array $selected = [];

    public function __construct()
    {
        parent::__construct();
        $this->filesystem = new Filesystem;
    }

    public function handle(): int
    {
        $installApi = confirm(label: 'Do you want to install the API Starter Kit?', default: true);

        $features = $this->loadFeatures();
        $selected = $this->selectFeatures($features, $installApi);

        if (empty($selected)) {
            warning('No features selected. Nothing to install.');

            return self::SUCCESS;
        }

        $this->selected = $this->resolveDependencies($features, $selected);
        $this->printSummary($features, $this->selected);

        if (! confirm(label: 'Proceed with installation?', default: true)) {
            warning('Installation cancelled.');

            return self::SUCCESS;
        }

        $this->installFeatures($features, $this->selected);

        $this->runPostInstallSteps($this->selected);

        $this->printCompletion($this->selected);

        return self::SUCCESS;
    }

    private function loadFeatures(): Collection
    {
        /** @var array<int, array<string, mixed>> $config */
        $config = config('installer.features');

        return collect($config);
    }

    private function selectFeatures(Collection $features, bool $installApi): array
    {
        $skippedFeatures = ['admin-panel'];

        if (! $installApi) {
            $skippedFeatures[] = 'api';
        }

        $options = $features
            ->reject(fn (array $f) => in_array($f['id'], $skippedFeatures))
            ->mapWithKeys(fn (array $f) => [$f['id'] => $f['name'].' — '.Str::of($f['description'])->limit(60)])
            ->all();

        $selected = multiselect(
            label: 'Which features would you like to install?',
            options: $options,
            default: array_keys($options),
            hint: 'Use space to select/deselect, enter to confirm.',
            required: false,
        );

        $selected[] = 'admin-panel';

        if ($installApi) {
            $selected[] = 'api';
        }

        return $selected;
    }

    private function resolveDependencies(Collection $features, array $selected): array
    {
        $resolved = collect($selected);

        foreach ($selected as $id) {
            $feature = $features->firstWhere('id', $id);
            if ($feature === null) {
                continue;
            }
            foreach ($feature['dependencies'] as $dep) {
                if (! $resolved->contains($dep)) {
                    $resolved->push($dep);
                    info("  Adding required dependency: {$dep}");
                }
            }
        }

        return $resolved->unique()->values()->all();
    }

    private function printSummary(Collection $features, array $selected): void
    {
        intro('Installation Summary');

        $lines = collect($selected)->map(function (string $id) use ($features): string {
            $feature = $features->firstWhere('id', $id);

            return "  • {$feature['name']} — {$feature['description']}";
        });

        $this->line($lines->implode("\n"));
        $this->newLine();
    }

    private function installFeatures(Collection $features, array $selected): void
    {
        intro('Installing features...');

        foreach ($selected as $id) {
            $method = 'install'.Str::studly($id);

            if (method_exists($this, $method)) {
                task(
                    label: "Installing {$this->featureName($features, $id)}",
                    callback: fn () => $this->{$method}($features, $this->selected),
                );
            } else {
                info("  No installer for feature: {$id} (skipping)");
            }
        }
    }

    private function featureName(Collection $features, string $id): string
    {
        $feature = $features->firstWhere('id', $id);

        return $feature['name'] ?? Str::headline($id);
    }

    private function runPostInstallSteps(array $selected): void
    {
        $steps = [];

        if (in_array('rbac', $selected)) {
            $steps[] = 'Publishing Filament Shield config...';
        }

        if (in_array('queue-monitor', $selected)) {
            $steps[] = 'Publishing queue monitor config...';
        }

        if (in_array('seo', $selected)) {
            $steps[] = 'Publishing SEO Pro config...';
        }

        if (in_array('media-library', $selected)) {
            $steps[] = 'Publishing media library config...';
        }

        if (in_array('multilingual', $selected)) {
            $steps[] = 'Publishing translation loader config...';
            $steps[] = 'Publishing Geo-Genius config...';
        }

        if (empty($steps)) {
            return;
        }

        $this->newLine();
        intro('Running post-install steps...');

        foreach ($steps as $step) {
            $this->line("  {$step}");
        }
    }

    private function publishConfig(string $tag): void
    {
        $this->call('vendor:publish', [
            '--tag' => $tag,
            '--force' => $this->option('force'),
        ]);
    }

    private function copyStub(string $stub, string $destination): void
    {
        $stubPath = base_path("installer/stubs/{$stub}");

        if (! $this->filesystem->exists($stubPath)) {
            warning("  Stub not found: {$stub}");

            return;
        }

        $targetDir = dirname(base_path($destination));

        if (! $this->filesystem->exists($targetDir)) {
            $this->filesystem->makeDirectory($targetDir, 0755, true);
        }

        if ($this->filesystem->exists(base_path($destination)) && ! $this->option('force')) {
            warning("  File exists, skipping: {$destination}");

            return;
        }

        $this->filesystem->copy($stubPath, base_path($destination));
    }

    private function appendToEnv(string $key, string $value): void
    {
        $envPath = base_path('.env');

        if (! $this->filesystem->exists($envPath)) {
            return;
        }

        $contents = $this->filesystem->get($envPath);

        if (Str::contains($contents, "{$key}=")) {
            return;
        }

        $this->filesystem->append($envPath, "\n{$key}={$value}\n");
    }

    private function appendToFile(string $path, string $content): void
    {
        $fullPath = base_path($path);

        if (! $this->filesystem->exists($fullPath)) {
            return;
        }

        $this->filesystem->append($fullPath, "\n".$content."\n");
    }

    private function ensureDirectoriesExist(): void
    {
        $dirs = [
            'app/Filament/Resources/Users/Pages',
            'app/Filament/Resources/Users/Schemas',
            'app/Filament/Resources/Users/Tables',
            'app/Filament/Resources/Posts/Pages',
            'app/Filament/Resources/Posts/Schemas',
            'app/Filament/Resources/Posts/Tables',
            'app/Filament/Resources/LanguageLines/Pages',
            'app/Filament/Resources/LanguageLines/Schemas',
            'app/Filament/Resources/LanguageLines/Tables',
            'app/Filament/Pages',
            'app/Filament/Forms/Components',
            'app/Http/Middleware',
            'app/Http/Controllers/Auth',
            'app/Enums',
            'app/Traits',
            'app/Livewire',
        ];

        foreach ($dirs as $dir) {
            $path = base_path($dir);
            if (! $this->filesystem->exists($path)) {
                $this->filesystem->makeDirectory($path, 0755, true);
            }
        }
    }

    protected function installAdminPanel(Collection $features, ?array $selected = null): void
    {
        $this->ensureDirectoriesExist();

        $providersDir = base_path('app/Providers/Filament');

        if (! $this->filesystem->exists($providersDir)) {
            $this->filesystem->makeDirectory($providersDir, 0755, true);
        }

        $selected ??= [];

        $this->generatePanelProvider($selected);

        $this->copyStub('UserResource.php', 'app/Filament/Resources/Users/UserResource.php');
        $this->copyStub('UserForm.php', 'app/Filament/Resources/Users/Schemas/UserForm.php');
        $this->copyStub('UsersTable.php', 'app/Filament/Resources/Users/Tables/UsersTable.php');
        $this->copyStub('ListUsers.php', 'app/Filament/Resources/Users/Pages/ListUsers.php');
        $this->copyStub('CreateUser.php', 'app/Filament/Resources/Users/Pages/CreateUser.php');
        $this->copyStub('EditUser.php', 'app/Filament/Resources/Users/Pages/EditUser.php');
    }

    private function generatePanelProvider(array $selected): void
    {
        $hasRbac = in_array('rbac', $selected);
        $hasQueueMonitor = in_array('queue-monitor', $selected);
        $hasSeo = in_array('seo', $selected);
        $hasLogViewer = in_array('log-viewer', $selected);
        $hasTwoFactor = in_array('two-factor', $selected);

        $plugins = [];

        if ($hasRbac) {
            $plugins[] = '                FilamentShieldPlugin::make(),';
        }

        if ($hasQueueMonitor) {
            $plugins[] = '                FilamentJobsMonitorPlugin::make(),';
        }

        if ($hasSeo) {
            $plugins[] = '                SeoPlugin::make()';
            $plugins[] = '                    ->enableDashboardWidget(false),';
        }

        $pluginImports = [];
        $authMiddleware = ['                Authenticate::class,'];
        $middlewareExtras = [];

        if ($hasRbac) {
            $pluginImports[] = 'use BezhanSalleh\FilamentShield\FilamentShieldPlugin;';
        }

        if ($hasQueueMonitor) {
            $pluginImports[] = 'use Croustibat\FilamentJobsMonitor\FilamentJobsMonitorPlugin;';
        }

        if ($hasSeo) {
            $pluginImports[] = 'use Nomanur\FilamentSeoPro\SeoPlugin;';
        }

        if ($hasTwoFactor) {
            $authMiddleware[] = '                TwoFactorMiddleware::class,';
        }

        $navigationItems = '';

        if ($hasLogViewer) {
            $navigationItems = <<<'PHP'

            ->navigationItems([
                NavigationItem::make('Log Viewer')
                    ->url(fn (): string => url('log-viewer'))
                    ->icon('heroicon-o-document-text')
                    ->group('System')
                    ->visible(fn (): bool => auth()->user()?->can('view_log_viewer') ?? false),
            ])
PHP;
        }

        $pluginsSection = empty($plugins)
            ? ''
            : "\n".implode("\n", $plugins)."\n        ";

        $pluginImportsSection = empty($pluginImports)
            ? ''
            : "\n".implode("\n", $pluginImports);

        $twoFactorImport = $hasTwoFactor
            ? "\nuse App\Http\Middleware\TwoFactorMiddleware;"
            : '';

        $content = <<<PHP
<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;{$twoFactorImport}{$pluginImportsSection}

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel \$panel): Panel
    {
        return \$panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ]){$navigationItems}
            ->plugins([{$pluginsSection}])
            ->authMiddleware([
{$authMiddleware[0]}
            ]);
    }
}
PHP;

        $this->filesystem->put(
            base_path('app/Providers/Filament/AdminPanelProvider.php'),
            $content,
        );
    }

    protected function installRbac(): void
    {
        spin(
            callback: function (): void {
                $this->publishConfig('filament-shield-config');
                $this->call('vendor:publish', [
                    '--tag' => 'filament-shield-config',
                    '--force' => $this->option('force'),
                ]);
            },
            message: 'Publishing RBAC configuration...',
        );
    }

    protected function installTwoFactor(): void
    {
        $this->copyStub('TwoFactorMiddleware.php', 'app/Http/Middleware/TwoFactorMiddleware.php');
        $this->copyStub('TwoFactorChallenge.php', 'app/Filament/Pages/TwoFactorChallenge.php');

        $this->appendToEnv('TWO_FACTOR_ENABLED', 'true');
    }

    protected function installSocialite(): void
    {
        $this->copyStub('SocialiteController.php', 'app/Http/Controllers/Auth/SocialiteController.php');
        $this->copyStub('SocialiteProvider.php', 'app/Enums/SocialiteProvider.php');

        $providers = [
            'GITHUB_CLIENT_ID', 'GITHUB_CLIENT_SECRET',
            'GOOGLE_CLIENT_ID', 'GOOGLE_CLIENT_SECRET',
            'FACEBOOK_CLIENT_ID', 'FACEBOOK_CLIENT_SECRET',
            'TWITTER_CLIENT_ID', 'TWITTER_CLIENT_SECRET',
            'LINKEDIN_CLIENT_ID', 'LINKEDIN_CLIENT_SECRET',
            'GITLAB_CLIENT_ID', 'GITLAB_CLIENT_SECRET',
            'BITBUCKET_CLIENT_ID', 'BITBUCKET_CLIENT_SECRET',
            'SLACK_CLIENT_ID', 'SLACK_CLIENT_SECRET',
        ];

        foreach ($providers as $key) {
            $this->appendToEnv($key, '');
        }

        $routes = base_path('routes/web.php');
        $socialiteRoutes = <<<'PHP'
use App\Http\Controllers\Auth\SocialiteController;
use App\Enums\SocialiteProvider;

Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/{provider}/redirect', [SocialiteController::class, 'redirect'])->whereIn('provider', SocialiteProvider::values())->name('socialite.redirect');
    Route::get('/{provider}/callback', [SocialiteController::class, 'callback'])->whereIn('provider', SocialiteProvider::values())->name('socialite.callback');
});

PHP;
        $this->appendToFile('routes/web.php', $socialiteRoutes);
    }

    protected function installMultilingual(): void
    {
        $this->copyStub('SetLocaleMiddleware.php', 'app/Http/Middleware/SetLocaleMiddleware.php');
        $this->copyStub('Translatable.php', 'app/Filament/Forms/Components/Translatable.php');

        $this->copyStub('LanguageLineResource.php', 'app/Filament/Resources/LanguageLines/LanguageLineResource.php');
        $this->copyStub('LanguageLineForm.php', 'app/Filament/Resources/LanguageLines/Schemas/LanguageLineForm.php');
        $this->copyStub('LanguageLinesTable.php', 'app/Filament/Resources/LanguageLines/Tables/LanguageLinesTable.php');
        $this->copyStub('ListLanguageLines.php', 'app/Filament/Resources/LanguageLines/Pages/ListLanguageLines.php');
        $this->copyStub('CreateLanguageLine.php', 'app/Filament/Resources/LanguageLines/Pages/CreateLanguageLine.php');
        $this->copyStub('EditLanguageLine.php', 'app/Filament/Resources/LanguageLines/Pages/EditLanguageLine.php');

        $this->publishConfig('translation-loader-config');
        $this->publishConfig('laravel-geo-genius-config');

        $this->appendToEnv('APP_LOCALE', 'en');
        $this->appendToEnv('APP_FALLBACK_LOCALE', 'en');
    }

    protected function installMediaLibrary(): void
    {
        $this->copyStub('LivewireTest.php', 'app/Livewire/Test.php');
        $this->copyStub('PhotoPage.php', 'app/Filament/Pages/PhotoPage.php');

        $this->publishConfig('media-library-config');

        $routes = base_path('routes/web.php');
        $mediaRoutes = <<<'PHP'
use App\Livewire\Test;

Route::get('/photo', Test::class)->name('photo');

PHP;
        $this->appendToFile('routes/web.php', $mediaRoutes);
    }

    protected function installSeo(): void
    {
        $this->copyStub('Post.php', 'app/Models/Post.php');
        $this->copyStub('PostResource.php', 'app/Filament/Resources/Posts/PostResource.php');
        $this->copyStub('PostForm.php', 'app/Filament/Resources/Posts/Schemas/PostForm.php');
        $this->copyStub('PostsTable.php', 'app/Filament/Resources/Posts/Tables/PostsTable.php');
        $this->copyStub('ListPosts.php', 'app/Filament/Resources/Posts/Pages/ListPosts.php');
        $this->copyStub('CreatePost.php', 'app/Filament/Resources/Posts/Pages/CreatePost.php');
        $this->copyStub('EditPost.php', 'app/Filament/Resources/Posts/Pages/EditPost.php');

        $this->publishConfig('filament-seo-pro-config');
    }

    protected function installQueueMonitor(): void
    {
        $this->publishConfig('filament-jobs-monitor-config');
    }

    protected function installLogViewer(): void
    {
        $this->publishConfig('log-viewer-config');
    }

    protected function installImportExport(): void
    {
        $this->copyStub('ExportImport.php', 'app/Traits/ExportImport.php');
    }

    protected function installApi(): void
    {

        $this->runComposerRequire('nomanur/api-starter-kit');

        $this->line('  Running: php artisan api-starter-kit:install...');

        $process = new Process(
            command: [
                PHP_BINARY,
                'artisan',
                'api-starter-kit:install',
                '--sanctum',
                '--migrations',
                '--force',
                '--no-interaction',
                '--ansi',
            ],
            cwd: base_path(),
            timeout: 180,
        );

        try {
            $process->mustRun(function (string $type, string $output): void {
                if ($type === Process::OUT) {
                    echo $output;
                }
            });
        } catch (\Throwable $e) {
            warning('API Starter Kit installation may have encountered issues: '.$e->getMessage());
        }
    }

    private function runComposerRequire(string $package): void
    {
        $this->line("  Running: composer require {$package}...");

        $process = new Process(
            command: ['composer', 'require', $package, '--no-interaction', '--ansi'],
            cwd: base_path(),
            timeout: 300,
        );

        $process->mustRun(function (string $type, string $output): void {
            if ($type === Process::OUT) {
                echo $output;
            }
        });
    }

    private function printCompletion(array $selected): void
    {
        $this->newLine();
        outro('Installation complete!');

        $this->table(
            ['Feature', 'Status'],
            collect($selected)->map(fn (string $id) => [
                Str::headline($id),
                'Installed',
            ])->all(),
        );

        $this->newLine();
        info('Next steps:');
        $this->line('  1. Run php artisan migrate to create database tables');
        $this->line('  2. Configure your .env file with any required credentials');
        $this->line('  3. Visit /admin and create your first admin user');
        $this->line('  4. Run npm install && npm run build for frontend assets');
        $this->newLine();
    }
}

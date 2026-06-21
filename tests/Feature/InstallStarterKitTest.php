<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

beforeEach(function (): void {
    $this->providerBackupPath = base_path('app/Providers/Filament/AdminPanelProvider.php.bak');
    $this->providerPath = base_path('app/Providers/Filament/AdminPanelProvider.php');

    if (File::exists($this->providerPath)) {
        File::copy($this->providerPath, $this->providerBackupPath);
    }
});

afterEach(function (): void {
    if (isset($this->providerBackupPath) && File::exists($this->providerBackupPath)) {
        File::move($this->providerBackupPath, $this->providerPath);
    }
});

function buildChoices(array $skipIds = ['admin-panel']): array
{
    $features = collect(config('installer.features'));
    $options = $features
        ->reject(fn (array $f) => in_array($f['id'], $skipIds))
        ->mapWithKeys(fn (array $f) => [$f['id'] => $f['name'].' — '.Str::of($f['description'])->limit(60)])
        ->all();

    $labels = array_values($options);
    sort($labels);

    $keys = array_keys($options);
    sort($keys);

    return array_merge($labels, $keys);
}

test('it exits when no features are selected', function (): void {
    $choices = buildChoices(['admin-panel', 'api']);

    $this->artisan('starter-kit:install')
        ->expectsConfirmation('Do you want to install the API Starter Kit?', 'no')
        ->expectsChoice('Which features would you like to install?', [], $choices)
        ->expectsConfirmation('Proceed with installation?', 'no')
        ->expectsOutputToContain('Installation cancelled')
        ->assertExitCode(0);
});

test('it exits when installation is cancelled', function (): void {
    $choices = buildChoices(['admin-panel', 'api']);

    $this->artisan('starter-kit:install')
        ->expectsConfirmation('Do you want to install the API Starter Kit?', 'no')
        ->expectsChoice('Which features would you like to install?', ['log-viewer'], $choices)
        ->expectsConfirmation('Proceed with installation?', 'no')
        ->expectsOutputToContain('Installation cancelled')
        ->assertExitCode(0);
});

test('it skips api starter kit when declined', function (): void {
    $choices = buildChoices(['admin-panel', 'api']);

    $this->artisan('starter-kit:install')
        ->expectsConfirmation('Do you want to install the API Starter Kit?', 'no')
        ->expectsChoice('Which features would you like to install?', [], $choices)
        ->expectsConfirmation('Proceed with installation?', 'yes')
        ->expectsOutputToContain('Installation complete!')
        ->assertExitCode(0);
});

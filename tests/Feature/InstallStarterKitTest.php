<?php

use Illuminate\Support\Str;

test('it exits when no features are selected', function (): void {
    $features = collect(config('installer.features'));
    $options = $features
        ->reject(fn (array $f) => in_array($f['id'], ['admin-panel']))
        ->mapWithKeys(fn (array $f) => [$f['id'] => $f['name'].' — '.Str::of($f['description'])->limit(60)])
        ->all();

    $labels = array_values($options);
    sort($labels);

    $keys = array_keys($options);
    sort($keys);

    $choices = array_merge($labels, $keys);

    $this->artisan('starter-kit:install')
        ->expectsChoice('Which features would you like to install?', [], $choices)
        ->expectsConfirmation('Proceed with installation?', 'no')
        ->expectsOutputToContain('Installation cancelled')
        ->assertExitCode(0);
});

test('it exits when installation is cancelled', function (): void {
    $features = collect(config('installer.features'));
    $options = $features
        ->reject(fn (array $f) => in_array($f['id'], ['admin-panel']))
        ->mapWithKeys(fn (array $f) => [$f['id'] => $f['name'].' — '.Str::of($f['description'])->limit(60)])
        ->all();

    $labels = array_values($options);
    sort($labels);

    $keys = array_keys($options);
    sort($keys);

    $choices = array_merge($labels, $keys);

    $this->artisan('starter-kit:install')
        ->expectsChoice('Which features would you like to install?', ['log-viewer'], $choices)
        ->expectsConfirmation('Proceed with installation?', 'no')
        ->expectsOutputToContain('Installation cancelled')
        ->assertExitCode(0);
});

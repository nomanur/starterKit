<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

test('sitemap generate artisan command works and outputs correct file', function (): void {
    $sitemapPath = public_path('sitemap.xml');

    // Clean up if exists
    if (File::exists($sitemapPath)) {
        File::delete($sitemapPath);
    }

    // Run command
    Artisan::call('sitemap:generate');

    // Assert file exists
    expect(File::exists($sitemapPath))->toBeTrue();

    // Assert content has correct elements
    $content = File::get($sitemapPath);
    expect($content)->toContain('<urlset')
        ->toContain('http://starterkit.test')
        ->toContain('http://starterkit.test/photo')
        ->toContain('http://starterkit.test/posts');
});

test('robots.txt contains sitemap reference and crawling rules', function (): void {
    $robotsPath = public_path('robots.txt');

    expect(File::exists($robotsPath))->toBeTrue();

    $content = File::get($robotsPath);
    expect($content)->toContain('User-agent: *')
        ->toContain('Disallow: /admin/')
        ->toContain('Disallow: /log-viewer/')
        ->toContain('Sitemap: http://starterkit.test/sitemap.xml');
});

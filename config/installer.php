<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Starter Kit Features
    |--------------------------------------------------------------------------
    |
    | Each feature can be toggled during `php artisan starter-kit:install`.
    | Features may declare dependencies on other features.
    |
    */

    'features' => [
        [
            'id' => 'admin-panel',
            'name' => 'Admin Panel',
            'description' => 'Filament admin panel with dashboard, user management, and Amber theme',
            'dependencies' => [],
            'published' => false,
        ],
        [
            'id' => 'rbac',
            'name' => 'Role-Based Access Control',
            'description' => 'Granular role/permission management via Filament Shield with super_admin and panel_user roles',
            'dependencies' => ['admin-panel'],
            'published' => false,
        ],
        [
            'id' => 'two-factor',
            'name' => 'Two-Factor Authentication',
            'description' => 'TOTP-based 2FA with recovery codes, QR code setup, and session-persistent middleware',
            'dependencies' => ['admin-panel'],
            'published' => false,
        ],
        [
            'id' => 'socialite',
            'name' => 'Socialite OAuth Login',
            'description' => 'Multi-provider social login (GitHub, Google, Facebook, X/Twitter, LinkedIn, GitLab, Bitbucket, Slack)',
            'dependencies' => ['admin-panel'],
            'published' => false,
        ],
        [
            'id' => 'multilingual',
            'name' => 'Multilingual & Localization',
            'description' => 'Spatie Translatable models, database-driven translations, and Geo-IP locale auto-detection',
            'dependencies' => [],
            'published' => false,
        ],
        [
            'id' => 'media-library',
            'name' => 'Media Library & Image Cropping',
            'description' => 'Spatie Media Library with avatars collection, client-side Cropper.js integration, and image optimization',
            'dependencies' => ['admin-panel'],
            'published' => false,
        ],
        [
            'id' => 'seo',
            'name' => 'SEO Tools',
            'description' => 'SEO analysis for Filament resources with meta tags, schema.org types, and sitemap generation',
            'dependencies' => ['admin-panel'],
            'published' => false,
        ],
        [
            'id' => 'queue-monitor',
            'name' => 'Queue Monitoring',
            'description' => 'Filament dashboard for monitoring background jobs with configurable pruning',
            'dependencies' => ['admin-panel'],
            'published' => false,
        ],
        [
            'id' => 'log-viewer',
            'name' => 'Log Viewer',
            'description' => 'Opcodes Log Viewer integrated into the Filament sidebar with permission-protected access',
            'dependencies' => ['admin-panel'],
            'published' => false,
        ],
        [
            'id' => 'import-export',
            'name' => 'Data Import / Export',
            'description' => 'CSV/XLSX import and export for Filament resources with column selection',
            'dependencies' => ['admin-panel'],
            'published' => false,
        ],
        [
            'id' => 'api',
            'name' => 'API Starter Kit',
            'description' => 'REST API with Sanctum auth, API resources, versioning, rate limiting, and form request patterns',
            'dependencies' => [],
            'published' => false,
        ],
    ],

];

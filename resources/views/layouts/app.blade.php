<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
            $seoModel = $model ?? null;
        @endphp

        <!-- Basic Meta Tags -->
        <title>{{ $seoModel?->seo?->title ?? $seoModel?->title ?? $title ?? config('app.name', 'Laravel Starter') }}</title>
        <meta name="description" content="{{ $seoModel?->seo?->description ?? ($seoModel?->content ? Str::limit(strip_tags($seoModel->content), 150) : '') }}">
        @if($seoModel?->seo?->keywords)
            <meta name="keywords" content="{{ $seoModel->seo->keywords }}">
        @endif
        <meta name="robots" content="{{ $seoModel?->seo?->robots ?? 'index, follow' }}">
        <link rel="canonical" href="{{ $seoModel?->seo?->canonical_url ?? request()->url() }}">

        <!-- Open Graph (Facebook / LinkedIn) -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ request()->url() }}">
        <meta property="og:title" content="{{ $seoModel?->seo?->og_title ?? $seoModel?->seo?->title ?? $seoModel?->title ?? $title ?? config('app.name', 'Laravel Starter') }}">
        <meta property="og:description" content="{{ $seoModel?->seo?->og_description ?? $seoModel?->seo?->description ?? '' }}">
        @if($seoModel?->seo?->og_image)
            <meta property="og:image" content="{{ Storage::disk(config('filament.default_filesystem_disk', 'public'))->url($seoModel->seo->og_image) }}">
        @endif

        <!-- Twitter Cards -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:url" content="{{ request()->url() }}">
        <meta name="twitter:title" content="{{ $seoModel?->seo?->twitter_title ?? $seoModel?->seo?->title ?? $seoModel?->title ?? $title ?? config('app.name', 'Laravel Starter') }}">
        <meta name="twitter:description" content="{{ $seoModel?->seo?->twitter_description ?? $seoModel?->seo?->description ?? '' }}">
        @if($seoModel?->seo?->twitter_image)
            <meta name="twitter:image" content="{{ Storage::disk(config('filament.default_filesystem_disk', 'public'))->url($seoModel->seo->twitter_image) }}">
        @endif

        <!-- Schema.org JSON-LD Markup -->
        @if($seoModel?->seo?->schema_type)
            <script type="application/ld+json">
            {
                "@@context": "https://schema.org",
                "@@type": "{{ $seoModel->seo->schema_type }}",
                "mainEntityOfPage": {
                    "@@type": "WebPage",
                    "@@id": "{{ request()->url() }}"
                },
                "headline": "{{ $seoModel?->seo?->title ?? $seoModel?->title }}",
                "description": "{{ $seoModel?->seo?->description ?? '' }}"
            }
            </script>
        @endif

        <!-- Fonts -->
        @fonts

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full antialiased bg-slate-950 text-slate-100 flex flex-col justify-between min-h-screen">
        <!-- Navigation bar -->
        <header class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 border-b border-slate-900">
            <div class="flex items-center justify-between">
                <a href="{{ url('/') }}" class="flex items-center gap-2 group">
                    <span class="w-8 h-8 rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 flex items-center justify-center font-bold text-white shadow-md shadow-indigo-600/15 group-hover:scale-105 transition-transform duration-300">
                        S
                    </span>
                    <span class="text-sm font-semibold tracking-tight text-white group-hover:text-slate-300 transition-colors">Starter Kit</span>
                </a>

                <nav class="flex items-center gap-4">
                    <a href="{{ url('/') }}" class="text-xs text-slate-400 hover:text-slate-200 transition-colors">Home</a>
                    <a href="{{ route('test') }}" class="text-xs font-semibold px-3 py-1.5 bg-slate-900 border border-slate-800 rounded-lg text-indigo-400 hover:bg-slate-800 hover:text-indigo-300 transition-all duration-300 {{ request()->routeIs('test') ? 'border-indigo-500/30 bg-indigo-500/5' : '' }}">
                        Media Library Demo
                    </a>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 py-8 px-4 sm:px-6 lg:px-8">
            @isset($slot)
                {{ $slot }}
            @else
                @yield('content')
            @endisset
        </main>

        <!-- Footer -->
        <footer class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 border-t border-slate-900 text-center text-xs text-slate-600">
            <p>&copy; {{ date('Y') }} Starter Kit. Built with Laravel + Livewire + Spatie Media Library.</p>
        </footer>
    </body>
</html>

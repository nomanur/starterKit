<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Laravel Starter') }}</title>

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
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 border-t border-slate-900 text-center text-xs text-slate-600">
            <p>&copy; {{ date('Y') }} Starter Kit. Built with Laravel + Livewire + Spatie Media Library.</p>
        </footer>
    </body>
</html>

@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-10 text-left">
        <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl">
            Our <span class="bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">Journal</span>
        </h1>
        <p class="mt-3 text-lg text-slate-400 max-w-2xl">
            Explore our latest articles, insights, and thoughts on software development and design.
        </p>
    </div>

    <!-- Posts Grid -->
    @if ($posts->isEmpty())
        <div class="text-center py-16 bg-slate-900/20 rounded-2xl border border-slate-800 border-dashed">
            <svg class="mx-auto h-12 w-12 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4a2 2 0 00-2-2v8a2 2 0 01-2 2h-3l-1 1-1-1H5m14-10V4a2 2 0 00-2-2h-3" />
            </svg>
            <h3 class="mt-4 text-sm font-semibold text-white">No posts</h3>
            <p class="mt-1 text-sm text-slate-500">Check back later for new articles.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($posts as $post)
                <article class="flex flex-col justify-between p-6 bg-slate-900/40 backdrop-blur-md rounded-2xl border border-slate-800 hover:border-indigo-500/30 hover:bg-slate-900/60 transition-all duration-300 shadow-xl group">
                    <div>
                        <!-- Date & Category -->
                        <div class="flex items-center gap-3 text-xs text-slate-500 mb-4">
                            <time datetime="{{ $post->created_at?->toIso8601String() }}">
                                {{ $post->created_at?->format('M d, Y') ?? 'Recently' }}
                            </time>
                            <span class="w-1 h-1 rounded-full bg-slate-800"></span>
                            <span class="px-2 py-0.5 rounded-full bg-indigo-500/10 text-indigo-400 font-medium">Article</span>
                        </div>

                        <!-- Title -->
                        <h3 class="text-xl font-bold text-white group-hover:text-indigo-400 transition-colors duration-200">
                            {{ $post->title }}
                        </h3>

                        <!-- Content -->
                        <p class="mt-3 text-sm text-slate-400 line-clamp-3 leading-relaxed">
                            {{ strip_tags($post->content ?? '') }}
                        </p>
                    </div>

                    <!-- Read More link -->
                    <div class="mt-6 pt-4 border-t border-slate-900 flex items-center justify-between">
                        <span class="text-xs font-semibold text-indigo-400 group-hover:text-indigo-300 transition-colors inline-flex items-center gap-1.5">
                            Read full story
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </span>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>
@endsection


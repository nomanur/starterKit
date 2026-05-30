<div>
    @if (count($providers) > 0)
        @if ($heading)
            <div class="flex items-center gap-4 my-6">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent via-slate-700 to-transparent"></div>
                <span class="text-slate-500 text-xs font-medium tracking-wide uppercase shrink-0">{{ $heading }}</span>
                <div class="h-px flex-1 bg-gradient-to-r from-transparent via-slate-700 to-transparent"></div>
            </div>
        @endif

        <div class="grid {{ $gridClass }} gap-3">
            @foreach ($providers as $provider)
                <a
                    href="{{ route('socialite.redirect', $provider->value) }}"
                    id="socialite-btn-{{ $provider->value }}"
                    class="group flex items-center justify-center {{ $buttonClass }} bg-slate-900/60 hover:bg-slate-800/80 border border-slate-700/50 hover:border-slate-600/80 rounded-xl text-slate-300 hover:text-white font-medium transition-all duration-300 backdrop-blur-sm"
                >
                    <span style="color: {{ $provider->color() }}" class="shrink-0 transition-transform duration-300 group-hover:scale-110">
                        {!! $provider->icon() !!}
                    </span>
                    @if ($showLabels)
                        <span>{{ $provider->label() }}</span>
                    @endif
                </a>
            @endforeach
        </div>
    @endif
</div>

@props(['title', 'subtitle' => null, 'stats' => null])

<div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl px-5 py-4 md:px-6 md:py-5 shadow-md mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-white/20 flex items-center justify-center text-white flex-shrink-0">
                {{-- Generic icon --}}
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xl md:text-2xl font-bold text-white leading-tight">{{ $title }}</h2>
                @if($subtitle)
                    <p class="text-blue-100/90 text-sm mt-0.5">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
        @if($stats)
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-black/20 backdrop-blur-sm rounded-full text-white text-sm font-medium">
                <svg class="w-4 h-4 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span>{{ $stats }}</span>
            </div>
        @endif
    </div>
</div>

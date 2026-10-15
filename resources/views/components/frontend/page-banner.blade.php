@props([
    'title'       => '',
    'subtitle'    => '',
    'description' => null,
])

<section class="relative overflow-hidden bg-gradient-to-br from-[#0a1f44] via-[#12307e] to-[#1d4ed8] text-white">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(rgba(255,255,255,0.4) 1px, transparent 1px); background-size: 28px 28px;"></div>
    <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full bg-blue-400/20 blur-3xl"></div>
    <div class="absolute -bottom-20 -left-16 w-72 h-72 rounded-full bg-indigo-400/20 blur-3xl"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
        <div class="max-w-3xl">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-sm text-blue-200/70 mb-4">
                <a href="{{ url('/') }}" class="hover:text-white transition inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Home
                </a>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-white font-medium">{{ $title }}</span>
            </nav>

            <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight">{{ $title }}</h1>
            @if($subtitle)
                <p class="mt-4 text-lg text-blue-100/80 max-w-2xl">{{ $subtitle }}</p>
            @endif
            @if($description)
                <p class="mt-3 text-blue-200/60">{{ $description }}</p>
            @endif
        </div>
    </div>

    {{-- Wave divider --}}
    <svg class="block w-full text-white -mb-px" viewBox="0 0 1440 40" fill="currentColor" preserveAspectRatio="none"><path d="M0,20 C360,40 1080,0 1440,20 L1440,40 L0,40 Z"/></svg>
</section>

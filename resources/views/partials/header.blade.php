{{-- Modern frontend header --}}
@php
    $isActive = fn($pattern) => request()->is($pattern);
    $navLink = fn($active) => 'px-4 py-2.5 text-[0.92rem] transition-all rounded-xl ' . ($active
        ? 'bg-[#1d4ed8] text-white font-bold shadow-md shadow-blue-600/25'
        : 'text-slate-600 hover:text-[#1d4ed8] hover:bg-blue-50 font-medium');
@endphp

{{-- Top utility bar --}}
<div class="hidden md:block bg-[#0a1f44] text-slate-300 text-xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-9 flex items-center justify-between">
        <div class="flex items-center gap-5">
            @if($siteSettings->site_email ?? null)
                <a href="mailto:{{ $siteSettings->site_email }}" class="inline-flex items-center gap-1.5 hover:text-white transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    {{ $siteSettings->site_email }}
                </a>
            @endif
            @if($siteSettings->site_phone ?? null)
                <a href="tel:{{ $siteSettings->site_phone }}" class="inline-flex items-center gap-1.5 hover:text-white transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    {{ $siteSettings->site_phone }}
                </a>
            @endif
        </div>
        <div class="flex items-center gap-4">
            <span class="inline-flex items-center gap-1.5 text-green-400">
                <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span>
                Available for new projects
            </span>
        </div>
    </div>
</div>

<header class="bg-white/95 backdrop-blur-md sticky top-0 z-50 border-b border-slate-100 shadow-[0_2px_20px_rgba(29,78,216,0.08)]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-[76px]">
            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 shrink-0 group">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $siteSettings->site_name ?? 'AK Tech SOL' }}" class="h-12 w-auto object-contain group-hover:scale-105 transition-transform">
                @else
                    <span class="h-11 w-11 rounded-2xl bg-gradient-to-br from-[#1d4ed8] to-[#12307e] text-white font-extrabold flex items-center justify-center text-xl shadow-lg shadow-blue-600/25">A</span>
                    <span class="leading-tight">
                        <span class="block font-extrabold text-[1.05rem] text-slate-900">AK Tech SOL</span>
                        <span class="block text-[0.65rem] font-semibold text-slate-400 tracking-[0.18em] uppercase">Digital Agency</span>
                    </span>
                @endif
            </a>

            {{-- Desktop nav --}}
            <nav class="hidden lg:flex items-center gap-0.5 bg-slate-50/80 rounded-2xl px-2 py-1.5 border border-slate-100">
                <a href="{{ url('/') }}" class="{{ $navLink($isActive('/')) }}">Home</a>
                <a href="{{ url('/services') }}" class="{{ $navLink($isActive('services*')) }}">Services</a>
                <a href="{{ route('pricing') }}" class="{{ $navLink($isActive('pricing')) }}">Pricing</a>
                <a href="{{ url('/portfolio') }}" class="{{ $navLink($isActive('portfolio*')) }}">Portfolio</a>
                <a href="{{ url('/blog') }}" class="{{ $navLink($isActive('blog*')) }}">Blog</a>
                <a href="{{ url('/about') }}" class="{{ $navLink($isActive('about')) }}">About</a>
                <a href="{{ url('/faqs') }}" class="{{ $navLink($isActive('faqs*')) }}">FAQs</a>
                <a href="{{ url('/contact') }}" class="{{ $navLink($isActive('contact')) }}">Contact</a>
            </nav>

            <div class="flex items-center gap-3">
                <a href="{{ route('quote.create') }}" class="hidden sm:inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-gradient-to-r from-[#1d4ed8] to-[#2f6fed] text-white text-sm font-bold hover:shadow-xl hover:shadow-blue-600/30 hover:-translate-y-0.5 transition-all">
                    Get a Quote
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                <button id="mobile-menu-toggle" class="lg:hidden w-11 h-11 rounded-2xl bg-slate-50 hover:bg-slate-100 text-slate-700 flex items-center justify-center transition border border-slate-100" aria-label="Menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div id="mobile-menu" class="lg:hidden hidden border-t border-slate-100 bg-white px-4 py-4 space-y-1 max-h-[70vh] overflow-y-auto">
        @foreach([
            ['/', 'Home', $isActive('/')],
            ['/services', 'Services', $isActive('services*')],
            ['/pricing', 'Pricing', $isActive('pricing')],
            ['/portfolio', 'Portfolio', $isActive('portfolio*')],
            ['/blog', 'Blog', $isActive('blog*')],
            ['/about', 'About', $isActive('about')],
            ['/faqs', 'FAQs', $isActive('faqs*')],
            ['/contact', 'Contact', $isActive('contact')],
        ] as [$href, $label, $active])
            <a href="{{ url($href) }}" class="flex items-center justify-between px-4 py-3 rounded-2xl text-[0.95rem] {{ $active ? 'bg-blue-50 text-[#1d4ed8] font-bold' : 'text-slate-600 font-medium hover:bg-slate-50' }}">
                {{ $label }}
                @if($active)
                    <span class="w-1.5 h-1.5 rounded-full bg-[#1d4ed8]"></span>
                @endif
            </a>
        @endforeach
        <a href="{{ route('quote.create') }}" class="block px-4 py-3.5 rounded-2xl bg-gradient-to-r from-[#1d4ed8] to-[#2f6fed] text-white text-center font-bold mt-2">Get a Quote</a>
    </div>
</header>

<script>
    document.getElementById('mobile-menu-toggle')?.addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });
</script>

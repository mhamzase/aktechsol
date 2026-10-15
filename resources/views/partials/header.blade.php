{{-- Modern frontend header --}}
@php
    $navLink = fn($active) => $active
        ? 'text-[#1d4ed8] font-semibold'
        : 'text-slate-600 hover:text-[#1d4ed8] font-medium';
    $isActive = fn($pattern) => request()->is($pattern);
@endphp
<header class="bg-white/95 backdrop-blur-md sticky top-0 z-50 border-b border-slate-100 shadow-[0_1px_12px_rgba(29,78,216,0.06)]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-[72px]">
            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 shrink-0">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $siteSettings->site_name ?? 'AK Tech SOL' }}" class="h-11 w-auto object-contain">
                @else
                    <span class="h-10 w-10 rounded-xl bg-gradient-to-br from-[#1d4ed8] to-[#12307e] text-white font-extrabold flex items-center justify-center text-lg">A</span>
                    <span class="font-extrabold text-lg text-slate-900">AK Tech SOL</span>
                @endif
            </a>

            {{-- Desktop nav --}}
            <nav class="hidden lg:flex items-center gap-1">
                <a href="{{ url('/') }}" class="px-4 py-2 rounded-lg text-[0.92rem] transition-colors {{ $navLink($isActive('/')) }}">Home</a>
                <a href="{{ url('/services') }}" class="px-4 py-2 rounded-lg text-[0.92rem] transition-colors {{ $navLink($isActive('services*')) }}">Services</a>
                <a href="{{ url('/portfolio') }}" class="px-4 py-2 rounded-lg text-[0.92rem] transition-colors {{ $navLink($isActive('portfolio*')) }}">Portfolio</a>
                <a href="{{ url('/blog') }}" class="px-4 py-2 rounded-lg text-[0.92rem] transition-colors {{ $navLink($isActive('blog*')) }}">Blog</a>
                <a href="{{ url('/about') }}" class="px-4 py-2 rounded-lg text-[0.92rem] transition-colors {{ $navLink($isActive('about')) }}">About</a>
                <a href="{{ url('/faqs') }}" class="px-4 py-2 rounded-lg text-[0.92rem] transition-colors {{ $navLink($isActive('faqs*')) }}">FAQs</a>
                <a href="{{ url('/contact') }}" class="px-4 py-2 rounded-lg text-[0.92rem] transition-colors {{ $navLink($isActive('contact')) }}">Contact</a>
            </nav>

            <div class="flex items-center gap-3">
                <a href="{{ url('/contact') }}" class="hidden sm:inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#1d4ed8] text-white text-sm font-bold hover:bg-[#1669d9] transition shadow-lg shadow-blue-600/20">
                    Get a Quote
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                <button id="mobile-menu-toggle" class="lg:hidden w-10 h-10 rounded-xl hover:bg-slate-100 text-slate-700 flex items-center justify-center transition" aria-label="Menu">
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
            ['/portfolio', 'Portfolio', $isActive('portfolio*')],
            ['/blog', 'Blog', $isActive('blog*')],
            ['/about', 'About', $isActive('about')],
            ['/faqs', 'FAQs', $isActive('faqs*')],
            ['/contact', 'Contact', $isActive('contact')],
        ] as [$href, $label, $active])
            <a href="{{ url($href) }}" class="block px-4 py-2.5 rounded-xl text-[0.95rem] {{ $active ? 'bg-blue-50 text-[#1d4ed8] font-bold' : 'text-slate-600 font-medium hover:bg-slate-50' }}">{{ $label }}</a>
        @endforeach
        <a href="{{ url('/contact') }}" class="block px-4 py-3 rounded-xl bg-[#1d4ed8] text-white text-center font-bold mt-2">Get a Quote</a>
    </div>
</header>

<script>
    document.getElementById('mobile-menu-toggle')?.addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });
</script>

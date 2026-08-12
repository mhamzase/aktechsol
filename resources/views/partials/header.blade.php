<header class="bg-gradient-to-br from-blue-600 to-blue-800 shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            {{-- Logo & site name --}}
            <a href="{{ url('/') }}" class="flex items-center gap-2 text-white hover:opacity-80 transition-opacity">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $siteSettings->site_name ?? 'AK Tech SOL' }}" class="h-8 w-auto object-contain">
                @endif
                <span class="text-xl font-bold text-white">{{ $siteSettings->site_name ?? 'AK Tech SOL' }}</span>
            </a>

            {{-- Desktop nav --}}
            <nav class="hidden md:flex items-center space-x-1">
                <a href="{{ url('/') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->is('/') ? 'bg-white/20 text-white shadow' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                    Home
                </a>
                <a href="{{ url('/about') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->is('about') ? 'bg-white/20 text-white shadow' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                    About
                </a>
                <a href="{{ url('/services') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->is('services*') ? 'bg-white/20 text-white shadow' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                    Services
                </a>
                <a href="{{ url('/portfolio') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->is('portfolio*') ? 'bg-white/20 text-white shadow' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                    Portfolio
                </a>
                <a href="{{ url('/blog') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->is('blog*') ? 'bg-white/20 text-white shadow' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                    Blog
                </a>
                <a href="{{ url('/contact') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->is('contact') ? 'bg-white/20 text-white shadow' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                    Contact
                </a>
            </nav>

            {{-- Mobile menu button --}}
            <button id="mobile-menu-toggle" class="md:hidden text-white hover:text-blue-200 focus:outline-none">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div id="mobile-menu" class="md:hidden hidden px-4 pb-4 pt-2 space-y-1 bg-blue-800/95">
        <a href="{{ url('/') }}"
           class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->is('/') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
            Home
        </a>
        <a href="{{ url('/about') }}"
           class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->is('about') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
            About
        </a>
        <a href="{{ url('/services') }}"
           class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->is('services*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
            Services
        </a>
        <a href="{{ url('/portfolio') }}"
           class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->is('portfolio*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
            Portfolio
        </a>
        <a href="{{ url('/blog') }}"
           class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->is('blog*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
            Blog
        </a>
        <a href="{{ url('/contact') }}"
           class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->is('contact') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
            Contact
        </a>
    </div>
</header>

<script>
    document.getElementById('mobile-menu-toggle').addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });
</script>

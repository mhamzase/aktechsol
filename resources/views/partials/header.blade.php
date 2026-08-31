<header class="bg-white shadow-md sticky top-0 z-50 border-b border-blue-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center gap-2 text-blue-700 hover:opacity-80 transition-opacity">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $siteSettings->site_name ?? 'AK Tech SOL' }}" class="h-8 w-auto object-contain">
                @endif
                {{-- <span class="text-xl font-bold text-blue-700">{{ $siteSettings->site_name ?? 'AK Tech SOL' }}</span> --}}
            </a>

            {{-- Desktop nav --}}
            <nav class="hidden md:flex items-center space-x-1">
                <a href="{{ url('/') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->is('/') ? 'bg-blue-600 text-white shadow' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}">
                    Home
                </a>
                <a href="{{ url('/services') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->is('services*') ? 'bg-blue-600 text-white shadow' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}">
                    Services
                </a>
                <a href="{{ url('/portfolio') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->is('portfolio*') ? 'bg-blue-600 text-white shadow' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}">
                    Portfolio
                </a>
                <a href="{{ url('/blog') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->is('blog*') ? 'bg-blue-600 text-white shadow' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}">
                    Blog
                </a>

                {{-- More Dropdown --}}
                <div class="relative group">
                    <button type="button"
                            class="flex items-center gap-1 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->is('about') || request()->is('contact') || request()->is('faqs*') || request()->is('privacy-policy') || request()->is('terms-conditions') ? 'bg-blue-600 text-white shadow' : 'text-gray-600 group-hover:bg-blue-50 group-hover:text-blue-700' }}">
                        More
                        <svg class="h-4 w-4 transition-transform duration-200 group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-blue-100 py-1 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <a href="{{ url('/about') }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                            About
                        </a>
                        <a href="{{ url('/contact') }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                            Contact
                        </a>
                        <a href="{{ url('/faqs') }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                            FAQs
                        </a>
                        <div class="border-t border-blue-50 my-1"></div>
                        <a href="{{ url('/privacy-policy') }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                            Privacy Policy
                        </a>
                        <a href="{{ url('/terms-conditions') }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                            Terms & Conditions
                        </a>
                    </div>
                </div>
            </nav>

            {{-- Mobile menu button --}}
            <button id="mobile-menu-toggle" class="md:hidden text-blue-700 hover:text-blue-500 focus:outline-none">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div id="mobile-menu" class="md:hidden hidden px-4 pb-4 pt-2 space-y-1 bg-white border-t border-blue-100">
        <a href="{{ url('/') }}"
           class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->is('/') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}">
            Home
        </a>
        <a href="{{ url('/services') }}"
           class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->is('services*') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}">
            Services
        </a>
        <a href="{{ url('/portfolio') }}"
           class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->is('portfolio*') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}">
            Portfolio
        </a>
        <a href="{{ url('/blog') }}"
           class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->is('blog*') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}">
            Blog
        </a>

        {{-- More section --}}
        <div class="space-y-1 pl-2">
            <p class="px-3 py-1 text-xs font-semibold uppercase tracking-widest text-blue-400">More</p>
            <a href="{{ url('/about') }}"
               class="block px-4 py-2 rounded-lg text-base font-medium {{ request()->is('about') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}">
                About
            </a>
            <a href="{{ url('/contact') }}"
               class="block px-4 py-2 rounded-lg text-base font-medium {{ request()->is('contact') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}">
                Contact
            </a>
            <a href="{{ url('/faqs') }}"
               class="block px-4 py-2 rounded-lg text-base font-medium {{ request()->is('faqs*') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}">
                FAQs
            </a>
            <a href="{{ url('/privacy-policy') }}"
               class="block px-4 py-2 rounded-lg text-base font-medium {{ request()->is('privacy-policy') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}">
                Privacy Policy
            </a>
            <a href="{{ url('/terms-conditions') }}"
               class="block px-4 py-2 rounded-lg text-base font-medium {{ request()->is('terms-conditions') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}">
                Terms & Conditions
            </a>
        </div>
    </div>
</header>

<script>
    document.getElementById('mobile-menu-toggle').addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });
</script>

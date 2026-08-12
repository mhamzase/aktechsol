<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $siteSettings->site_name ?? 'AK Tech SOL')</title>
    <meta name="description" content="@yield('meta_description', 'AK Tech SOL - Professional Software & Freelancing Agency')">

    {{-- Favicon --}}
    @if($faviconUrl)
        <link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
    @endif

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body class="font-sans antialiased text-gray-800 bg-white">

    {{-- Header / Navigation --}}
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo & site name --}}
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $siteSettings->site_name ?? 'AK Tech SOL' }}" class="h-8 w-auto">
                    @endif
                    <span class="text-xl font-bold text-gray-900">{{ $siteSettings->site_name ?? 'AK Tech SOL' }}</span>
                </a>

                {{-- Desktop nav --}}
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="{{ url('/') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 transition">Home</a>
                    <a href="{{ url('/about') }}" class="text-sm font-medium text-gray-500 hover:text-gray-800 transition">About</a>
                    <a href="{{ url('/services') }}" class="text-sm font-medium text-gray-500 hover:text-gray-800 transition">Services</a>
                    <a href="{{ url('/portfolio') }}" class="text-sm font-medium text-gray-500 hover:text-gray-800 transition">Portfolio</a>
                    <a href="{{ url('/blog') }}" class="text-sm font-medium text-gray-500 hover:text-gray-800 transition">Blog</a>
                    <a href="{{ url('/contact') }}" class="text-sm font-medium text-gray-500 hover:text-gray-800 transition">Contact</a>
                </nav>

                {{-- Mobile menu button --}}
                <button id="mobile-menu-toggle" class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile menu (hidden by default) --}}
        <div id="mobile-menu" class="md:hidden hidden px-4 pb-4 pt-2 space-y-2">
            <a href="{{ url('/') }}" class="block px-3 py-2 rounded-md text-base font-medium text-blue-600 bg-blue-50">Home</a>
            <a href="{{ url('/about') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-500 hover:bg-gray-50">About</a>
            <a href="{{ url('/services') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-500 hover:bg-gray-50">Services</a>
            <a href="{{ url('/portfolio') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-500 hover:bg-gray-50">Portfolio</a>
            <a href="{{ url('/blog') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-500 hover:bg-gray-50">Blog</a>
            <a href="{{ url('/contact') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-500 hover:bg-gray-50">Contact</a>
        </div>
    </header>

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-gray-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- About / Site Name --}}
                <div>
                    <h3 class="text-white font-semibold text-lg mb-4">{{ $siteSettings->site_name ?? 'AK Tech SOL' }}</h3>
                    <p class="text-sm">{{ $siteSettings->footer_text ?? 'Professional Software & Freelancing Agency.' }}</p>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h3 class="text-white font-semibold text-lg mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ url('/') }}" class="hover:text-white transition">Home</a></li>
                        <li><a href="{{ url('/about') }}" class="hover:text-white transition">About</a></li>
                        <li><a href="{{ url('/services') }}" class="hover:text-white transition">Services</a></li>
                        <li><a href="{{ url('/portfolio') }}" class="hover:text-white transition">Portfolio</a></li>
                        <li><a href="{{ url('/blog') }}" class="hover:text-white transition">Blog</a></li>
                        <li><a href="{{ url('/contact') }}" class="hover:text-white transition">Contact</a></li>
                    </ul>
                </div>

                {{-- Social & Contact --}}
                <div>
                    <h3 class="text-white font-semibold text-lg mb-4">Connect</h3>
                    <div class="flex space-x-4 mb-4">
                        @if($siteSettings->facebook_url)
                            <a href="{{ $siteSettings->facebook_url }}" target="_blank" class="hover:text-white transition">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                        @endif
                        @if($siteSettings->twitter_url)
                            <a href="{{ $siteSettings->twitter_url }}" target="_blank" class="hover:text-white transition">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                            </a>
                        @endif
                        @if($siteSettings->linkedin_url)
                            <a href="{{ $siteSettings->linkedin_url }}" target="_blank" class="hover:text-white transition">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            </a>
                        @endif
                        @if($siteSettings->instagram_url)
                            <a href="{{ $siteSettings->instagram_url }}" target="_blank" class="hover:text-white transition">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </a>
                        @endif
                    </div>
                    @if($siteSettings->site_email)
                        <p class="text-sm"><a href="mailto:{{ $siteSettings->site_email }}" class="hover:text-white transition">{{ $siteSettings->site_email }}</a></p>
                    @endif
                    @if($siteSettings->site_phone)
                        <p class="text-sm mt-1"><a href="tel:{{ $siteSettings->site_phone }}" class="hover:text-white transition">{{ $siteSettings->site_phone }}</a></p>
                    @endif
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-gray-800 text-center text-sm">
                <p>{{ $siteSettings->copyright_text ?? '© '.date('Y').' AK Tech SOL. All rights reserved.' }}</p>
            </div>
        </div>
    </footer>

    {{-- Mobile menu JavaScript --}}
    <script>
        document.getElementById('mobile-menu-toggle').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
    @stack('scripts')
</body>
</html>

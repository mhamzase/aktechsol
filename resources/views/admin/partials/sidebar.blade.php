{{--
    Admin sidebar — grouped navigation with count badges.
    Badge counts come from App\Support\AdminNav (shared via view composer).
    Active state: white pill. All icons inline SVG (no external deps).
--}}
@php
    $badges = $navBadges ?? [];
    $isActive = fn ($pattern) => request()->routeIs($pattern) ? 'active' : '';
    $badge = fn ($key) => ($badges[$key] ?? 0) > 0
        ? '<span class="nav-badge">' . $badges[$key] . '</span>' : '';
@endphp

<aside id="admin-sidebar"
       class="admin-sidebar fixed lg:static z-40 w-64 h-screen shrink-0 -translate-x-full lg:translate-x-0 transition-transform duration-200 flex flex-col">
    {{-- Logo --}}
    <div class="flex items-center justify-between px-5 h-16 border-b border-white/10">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
            @if (!empty($logoUrl))
                <img src="{{ $logoUrl }}" alt="Logo" class="h-9 w-9 rounded-lg object-contain bg-white/10 p-1">
            @else
                <span class="h-9 w-9 rounded-lg bg-white text-[#12307e] font-extrabold flex items-center justify-center text-lg">A</span>
            @endif
            <span class="text-white font-bold text-[0.95rem] leading-tight">AK Tech Sol<br><span class="text-[0.65rem] font-medium text-white/50 tracking-widest">ADMIN PANEL</span></span>
        </a>
        <button class="lg:hidden text-white/70 hover:text-white" onclick="toggleSidebar()" aria-label="Close menu">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-5 text-[0.85rem]">

        <div class="space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ $isActive('admin.dashboard') }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.notifications.index') }}" class="nav-link {{ $isActive('admin.notifications.*') }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                Notifications {!! $badge('notifications') !!}
            </a>
        </div>

        @canany(['manage quotes', 'manage proposals'])
        <div>
            <p class="nav-group-label px-3 mb-2">Sales</p>
            <div class="space-y-1">
                @can('manage quotes')
                <a href="{{ route('admin.quotes.index') }}" class="nav-link {{ $isActive('admin.quotes.*') }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Quote Requests {!! $badge('quotes') !!}
                </a>
                @endcan
                @can('manage proposals')
                <a href="{{ route('admin.proposals.index') }}" class="nav-link {{ $isActive('admin.proposals.*') }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Proposals {!! $badge('proposals') !!}
                </a>
                @endcan
            </div>
        </div>
        @endcanany

        @canany(['manage projects', 'manage invoices', 'manage reviews'])
        <div>
            <p class="nav-group-label px-3 mb-2">Work</p>
            <div class="space-y-1">
                @can('manage projects')
                <a href="{{ route('admin.client-projects.index') }}" class="nav-link {{ $isActive('admin.client-projects.*') }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    Client Projects {!! $badge('projects') !!}
                </a>
                @endcan
                @can('manage invoices')
                <a href="{{ route('admin.invoices.index') }}" class="nav-link {{ $isActive('admin.invoices.*') }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Invoices {!! $badge('invoices') !!}
                </a>
                @endcan
                @can('manage reviews')
                <a href="{{ route('admin.reviews.index') }}" class="nav-link {{ $isActive('admin.reviews.*') }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    Reviews
                </a>
                @endcan
            </div>
        </div>
        @endcanany

        @canany(['manage chatbot', 'manage demo bookings', 'manage contact messages', 'manage job applications', 'manage newsletter'])
        <div>
            <p class="nav-group-label px-3 mb-2">Engagement</p>
            <div class="space-y-1">
                @can('manage chatbot')
                <a href="{{ route('admin.chatbot.index') }}" class="nav-link {{ $isActive('admin.chatbot.*') }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    Chatbot {!! $badge('chatbot') !!}
                </a>
                @endcan
                @can('manage demo bookings')
                <a href="{{ route('admin.demo-bookings.index') }}" class="nav-link {{ $isActive('admin.demo-bookings.*') }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Demo Bookings {!! $badge('demos') !!}
                </a>
                @endcan
                @can('manage contact messages')
                <a href="{{ route('admin.contact-messages.index') }}" class="nav-link {{ $isActive('admin.contact-messages.*') }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Contact Messages {!! $badge('contact') !!}
                </a>
                @endcan
                @can('manage job applications')
                <a href="{{ route('admin.job-applications.index') }}" class="nav-link {{ $isActive('admin.job-applications.*') }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Job Applications {!! $badge('jobs') !!}
                </a>
                @endcan
                @can('manage newsletter')
                <a href="{{ route('admin.newsletter-subscribers.index') }}" class="nav-link {{ $isActive('admin.newsletter-subscribers.*') }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    Newsletter
                </a>
                @endcan
            </div>
        </div>
        @endcanany

        @canany(['manage services', 'manage packages', 'manage portfolio', 'manage testimonials', 'manage blog', 'manage faqs', 'manage jobs', 'manage pages'])
        <div>
            <p class="nav-group-label px-3 mb-2">Content</p>
            <div class="space-y-1">
                @can('manage services')
                <a href="{{ route('admin.services.index') }}" class="nav-link {{ $isActive('admin.services.*') }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Services
                </a>
                <a href="{{ route('admin.service-categories.index') }}" class="nav-link {{ $isActive('admin.service-categories.*') }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5.586a1 1 0 01.707.293l7.414 7.414a1 1 0 010 1.414l-5.414 5.414a1 1 0 01-1.414 0L5.879 10.12a1 1 0 01-.293-.707V7a2 2 0 012-2z"/></svg>
                    Service Categories
                </a>
                @endcan
                @can('manage packages')
                <a href="{{ route('admin.packages.index') }}" class="nav-link {{ $isActive('admin.packages.*') }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Packages
                </a>
                @endcan
                @can('manage portfolio')
                <a href="{{ route('admin.projects.index') }}" class="nav-link {{ $isActive('admin.projects.*') }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Portfolio
                </a>
                @endcan
                @can('manage testimonials')
                <a href="{{ route('admin.testimonials.index') }}" class="nav-link {{ $isActive('admin.testimonials.*') }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    Testimonials
                </a>
                @endcan
                @can('manage blog')
                <a href="{{ route('admin.blog-posts.index') }}" class="nav-link {{ $isActive('admin.blog-posts.*') }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Blog Posts
                </a>
                @endcan
                @can('manage faqs')
                <a href="{{ route('admin.faqs.index') }}" class="nav-link {{ $isActive('admin.faqs.*') }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    FAQs
                </a>
                @endcan
                @can('manage jobs')
                <a href="{{ route('admin.jobs.index') }}" class="nav-link {{ $isActive('admin.jobs.*') }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Jobs
                </a>
                @endcan
                @can('manage pages')
                <a href="{{ route('admin.pages.index') }}" class="nav-link {{ $isActive('admin.pages.*') }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Pages
                </a>
                @endcan
            </div>
        </div>
        @endcanany

        @canany(['manage media', 'manage users', 'manage settings', 'view activity log'])
        <div>
            <p class="nav-group-label px-3 mb-2">System</p>
            <div class="space-y-1">
                @can('manage media')
                <a href="{{ route('admin.media.index') }}" class="nav-link {{ $isActive('admin.media.*') }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Media Library
                </a>
                @endcan
                @can('manage users')
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ $isActive('admin.users.*') }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Users & Roles
                </a>
                @endcan
                @can('view activity log')
                <a href="{{ route('admin.activity-log.index') }}" class="nav-link {{ $isActive('admin.activity-log.*') }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Activity Log
                </a>
                @endcan
                @can('manage settings')
                <a href="{{ route('admin.settings') }}" class="nav-link {{ $isActive('admin.settings') }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Settings
                </a>
                @endcan
            </div>
        </div>
        @endcanany
    </nav>
    {{-- App version (update when Hamza asks) --}}
    <div class="px-4 py-3 border-t border-white/10">
        <p class="text-[0.65rem] font-semibold text-white/40 text-center tracking-wider">v1.0.0</p>
    </div>
</aside>

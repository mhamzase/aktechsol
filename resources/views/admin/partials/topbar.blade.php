{{--
    Admin topbar — breadcrumbs (left), notifications bell + user menu (right).
    $navBadges / $navNotifications come from the admin view composer.
--}}
@php
    $unreadCount = $navBadges['notifications'] ?? 0;
    $latestNotes = $navNotifications ?? collect();
@endphp
<header class="admin-topbar h-[4.25rem] flex items-center gap-3 px-4 lg:px-6 shrink-0">
    {{-- Left: mobile menu + breadcrumbs --}}
    <div class="flex items-center gap-3 min-w-0">
        <button class="lg:hidden w-10 h-10 rounded-xl hover:bg-blue-50 text-[#1d4ed8] flex items-center justify-center transition-colors" onclick="toggleSidebar()" aria-label="Open menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <nav class="hidden sm:flex items-center gap-1.5 min-w-0 text-sm" aria-label="Breadcrumb">
            <a href="{{ route('admin.dashboard') }}" class="crumb shrink-0 hover:text-[#1d4ed8] transition-colors" title="Dashboard">
                <svg class="w-[1.05rem] h-[1.05rem]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            </a>
            @hasSection('breadcrumbs')
                <svg class="w-3.5 h-3.5 text-slate-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                @yield('breadcrumbs')
            @endif
        </nav>
    </div>

    <div class="flex-1"></div>

    {{-- Global search (Ctrl+K) --}}
    <div class="relative hidden lg:block w-60 xl:w-72 shrink-0" id="topbar-search">
        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </span>
        <input id="global-search-input" type="text" placeholder="Search pages…" autocomplete="off"
               class="w-full rounded-md bg-slate-100/90 border border-transparent py-2 pl-10 pr-14 text-sm text-slate-700 placeholder:text-slate-400 focus:bg-white focus:border-[#1d4ed8] focus:ring-4 focus:ring-blue-100 focus:outline-none transition-all">
        <kbd class="absolute right-3 top-1/2 -translate-y-1/2 text-[0.62rem] font-bold text-slate-400 bg-white border border-slate-200 rounded-md px-1.5 py-0.5 pointer-events-none">Ctrl K</kbd>
        <div id="global-search-results"
             class="hidden absolute right-0 left-0 mt-2 bg-white rounded-xl shadow-xl border border-slate-100 z-50 overflow-hidden py-1.5"></div>
    </div>

    {{-- Right: theme toggle + bell + user --}}
    <div class="flex items-center gap-1.5 shrink-0">
        {{-- Dark / light mode toggle --}}
        <button onclick="toggleTheme()" id="theme-toggle-btn" aria-label="Toggle dark mode" title="Dark / light mode"
                class="w-10 h-10 rounded-md hover:bg-slate-100 flex items-center justify-center text-slate-500 hover:text-[#1d4ed8] transition-colors">
            <svg id="theme-icon-moon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
            <svg id="theme-icon-sun" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        </button>
        {{-- Notifications bell --}}
        <div class="relative">
            <button onclick="toggleDropdown('topbar-notifications')" data-dropdown-toggle
                    class="relative w-10 h-10 rounded-md hover:bg-slate-100 flex items-center justify-center text-slate-500 hover:text-[#1d4ed8] transition-colors"
                    aria-label="Notifications">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                @if ($unreadCount > 0)
                    <span class="bell-dot absolute top-1 right-1 min-w-[1.15rem] h-[1.15rem] px-1 rounded-full bg-red-500 text-white text-[0.62rem] font-bold flex items-center justify-center">
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>
                @endif
            </button>
            <div id="topbar-notifications" data-dropdown
                 class="hidden absolute right-0 mt-2 w-80 max-w-[90vw] bg-white rounded-xl shadow-xl border border-slate-100 z-50 overflow-hidden">
                <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100">
                    <p class="font-bold text-sm text-slate-800">Notifications</p>
                    <form method="POST" action="{{ route('admin.notifications.read-all') }}">
                        @csrf
                        <button class="text-xs font-semibold text-[#1d4ed8] hover:underline">Mark all read</button>
                    </form>
                </div>
                <div class="max-h-80 overflow-y-auto">
                    @forelse ($latestNotes as $note)
                        <a href="{{ route('admin.notifications.index') }}"
                           class="flex gap-3 px-4 py-3 hover:bg-slate-50 border-b border-slate-50">
                            <span class="w-2 h-2 rounded-full bg-[#1d4ed8] mt-1.5 shrink-0"></span>
                            <span>
                                <span class="block text-[0.8rem] text-slate-700">{{ $note->data['message'] ?? $note->type }}</span>
                                <span class="block text-[0.68rem] text-slate-400 mt-0.5">{{ $note->created_at->diffForHumans() }}</span>
                            </span>
                        </a>
                    @empty
                        <p class="px-4 py-8 text-center text-sm text-slate-400">You're all caught up.</p>
                    @endforelse
                </div>
                <a href="{{ route('admin.notifications.index') }}"
                   class="block text-center text-xs font-semibold text-[#1d4ed8] py-2.5 bg-slate-50 hover:bg-slate-100">
                    View all notifications
                </a>
            </div>
        </div>

        <div class="w-px h-8 bg-slate-200/80 mx-1 hidden sm:block"></div>

        {{-- User menu --}}
        <div class="relative">
            <button onclick="toggleDropdown('topbar-user')" data-dropdown-toggle
                    class="flex items-center gap-2 pl-1 pr-2 py-1 rounded-md hover:bg-slate-100 transition-all">
                <span class="w-9 h-9 rounded-full bg-gradient-to-br from-[#1d4ed8] to-[#12307e] text-white flex items-center justify-center font-bold text-sm overflow-hidden ring-2 ring-blue-100">
                    @if (auth()->user()->getAvatarUrl())
                        <img src="{{ auth()->user()->getAvatarUrl() }}" alt="Avatar" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    @endif
                </span>
                <span class="hidden md:block text-left leading-tight">
                    <span class="block font-bold text-[0.8rem] text-slate-800 max-w-[9rem] truncate">{{ auth()->user()->name }}</span>
                    <span class="block text-[0.65rem] text-slate-400">{{ auth()->user()->getRoleNames()->first() ?? 'Team' }}</span>
                </span>
                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div id="topbar-user" data-dropdown class="hidden absolute right-0 mt-2 w-60 bg-white rounded-xl shadow-xl border border-slate-100 py-2 z-50 overflow-hidden">
                <div class="px-4 py-2.5 border-b border-slate-100 mb-1">
                    <p class="font-bold text-sm text-slate-800 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email }}</p>
                </div>
                <a href="{{ route('admin.profile') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Profile
                </a>
                <a href="{{ route('admin.change-password') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                    Change Password
                </a>
                <div class="border-t border-slate-100 my-1"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2.5 text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

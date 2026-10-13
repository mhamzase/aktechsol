{{--
    Admin topbar — breadcrumbs (left), notifications bell + user menu (right).
    $navBadges / $navNotifications come from the admin view composer.
--}}
@php
    $unreadCount = $navBadges['notifications'] ?? 0;
    $latestNotes = $navNotifications ?? collect();
@endphp
<header class="admin-topbar h-16 flex items-center justify-between px-4 lg:px-6 shrink-0">
    {{-- Left: mobile menu + breadcrumbs --}}
    <div class="flex items-center gap-3 min-w-0">
        <button class="lg:hidden text-[#1d4ed8] hover:text-[#12307e]" onclick="toggleSidebar()" aria-label="Open menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <nav class="hidden sm:flex items-center gap-1.5 min-w-0" aria-label="Breadcrumb">
            <a href="{{ route('admin.dashboard') }}" class="crumb hover:text-[#1d4ed8] shrink-0">Dashboard</a>
            @hasSection('breadcrumbs')
                <span class="text-slate-300 shrink-0">/</span>
                @yield('breadcrumbs')
            @endif
        </nav>
    </div>

    {{-- Right: bell + user --}}
    <div class="flex items-center gap-2">
        {{-- Notifications bell --}}
        <div class="relative">
            <button onclick="toggleDropdown('topbar-notifications')" data-dropdown-toggle
                    class="relative w-10 h-10 rounded-full hover:bg-slate-100 flex items-center justify-center text-slate-500 hover:text-[#1d4ed8] transition-colors"
                    aria-label="Notifications">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                @if ($unreadCount > 0)
                    <span class="absolute top-1 right-1 min-w-[1.15rem] h-[1.15rem] px-1 rounded-full bg-red-500 text-white text-[0.62rem] font-bold flex items-center justify-center">
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

        {{-- User menu --}}
        <div class="relative">
            <button onclick="toggleDropdown('topbar-user')" data-dropdown-toggle
                    class="flex items-center gap-2 pl-1.5 pr-2.5 py-1.5 rounded-full border border-slate-200 bg-white hover:border-blue-300 hover:bg-blue-50/50 transition-all">
                <span class="w-8 h-8 rounded-full bg-gradient-to-br from-[#1d4ed8] to-[#12307e] text-white flex items-center justify-center font-bold text-sm overflow-hidden">
                    @if (auth()->user()->getAvatarUrl())
                        <img src="{{ auth()->user()->getAvatarUrl() }}" alt="Avatar" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    @endif
                </span>
                <span class="hidden sm:inline font-semibold text-sm text-slate-700 max-w-[8rem] truncate">{{ auth()->user()->name }}</span>
                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div id="topbar-user" data-dropdown class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-slate-100 py-2 z-50">
                <a href="{{ route('admin.profile') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Profile</a>
                <a href="{{ route('admin.change-password') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Change Password</a>
                <div class="border-t border-slate-100 my-1"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2.5 text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</button>
                </form>
            </div>
        </div>
    </div>
</header>

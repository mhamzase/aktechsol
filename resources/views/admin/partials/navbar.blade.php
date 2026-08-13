<header
    class="bg-gray-50 border-b border-blue-200 h-16 flex items-center justify-between px-4 lg:px-6 shadow-lg shadow-blue-100/50">
    <div class="flex items-center gap-3">
        <button class="lg:hidden text-blue-600 hover:text-blue-800 transition-colors" onclick="toggleSidebar()">
            <x-icons.menu />
        </button>
        @hasSection('breadcrumbs')
            <div class="hidden sm:flex items-center">
                @yield('breadcrumbs')
            </div>
        @endif
    </div>

    <div class="relative" id="user-dropdown-container">
        <button onclick="toggleUserDropdown()"
            class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-full border border-gray-200 bg-white hover:border-blue-300 hover:bg-blue-50/50 shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-blue-200">
            <div
                class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 text-white flex items-center justify-center font-semibold text-sm shadow-sm overflow-hidden">
                @if (auth()->user()->getAvatarUrl())
                    <img src="{{ auth()->user()->getAvatarUrl() }}" alt="Avatar" class="w-full h-full object-cover">
                @else
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                @endif
            </div>
            <span class="hidden sm:inline font-medium text-sm text-gray-700">{{ auth()->user()->name }}</span>
            <svg id="user-dropdown-chevron" class="h-4 w-4 text-gray-400 transition-transform duration-200"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div id="user-dropdown-menu"
            class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50 hidden">
            <a href="{{ route('admin.profile') }}"
                class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Profile
            </a>
            <a href="{{ route('admin.change-password') }}"
                class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                Change Password
            </a>
            <div class="border-t border-gray-100 my-1"></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-2.5 text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </div>
</header>

<script>
    function toggleUserDropdown() {
        const menu = document.getElementById('user-dropdown-menu');
        menu.classList.toggle('hidden');
    }

    // Close dropdown when clicking outside
    window.addEventListener('click', function(e) {
        const container = document.getElementById('user-dropdown-container');
        if (!container.contains(e.target)) {
            const menu = document.getElementById('user-dropdown-menu');
            if (!menu.classList.contains('hidden')) {
                menu.classList.add('hidden');
            }
        }
    });
</script>

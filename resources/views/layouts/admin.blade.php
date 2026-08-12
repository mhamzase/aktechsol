<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') | AK Tech SOL Admin</title>

    {{-- Favicon --}}
    @if ($faviconUrl)
        <link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
    @else
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @endif

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>

<body class="bg-gray-100 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar overlay (mobile) -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden lg:hidden"
            onclick="toggleSidebar()"></div>

        <!-- Sidebar (blue gradient, same as page banner) -->
        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-40 w-64  bg-blue-800 shadow-xl transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out lg:static lg:inset-auto lg:z-auto">
            <div class="flex items-center justify-between h-16 px-4 border-b border-white/10">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-2 text-white hover:opacity-80 transition-opacity">
                    @if ($logoUrl)
                        <img src="{{ $logoUrl }}" alt="Logo" class="h-8 w-auto object-contain">
                    @endif
                    <span class="text-xl font-bold text-white">AK Tech SOL</span>
                </a>
                <button class="lg:hidden text-white/70 hover:text-white" onclick="toggleSidebar()">
                    <x-icons.x />
                </button>
            </div>

            <nav class="mt-4 px-3 space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                    <x-icons.home />
                    <span class="ml-3">Dashboard</span>
                </a>

                <a href="{{ route('admin.services.index') }}"
                    class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150 {{ request()->routeIs('admin.services.*') ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z"
                            clip-rule="evenodd" />
                        <path
                            d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z" />
                    </svg>
                    <span class="ml-3">Services</span>
                </a>

                <a href="{{ route('admin.settings') }}"
                    class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150 {{ request()->routeIs('admin.settings') ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="ml-3">Settings</span>
                </a>

                {{-- Future modules will add links here --}}
            </nav>
        </aside>

        <!-- Main content area -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top navbar (very light blue) -->
            <header
                class="bg-gray-50 border-b border-blue-200 h-16 flex items-center justify-between px-4 lg:px-6 shadow-lg shadow-blue-100/50">
                <div class="flex items-center gap-3">
                    <button class="lg:hidden text-blue-600 hover:text-blue-800 transition-colors"
                        onclick="toggleSidebar()">
                        <x-icons.menu />
                    </button>
                    @hasSection('breadcrumbs')
                        <div class="hidden sm:flex items-center">
                            @yield('breadcrumbs')
                        </div>
                    @endif
                </div>

                <!-- User dropdown -->
                <div class="relative" id="user-dropdown-container">
                    <button onclick="toggleUserDropdown()"
                        class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-full border border-gray-200 bg-white hover:border-blue-300 hover:bg-blue-50/50 shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-blue-200">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 text-white flex items-center justify-center font-semibold text-sm shadow-sm">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span class="hidden sm:inline font-medium text-sm text-gray-700">{{ auth()->user()->name }}</span>
                        <svg id="user-dropdown-chevron" class="h-4 w-4 text-gray-400 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown menu -->
                    <div id="user-dropdown-menu"
                        class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50 hidden">
                        <div class="flex items-center gap-3 px-4 py-2.5 border-b border-gray-100 mb-1">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 text-white flex items-center justify-center font-semibold text-sm shrink-0">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.profile') }}"
                            class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Profile
                        </a>
                        <a href="{{ route('admin.change-password') }}"
                            class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Change Password
                        </a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-2.5 text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main id="admin-main" class="flex-1 overflow-y-auto p-4 lg:p-6 bg-white">
                @if (session('success'))
                    <x-admin.alert type="success" :message="session('success')" />
                @endif
                @if (session('error'))
                    <x-admin.alert type="error" :message="session('error')" />
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- GLOBAL CONFIRMATION MODAL -->
    <div id="confirm-modal-overlay"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 p-6">
            <h3 id="confirm-modal-title" class="text-lg font-semibold text-gray-800 mb-2">Confirm Action</h3>
            <p id="confirm-modal-message" class="text-sm text-gray-600 mb-6">Are you sure you want to proceed?</p>
            <div class="flex justify-end gap-3">
                <button id="confirm-modal-cancel"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Cancel</button>
                <button id="confirm-modal-confirm"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition">Confirm</button>
            </div>
        </div>
    </div>

    <!-- GLOBAL IMAGE PREVIEW MODAL (dark blurred backdrop) -->
    <div id="image-preview-modal"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm hidden">
        <div class="relative max-w-4xl" onclick="event.stopPropagation();">
            <div class="flex justify-between items-center mb-2">
                <a id="image-preview-open" href="#" target="_blank"
                    class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    Open in New Tab
                </a>
                <button onclick="closeImagePreview()"
                    class="inline-flex items-center justify-center w-8 h-8 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <img id="image-preview-img" src=""
                class="max-w-full max-h-[80vh] object-contain mx-auto bg-white shadow-lg rounded-lg">
        </div>
    </div>

    <script>
        // Sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        // User dropdown toggle
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

        // Confirmation modal functions
        (function() {
            const overlay = document.getElementById('confirm-modal-overlay');
            const titleEl = document.getElementById('confirm-modal-title');
            const messageEl = document.getElementById('confirm-modal-message');
            const confirmBtn = document.getElementById('confirm-modal-confirm');
            const cancelBtn = document.getElementById('confirm-modal-cancel');
            let callback = null;

            window.confirmAction = function(title, message, onConfirm) {
                titleEl.textContent = title || 'Confirm Action';
                messageEl.textContent = message || 'Are you sure?';
                callback = onConfirm;
                overlay.classList.remove('hidden');
            };

            function hideModal() {
                overlay.classList.add('hidden');
                callback = null;
            }

            confirmBtn.addEventListener('click', function() {
                if (callback) callback();
                hideModal();
            });
            cancelBtn.addEventListener('click', hideModal);
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) hideModal();
            });
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !overlay.classList.contains('hidden')) hideModal();
            });
        })();

        // Image preview functions
        function openImagePreview(src) {
            document.getElementById('image-preview-img').src = src;
            document.getElementById('image-preview-open').href = src;
            document.getElementById('image-preview-modal').classList.remove('hidden');
        }

        function closeImagePreview() {
            document.getElementById('image-preview-modal').classList.add('hidden');
        }

        // Attach click handler to all images inside main content
        document.addEventListener('click', function(e) {
            if (e.target.closest('#admin-main img')) {
                e.preventDefault();
                openImagePreview(e.target.src);
            }
        });

        // Close image preview when clicking outside the image container
        document.getElementById('image-preview-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImagePreview();
            }
        });

        // Close image preview on Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('image-preview-modal').classList.contains(
                    'hidden')) {
                closeImagePreview();
            }
        });
    </script>
    @stack('scripts')
</body>

</html>

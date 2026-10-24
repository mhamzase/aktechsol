<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | {{ config('app.name') }} Admin</title>

    {{-- Apply saved admin theme before first paint (prevents light flash in dark mode) --}}
    <script>
    (function () { try { if (localStorage.getItem('aktechsol-admin-theme') === 'dark') document.documentElement.classList.add('dark'); } catch (e) {} })();
    </script>

    @if (!empty($faviconUrl))
        <link rel="icon" href="{{ $faviconUrl }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @endif

    {{-- Local assets only — no CDNs --}}
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}?v={{ config('app.version') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/notyf/notyf.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/quill/quill.snow.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}?v={{ config('app.version') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/ui.css') }}?v={{ config('app.version') }}">
    @stack('styles')
</head>
<body class="admin-body bg-[#f4f7fe] text-slate-800 antialiased">
    @include('partials.page-loader')

    {{-- Flash messages -> Notyf toasts --}}
    <div id="flash-messages"
         data-success="{{ session('success') }}"
         data-error="{{ session('error') }}"></div>

    <div class="flex h-screen overflow-hidden">
        {{-- Mobile sidebar overlay --}}
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-30 hidden lg:hidden" onclick="toggleSidebar()"></div>

        @include('admin.partials.sidebar')

        <div class="flex-1 flex flex-col min-w-0">
            @include('admin.partials.topbar')

            <main id="admin-main" class="flex-1 overflow-y-auto p-4 lg:p-6">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Slide-in drawer for AJAX forms --}}
    <div id="drawer-overlay" class="drawer-overlay" onclick="closeDrawer()"></div>
    <aside id="drawer" class="drawer" aria-label="Form panel">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <h3 data-drawer-title class="font-bold text-slate-800"></h3>
            <button onclick="closeDrawer()" class="btn-ghost btn !px-2" aria-label="Close">✕</button>
        </div>
        <div data-drawer-body class="drawer-body p-5"></div>
    </aside>

    {{-- Delete confirmation modal --}}
    <div id="confirm-delete-modal" class="hidden fixed inset-0 z-[70] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/60" onclick="closeConfirmDelete()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6">
            <h3 class="font-bold text-slate-800 mb-2">Confirm delete</h3>
            <p id="confirm-delete-message" class="text-sm text-slate-500 mb-5"></p>
            <div class="flex justify-end gap-2">
                <button class="btn btn-ghost" onclick="closeConfirmDelete()">Cancel</button>
                <button class="btn btn-danger" onclick="doDelete()">Delete</button>
            </div>
        </div>
    </div>

    {{-- Generic confirm-action modal (resend, duplicate, mark-as-sent, …) --}}
    <div id="confirm-action-modal" class="hidden fixed inset-0 z-[70] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/60" onclick="closeConfirmAction()"></div>
        <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-sm w-full p-6">
            <h3 id="confirm-action-title" class="font-bold text-slate-800 dark:text-slate-100 mb-2">Are you sure?</h3>
            <p id="confirm-action-message" class="text-sm text-slate-500 dark:text-slate-400 mb-5"></p>
            <div class="flex justify-end gap-2">
                <button class="btn btn-ghost" onclick="closeConfirmAction()">Cancel</button>
                <button id="confirm-action-btn" class="btn btn-primary" onclick="doConfirmAction()">Confirm</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/vendor/notyf/notyf.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/js/admin.js') }}?v={{ config('app.version') }}"></script>
    <script src="{{ asset('assets/js/ui-components.js') }}?v={{ config('app.version') }}"></script>
    @stack('scripts')
</body>
</html>

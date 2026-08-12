<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') | AK Tech SOL Admin</title>

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
        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden lg:hidden"
            onclick="toggleSidebar()"></div>

        @include('admin.partials.sidebar')

        <div class="flex-1 flex flex-col min-w-0">
            @include('admin.partials.navbar')

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

    @include('admin.partials.confirmation-modal')
    @include('admin.partials.image-preview-modal')

    <script>
        function toggleSidebar() { ... }
        function toggleUserDropdown() { ... }
        // ... all existing JS
    </script>
    @stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name')) - AK Tech SOL Admin</title>

    {{-- Favicon --}}
    @if($faviconUrl)
        <link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
    @else
        {{-- Default favicon fallback (optional) --}}
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @endif

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="font-sans antialiased min-h-screen flex flex-col justify-center items-center bg-blue-800">

    <div class="w-full sm:max-w-md px-6 py-8 mx-4 bg-white rounded-xl shadow-2xl">
        <div class="mb-6 text-center">
            {{-- Logo --}}
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="AK Tech SOL Logo" class="h-12 mx-auto mb-2 object-contain">
            @else
                <h1 class="text-3xl font-extrabold text-gray-900">AK Tech SOL</h1>
            @endif
            <p class="text-sm text-gray-500 mt-1">Admin Panel</p>
        </div>

        @yield('content')
    </div>

    <p class="mt-6 text-blue-300/60 text-xs text-center">&copy; {{ date('Y') }} AK Tech SOL. All rights reserved.</p>
</body>
</html>

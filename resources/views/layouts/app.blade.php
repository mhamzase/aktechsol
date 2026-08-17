<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $siteSettings->seo_title ?? ($siteSettings->site_name ?? 'AK Tech SOL'))</title>
    <meta name="description" content="@yield('meta_description', $siteSettings->seo_description ?? 'AK Tech SOL - Professional Software & Freelancing Agency')">
    @if ($siteSettings->seo_keywords)
        <meta name="keywords" content="{{ $siteSettings->seo_keywords }}">
    @endif
    @if ($siteSettings->seo_robots)
        <meta name="robots" content="{{ $siteSettings->seo_robots }}">
    @endif
    @if ($siteSettings->seo_canonical_url)
        <link rel="canonical" href="{{ $siteSettings->seo_canonical_url }}">
    @else
        <link rel="canonical" href="{{ url()->current() }}">
    @endif
    @if ($siteSettings->seo_og_image)
        <meta property="og:image" content="{{ $siteSettings->seo_og_image }}">
    @endif
    @if ($faviconUrl)
        <link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
    @endif
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    @stack('styles')
</head>

<body class="font-sans antialiased text-gray-800 bg-white">

    @include('partials.header')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    @stack('scripts')
</body>

</html>

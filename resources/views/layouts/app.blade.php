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
    {{-- Open Graph --}}
    <meta property="og:title" content="@yield('title', $siteSettings->seo_title ?? ($siteSettings->site_name ?? 'AK Tech SOL'))">
    <meta property="og:description" content="@yield('meta_description', $siteSettings->seo_description ?? 'AK Tech SOL - Professional Software & Freelancing Agency')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ $siteSettings->site_name ?? 'AK Tech SOL' }}">
    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', $siteSettings->site_name ?? 'AK Tech SOL')">
    <meta name="twitter:description" content="@yield('meta_description', $siteSettings->seo_description ?? '')">
    @if ($siteSettings->seo_og_image)
        <meta name="twitter:image" content="{{ $siteSettings->seo_og_image }}">
    @endif
    @stack('seo')
    @if ($faviconUrl)
        <link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
    @endif
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/notyf/notyf.min.css') }">
    <style>
        @font-face { font-family: 'Sora'; src: url('{{ asset('assets/fonts/sora/Sora-Regular.woff2') }}') format('woff2'); font-weight: 400; font-display: swap; }
        @font-face { font-family: 'Sora'; src: url('{{ asset('assets/fonts/sora/Sora-SemiBold.woff2') }}') format('woff2'); font-weight: 600; font-display: swap; }
        @font-face { font-family: 'Sora'; src: url('{{ asset('assets/fonts/sora/Sora-Bold.woff2') }}') format('woff2'); font-weight: 700; font-display: swap; }
        @font-face { font-family: 'Sora'; src: url('{{ asset('assets/fonts/sora/Sora-ExtraBold.woff2') }}') format('woff2'); font-weight: 800; font-display: swap; }
        body { font-family: 'Sora', ui-sans-serif, system-ui, sans-serif; }
    </style>
    @stack('styles')
</head>

<body class="antialiased text-slate-800 bg-white">
    @include('partials.page-loader')

    @include('partials.header')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    <script src="{{ asset('assets/vendor/notyf/notyf.min.js') }}"></script>
    <script>
        // Frontend toast notifications (bottom-right, same as admin).
        window.notify = (() => {
            const notyf = new Notyf({ duration: 4000, position: { x: 'right', y: 'bottom' }, dismissible: true });
            const show = (type, msg) => {
                const text = (msg || '').trim();
                document.querySelectorAll('.notyf__toast').forEach((t) => { if (t.textContent.trim() === text) t.remove(); });
                if (type === 'success') notyf.success(msg);
                else if (type === 'error') notyf.error(msg);
                else notyf.open({ type: 'info', message: msg, background: '#1d4ed8', icon: false });
            };
            return { success: (m) => show('success', m), error: (m) => show('error', m), info: (m) => show('info', m) };
        })();
    </script>
    @stack('scripts')
</body>

</html>

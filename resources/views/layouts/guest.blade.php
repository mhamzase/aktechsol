<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name')) - {{ $siteSettings->site_name ?: 'AK Tech SOL' }} Admin</title>

    {{-- Favicon --}}
    @if($faviconUrl)
        <link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
    @else
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @endif

    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <style>
        .login-branding {
            background: linear-gradient(135deg, #1669d9 0%, #1d4ed8 40%, #12307e 100%);
            position: relative;
            overflow: hidden;
        }
        .login-branding::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle at 20% 30%, rgba(255,255,255,0.12) 0, transparent 40%),
                              radial-gradient(circle at 80% 70%, rgba(255,255,255,0.08) 0, transparent 40%);
        }
        .login-branding::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255,255,255,0.15) 1px, transparent 1px);
            background-size: 24px 24px;
            opacity: 0.4;
        }
        .login-input {
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .login-input:focus {
            border-color: #1d4ed8;
            box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.12);
            outline: none;
        }
    </style>
</head>
<body class="font-sans antialiased min-h-screen bg-slate-100">
    @include('partials.page-loader')

    <div class="min-h-screen flex">
        {{-- Left: branding (hidden on mobile) --}}
        <div class="hidden lg:flex lg:w-[45%] login-branding items-center justify-center p-12">
            <div class="relative z-10 text-center max-w-md">
                @if($logoUrl)
                    <div class="inline-block bg-white rounded-2xl p-3 mb-6 shadow-lg">
                        <img src="{{ $logoUrl }}" alt="{{ $siteSettings->site_name ?: 'AK Tech SOL' }}" class="h-16 object-contain">
                    </div>
                @else
                    <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center">
                        <span class="text-3xl font-extrabold text-white">A</span>
                    </div>
                @endif
                <h1 class="text-3xl font-extrabold text-white mb-3">{{ $siteSettings->site_name ?: 'AK Tech SOL' }}</h1>
                <p class="text-blue-100/90 text-lg leading-relaxed">Your complete digital solution partner.<br>Manage everything from one place.</p>
                <div class="flex justify-center gap-6 mt-8 text-blue-100/70 text-sm">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Secure
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Fast
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Reliable
                    </span>
                </div>
            </div>
        </div>

        {{-- Right: form --}}
        <div class="flex-1 flex items-center justify-center p-6 sm:p-12">
            <div class="w-full max-w-md">
                <div class="lg:hidden text-center mb-8">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $siteSettings->site_name ?: 'AK Tech SOL' }}" class="h-12 mx-auto mb-2 object-contain">
                    @else
                        <h1 class="text-2xl font-extrabold text-slate-900">{{ $siteSettings->site_name ?: 'AK Tech SOL' }}</h1>
                    @endif
                </div>

                <h2 class="text-2xl font-bold text-slate-900 mb-1">Welcome back</h2>
                <p class="text-sm text-slate-500 mb-8">Sign in to your admin account</p>

                @yield('content')

                <p class="mt-8 text-center text-xs text-slate-400">&copy; {{ date('Y') }} {{ $siteSettings->site_name ?: 'AK Tech SOL' }}. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>

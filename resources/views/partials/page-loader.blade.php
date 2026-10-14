{{--
    ============================================================================
    AK TECH SOL — Global page loader
    ----------------------------------------------------------------------------
    Shows a branded "A" logo spinner on every full page load (admin + frontend).
    Included right after <body> in:
        - resources/views/admin/layouts/app.blade.php
        - resources/views/layouts/app.blade.php
        - resources/views/layouts/guest.blade.php

    *** FUTURE UPDATES: edit THIS FILE ONLY. ***
    Markup, styles and animation all live here — every layout picks up
    changes automatically. Quick tweaks:
        --pl-size : loader size (default 76px)
        --pl-blue : brand color (default #1669d9)
    Theme-aware: dark overlay when <html> has .dark or the saved admin
    theme is dark.
    ============================================================================
--}}
<div id="page-loader" aria-hidden="true">
    <div class="pl-box">
        <div class="pl-ring"></div>
        <div class="pl-logo">A</div>
    </div>
    <p class="pl-brand">AK Tech Sol</p>
</div>
<style>
    #page-loader {
        --pl-size: 76px;
        --pl-blue: #1669d9;
        --pl-bg: #f4f7fe;
        --pl-text: #0f2a5c;
        position: fixed;
        inset: 0;
        z-index: 1000000;
        display: flex;
        flex-direction: column;
        gap: 1.1rem;
        align-items: center;
        justify-content: center;
        background: var(--pl-bg);
        transition: opacity 0.45s ease, visibility 0.45s ease;
    }
    #page-loader.pl-dark {
        --pl-bg: #1F2937;
        --pl-text: #e2e8f0;
    }
    #page-loader.pl-hide {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
    }
    #page-loader .pl-box {
        position: relative;
        width: var(--pl-size);
        height: var(--pl-size);
    }
    /* Spinning ring around the logo */
    #page-loader .pl-ring {
        position: absolute;
        inset: -9px;
        border-radius: 50%;
        border: 4px solid rgba(22, 105, 217, 0.15);
        border-top-color: var(--pl-blue);
        animation: pl-spin 0.9s linear infinite;
    }
    /* Brand mark — matches the sidebar logo */
    #page-loader .pl-logo {
        position: absolute;
        inset: 0;
        border-radius: 1.1rem;
        background: var(--pl-blue);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Sora', 'Nunito', ui-sans-serif, system-ui, sans-serif;
        font-weight: 800;
        font-size: calc(var(--pl-size) * 0.44);
        box-shadow: 0 10px 26px rgba(22, 105, 217, 0.35);
        animation: pl-pulse 1.6s ease-in-out infinite;
    }
    #page-loader .pl-brand {
        margin: 0;
        font-family: 'Sora', 'Nunito', ui-sans-serif, system-ui, sans-serif;
        font-size: 0.85rem;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--pl-text);
    }
    @keyframes pl-spin {
        to { transform: rotate(360deg); }
    }
    @keyframes pl-pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(0.93); }
    }
</style>
<script>
    (function () {
        var loader = document.getElementById('page-loader');
        if (!loader) return;
        // Dark overlay when dark mode is active (admin toggle or OS preference).
        try {
            if (document.documentElement.classList.contains('dark') ||
                localStorage.getItem('aktechsol-admin-theme') === 'dark') {
                loader.classList.add('pl-dark');
            }
        } catch (e) { /* ignore */ }
        function hide() {
            if (!loader || loader.classList.contains('pl-hide')) return;
            loader.classList.add('pl-hide');
            setTimeout(function () { loader.remove(); }, 600);
        }
        if (document.readyState === 'complete') {
            // Page already loaded (e.g. cached) — hide quickly.
            setTimeout(hide, 350);
        } else {
            window.addEventListener('load', function () { setTimeout(hide, 350); });
        }
        // Safety: never trap the user behind the loader.
        setTimeout(hide, 5000);
    })();
</script>

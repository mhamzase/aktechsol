{{--
    Shared admin page header — tailor-app style hero.
    Gradient slate-900 → slate-800, gold icon tile, white title.
    Props: $title (string), $subtitle (string, optional), $icon (svg path, optional).
    Slots:  "meta"    — small stat chips on the right (translucent).
            "actions" — buttons on the right. .btn-primary automatically
                        renders as the gold CTA on the navy hero (tailor-app
                        style); .btn-dark for the translucent Export-style button.
--}}
@props(['title' => '', 'subtitle' => null, 'icon' => null])

@section('breadcrumbs')
    <span class="crumb-current truncate">{{ $title }}</span>
@endsection

<div class="page-hero mb-6">
    <div class="flex flex-wrap items-center justify-between gap-x-6 gap-y-4">
        <div class="flex items-start gap-4 min-w-0">
            @if ($icon)
                <div class="hero-icon shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}" />
                    </svg>
                </div>
            @endif
            <div class="min-w-0">
                <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-white truncate">{{ $title }}</h1>
                @if ($subtitle)
                    <p class="mt-1 text-sm sm:text-base text-blue-100/90">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            @isset($meta)
                {{ $meta }}
            @endisset
            @isset($actions)
                {{ $actions }}
            @endisset
        </div>
    </div>
</div>

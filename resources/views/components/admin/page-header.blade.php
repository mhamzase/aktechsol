{{--
    Shared admin page header — tailor-app style dark navy hero card.
    Props: $title (string), $subtitle (string, optional), $icon (svg path, optional).
    Slots:  "meta"    — small stat chips on the right (dark translucent).
            "actions" — buttons on the right (use .btn-light on dark bg).
--}}
@props(['title' => '', 'subtitle' => null, 'icon' => null])

@section('breadcrumbs')
    <span class="crumb-current truncate">{{ $title }}</span>
@endsection

<div class="page-hero mb-5">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3.5 min-w-0">
            @if ($icon)
                <div class="hero-icon shrink-0">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}" />
                    </svg>
                </div>
            @endif
            <div class="min-w-0">
                <h1 class="text-xl md:text-2xl font-extrabold text-white tracking-tight truncate">{{ $title }}</h1>
                @if ($subtitle)
                    <p class="text-sm text-blue-100/80 mt-0.5 truncate">{{ $subtitle }}</p>
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

{{--
    Page heading bar — used on EVERY admin page (tailor / akdesignerart pattern).
    Props: title, subtitle. Slot: actions (buttons on the right).
    Also fills the topbar breadcrumb section.
--}}
@props(['title', 'subtitle' => null])

@section('breadcrumbs')
    <span class="crumb-current truncate">{{ $title }}</span>
@endsection

<div class="flex flex-wrap items-start justify-between gap-3 mb-5">
    <div class="min-w-0">
        <h1 class="text-xl lg:text-2xl font-extrabold text-[#0f2a5c] truncate">{{ $title }}</h1>
        @if ($subtitle)
            <p class="text-sm text-slate-500 mt-0.5">{{ $subtitle }}</p>
        @endif
    </div>
    @if (trim($actions ?? '') !== '')
        <div class="flex items-center gap-2 shrink-0">
            {{ $actions }}
        </div>
    @endif
</div>

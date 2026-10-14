{{-- Service details drawer — modern professional --}}
<div class="drawer-content">
    {{-- Header with image --}}
    @if ($service->getThumbnailUrl())
        <div class="h-44 -mx-6 -mt-2 mb-5 overflow-hidden">
            <img src="{{ $service->getThumbnailUrl() }}" alt="" class="w-full h-full object-cover">
        </div>
    @endif

    <div class="px-6 pb-6">
        <div class="flex items-start gap-3 mb-4">
            <div class="min-w-0 flex-1">
                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">{{ $service->title }}</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400">/{{ $service->slug }}</p>
            </div>
            <span class="shrink-0 text-xs font-bold px-3 py-1 rounded-full {{ $service->status ? 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400' }}">
                {{ $service->status ? 'Active' : 'Inactive' }}
            </span>
        </div>

        {{-- Meta --}}
        <div class="grid grid-cols-2 gap-3 mb-5">
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Category</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $service->category?->name ?? '—' }}</p>
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Sort Order</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $service->sort_order }}</p>
            </div>
            @if ($service->icon)
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5 col-span-2">
                <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Icon</p>
                <p class="text-sm font-mono text-slate-700 dark:text-slate-300 mt-1">{{ $service->icon }}</p>
            </div>
            @endif
        </div>

        @if ($service->short_description)
        <div class="mb-4">
            <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1.5">Short Description</p>
            <p class="text-slate-700 dark:text-slate-300 text-[0.95rem]">{{ $service->short_description }}</p>
        </div>
        @endif

        @if ($service->full_description)
        <div class="mb-4">
            <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1.5">Full Description</p>
            <div class="text-slate-700 dark:text-slate-300 leading-relaxed text-[0.95rem] whitespace-pre-wrap">{{ $service->full_description }}</div>
        </div>
        @endif

        @if ($service->seo_title || $service->meta_description || $service->canonical_url)
        <div class="border-t border-slate-200 dark:border-slate-700 pt-4 mt-5">
            <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-3">SEO</p>
            <div class="space-y-2 text-sm">
                @if ($service->seo_title)
                    <p><span class="text-slate-500 dark:text-slate-400">Title:</span> <span class="text-slate-800 dark:text-slate-100 font-medium">{{ $service->seo_title }}</span></p>
                @endif
                @if ($service->meta_description)
                    <p><span class="text-slate-500 dark:text-slate-400">Description:</span> <span class="text-slate-700 dark:text-slate-300">{{ $service->meta_description }}</span></p>
                @endif
                @if ($service->canonical_url)
                    <p><span class="text-slate-500 dark:text-slate-400">Canonical:</span> <a href="{{ $service->canonical_url }}" target="_blank" class="text-[#1d4ed8] dark:text-blue-400 hover:underline break-all">{{ $service->canonical_url }}</a></p>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

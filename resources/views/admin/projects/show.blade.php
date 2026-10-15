{{-- Project details drawer — modern professional --}}
<div class="drawer-content">
    @if ($project->getFeaturedImageUrl())
        <div class="px-6 pt-4 mb-5">
            <img src="{{ $project->getFeaturedImageUrl() }}?v={{ $project->updated_at->timestamp }}" alt="" class="w-full h-48 object-cover rounded-xl">
        </div>
    @endif

    <div class="px-6 pb-6">
        <div class="flex items-start gap-3 mb-4">
            <div class="min-w-0 flex-1">
                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">{{ $project->title }}</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400">/{{ $project->slug }}</p>
            </div>
            <span class="shrink-0 text-xs font-bold px-3 py-1 rounded-full {{ $project->status ? 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400' }}">
                {{ $project->status ? 'Active' : 'Inactive' }}
            </span>
        </div>

        <div class="grid grid-cols-2 gap-3 mb-5">
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Client</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $project->client_name ?? '—' }}</p>
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Completed</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $project->completion_date?->format('M d, Y') ?? '—' }}</p>
            </div>
            @if ($project->project_url)
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5 col-span-2">
                <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Project URL</p>
                <a href="{{ $project->project_url }}" target="_blank" class="text-sm font-medium text-[#1d4ed8] dark:text-blue-400 hover:underline break-all">{{ $project->project_url }}</a>
            </div>
            @endif
        </div>

        @if ($project->short_description)
        <div class="mb-4">
            <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1.5">Short Description</p>
            <p class="text-slate-700 dark:text-slate-300 text-[0.95rem]">{{ $project->short_description }}</p>
        </div>
        @endif

        @if ($project->full_description)
        <div class="mb-4">
            <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1.5">Full Description</p>
            <div class="prose prose-sm dark:prose-invert max-w-none text-slate-700 dark:text-slate-300">{!! $project->full_description !!}</div>
        </div>
        @endif

        @php $gallery = $project->getGalleryImages(); @endphp
        @if ($gallery && count($gallery))
        <div class="mb-4">
            <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2">Gallery</p>
            <div class="grid grid-cols-3 gap-2">
                @foreach ($gallery as $media)
                    <img src="{{ $media->getUrl() }}" alt="" class="h-20 w-full object-cover rounded-lg">
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

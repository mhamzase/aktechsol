{{-- Blog post details drawer — modern professional --}}
<div class="drawer-content">
    {{-- Featured image --}}
    @if ($post->getFeaturedImageUrl())
        <div class="px-6 pt-4 mb-5">
            <img src="{{ $post->getFeaturedImageUrl() }}?v={{ $post->updated_at->timestamp }}" alt="" class="w-full h-48 object-cover rounded-xl">
        </div>
    @endif

    <div class="px-6 pb-6">
        <div class="flex items-start gap-3 mb-4">
            <div class="min-w-0 flex-1">
                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">{{ $post->title }}</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400">/{{ $post->slug }}</p>
            </div>
            <span class="shrink-0 text-xs font-bold px-3 py-1 rounded-full {{ $post->status ? 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400' }}">
                {{ $post->status ? 'Active' : 'Inactive' }}
            </span>
        </div>

        {{-- Meta --}}
        <div class="grid grid-cols-2 gap-3 mb-5">
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Category</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $post->category?->name ?? '—' }}</p>
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Published</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $post->published_at?->format('M d, Y') ?? '—' }}</p>
            </div>
        </div>

        @if ($post->excerpt)
        <div class="mb-4">
            <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1.5">Excerpt</p>
            <p class="text-slate-700 dark:text-slate-300 text-[0.95rem] italic">{{ $post->excerpt }}</p>
        </div>
        @endif

        @if ($post->content)
        <div class="mb-4">
            <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1.5">Content</p>
            <div class="prose prose-sm dark:prose-invert max-w-none text-slate-700 dark:text-slate-300">{!! $post->content !!}</div>
        </div>
        @endif

        @if ($post->seo_title || $post->meta_description || $post->canonical_url)
        <div class="border-t border-slate-200 dark:border-slate-700 pt-4 mt-5">
            <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-3">SEO</p>
            <div class="space-y-2 text-sm">
                @if ($post->seo_title)
                    <p><span class="text-slate-500 dark:text-slate-400">Title:</span> <span class="text-slate-800 dark:text-slate-100 font-medium">{{ $post->seo_title }}</span></p>
                @endif
                @if ($post->meta_description)
                    <p><span class="text-slate-500 dark:text-slate-400">Description:</span> <span class="text-slate-700 dark:text-slate-300">{{ $post->meta_description }}</span></p>
                @endif
                @if ($post->canonical_url)
                    <p><span class="text-slate-500 dark:text-slate-400">Canonical:</span> <a href="{{ $post->canonical_url }}" target="_blank" class="text-[#1d4ed8] dark:text-blue-400 hover:underline break-all">{{ $post->canonical_url }}</a></p>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

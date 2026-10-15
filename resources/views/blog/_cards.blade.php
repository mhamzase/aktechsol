@forelse($posts as $post)
    <a href="{{ route('blog.show', $post->slug) }}"
       class="group bg-white rounded-2xl overflow-hidden border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
        <div class="relative overflow-hidden">
            @if($post->getFeaturedImageUrl())
                <img src="{{ $post->getFeaturedImageUrl() }}" alt="{{ $post->title }}" loading="lazy"
                     class="h-52 w-full object-cover group-hover:scale-105 transition-transform duration-500">
            @else
                <div class="h-52 w-full flex items-center justify-center text-slate-300 bg-gradient-to-br from-slate-50 to-slate-100">
                    <svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2 2H9a2 2 0 01-2-2V9a2 2 0 012-2h10a2 2 0 012 2v11z"/></svg>
                </div>
            @endif
            <span class="absolute top-3 left-3 px-3 py-1 bg-[#1d4ed8] text-white text-xs font-bold rounded-full shadow">{{ $post->category?->name ?? 'General' }}</span>
        </div>
        <div class="p-5 sm:p-6 flex flex-col flex-1">
            <h3 class="text-lg font-bold text-slate-900 group-hover:text-[#1d4ed8] transition line-clamp-2">{{ $post->title }}</h3>
            <p class="text-sm text-slate-500 mt-2 line-clamp-3 flex-1">{{ $post->excerpt }}</p>
            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs text-slate-400 font-medium">{{ $post->published_at?->format('M d, Y') }}</span>
                <span class="text-sm font-bold text-[#1d4ed8] inline-flex items-center gap-1">
                    Read More
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </span>
            </div>
        </div>
    </a>
@empty
    <div class="col-span-full text-center py-16">
        <div class="w-16 h-16 mx-auto bg-slate-100 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        <h3 class="text-lg font-bold text-slate-900">No posts found</h3>
        <p class="text-sm text-slate-500 mt-1">Try a different search or category.</p>
    </div>
@endforelse

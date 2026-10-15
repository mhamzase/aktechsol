@forelse($projects as $project)
    <a href="{{ route('projects.show', $project->slug) }}"
       class="group bg-white rounded-2xl overflow-hidden border border-slate-100 hover:shadow-xl hover:shadow-blue-100/60 hover:-translate-y-1 transition-all duration-300 flex flex-col">
        <div class="h-52 overflow-hidden bg-slate-100 relative">
            @if($project->getFeaturedImageUrl())
                <img src="{{ $project->getFeaturedImageUrl() }}" alt="{{ $project->title }}" loading="lazy"
                     class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500">
            @else
                <div class="h-52 w-full flex items-center justify-center text-slate-300 bg-gradient-to-br from-slate-50 to-slate-100">
                    <svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-[#0a1f44]/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        </div>
        <div class="p-5 flex-1 flex flex-col">
            <h3 class="font-bold text-slate-900 group-hover:text-[#1d4ed8] transition-colors">{{ $project->title }}</h3>
            @if($project->client_name)
                <p class="text-xs text-slate-400 mt-1 font-medium">{{ $project->client_name }}</p>
            @endif
            <p class="text-sm text-slate-500 mt-2 line-clamp-2 flex-1">{{ $project->short_description }}</p>
            <span class="inline-flex items-center gap-1.5 text-sm font-bold text-[#1d4ed8] mt-4">
                View Case Study
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </span>
        </div>
    </a>
@empty
    <div class="col-span-full text-center py-16">
        <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        <p class="text-slate-500 font-medium">No projects found.</p>
        <p class="text-sm text-slate-400 mt-1">Try a different search.</p>
    </div>
@endforelse

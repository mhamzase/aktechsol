@forelse($services as $service)
    <a href="{{ route('services.show', $service->slug) }}"
       class="group bg-white rounded-2xl overflow-hidden border border-slate-100 hover:shadow-xl hover:shadow-blue-100/60 hover:-translate-y-1 transition-all duration-300 flex flex-col">
        @if($service->getThumbnailUrl())
            <div class="h-44 overflow-hidden bg-slate-100">
                <img src="{{ $service->getThumbnailUrl() }}" alt="{{ $service->title }}" loading="lazy"
                     class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500">
            </div>
        @else
            <div class="h-44 bg-gradient-to-br from-[#1d4ed8]/10 to-[#1d4ed8]/5 flex items-center justify-center">
                <span class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#1d4ed8] to-[#12307e] text-white flex items-center justify-center group-hover:scale-110 transition-transform">
                    @if($service->icon)
                        <i class="{{ $service->icon }} text-2xl"></i>
                    @else
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    @endif
                </span>
            </div>
        @endif
        <div class="p-6 flex-1 flex flex-col">
            @if($service->category)
                <span class="text-[0.7rem] font-bold text-[#1d4ed8] uppercase tracking-widest">{{ $service->category->name }}</span>
            @endif
            <h3 class="text-lg font-bold text-slate-900 mt-1.5 group-hover:text-[#1d4ed8] transition-colors">{{ $service->title }}</h3>
            <p class="text-sm text-slate-500 mt-2 line-clamp-2 flex-1">{{ $service->short_description }}</p>
            <span class="inline-flex items-center gap-1.5 text-sm font-bold text-[#1d4ed8] mt-4">
                Learn more
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </span>
        </div>
    </a>
@empty
    <div class="col-span-full text-center py-16">
        <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        <p class="text-slate-500 font-medium">No services found.</p>
        <p class="text-sm text-slate-400 mt-1">Try a different search or category.</p>
    </div>
@endforelse

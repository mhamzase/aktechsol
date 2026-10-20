@extends('layouts.app')

@section('title', 'Our Portfolio — Recent Projects & Case Studies | ' . ($siteSettings->site_name ?? 'AK Tech SOL'))
@section('meta_description', 'Browse ' . ($siteSettings->site_name ?? 'AK Tech SOL') . ' portfolio: real projects, case studies and client work across web development, mobile apps, UI/UX design and more.')

@section('content')
    <x-frontend.page-banner
        title="Our Portfolio"
        subtitle="Real projects. Real results. Take a look at our recent work."
    />

    <section class="py-16 bg-slate-50/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Search --}}
            <form id="projects-filter" class="mb-10">
                <div class="max-w-md">
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" name="search" id="projects-search" value="{{ request('search') }}" placeholder="Search projects..."
                               class="w-full rounded-2xl border border-slate-200 bg-white pl-11 pr-10 py-3 text-sm shadow-sm focus:border-[#1d4ed8] focus:ring-2 focus:ring-[#1d4ed8]/20 focus:outline-none transition">
                        <button type="button" id="projects-search-clear" class="absolute inset-y-0 right-0 px-3.5 text-slate-300 hover:text-slate-500 transition hidden" aria-label="Clear search">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
            </form>

            <div class="relative">
                <div id="projects-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @include('projects._cards', ['projects' => $projects])
                </div>
                <div id="projects-loading" class="absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center hidden rounded-2xl z-10">
                    <div class="flex flex-col items-center gap-3">
                        <svg class="animate-spin h-10 w-10 text-[#1d4ed8]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <p class="text-sm text-slate-500 font-medium">Loading...</p>
                    </div>
                </div>
            </div>

            <div id="load-more-wrapper" class="text-center mt-12 {{ $projects->nextPageUrl() ? '' : 'hidden' }}">
                <button id="load-more-projects" class="px-8 py-3.5 bg-white border-2 border-[#1d4ed8] text-[#1d4ed8] font-bold rounded-2xl hover:bg-[#1d4ed8] hover:text-white transition disabled:opacity-50 disabled:cursor-not-allowed shadow-sm" data-next-url="{{ $projects->nextPageUrl() }}">Load More</button>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    (function() {
        const grid = document.getElementById('projects-grid');
        const filterForm = document.getElementById('projects-filter');
        const loadMoreBtn = document.getElementById('load-more-projects');
        const loadMoreWrapper = document.getElementById('load-more-wrapper');
        const loadingOverlay = document.getElementById('projects-loading');
        const searchInput = document.getElementById('projects-search');
        const clearBtn = document.getElementById('projects-search-clear');

        function showLoading() { loadingOverlay.classList.remove('hidden'); }
        function hideLoading() { loadingOverlay.classList.add('hidden'); }

        function updateLoadMoreButton(nextUrl) {
            if (nextUrl) {
                loadMoreBtn.setAttribute('data-next-url', nextUrl);
                loadMoreBtn.textContent = 'Load More';
                loadMoreBtn.disabled = false;
                loadMoreWrapper.classList.remove('hidden');
            } else {
                loadMoreWrapper.classList.add('hidden');
            }
        }

        async function fetchProjects(url) {
            const params = new URLSearchParams(new FormData(filterForm)).toString();
            const separator = url.includes('?') ? '&' : '?';
            const response = await fetch(`${url}${separator}${params}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            return await response.json();
        }

        async function applyFilter() {
            showLoading();
            try {
                const data = await fetchProjects("{{ route('projects.index') }}");
                grid.innerHTML = data.items;
                updateLoadMoreButton(data.next_page_url);
            } catch (e) { console.error(e); }
            finally { hideLoading(); }
        }

        let t = null;
        const toggleClear = () => clearBtn.classList.toggle('hidden', !searchInput.value.trim());
        toggleClear();
        searchInput.addEventListener('input', () => {
            toggleClear();
            clearTimeout(t);
            t = setTimeout(applyFilter, 400);
        });
        clearBtn.addEventListener('click', () => { searchInput.value = ''; toggleClear(); applyFilter(); });
        filterForm.addEventListener('submit', e => { e.preventDefault(); applyFilter(); });

        loadMoreBtn.addEventListener('click', async function() {
            const nextUrl = loadMoreBtn.getAttribute('data-next-url');
            if (!nextUrl) return;
            loadMoreBtn.disabled = true;
            loadMoreBtn.textContent = 'Loading...';
            try {
                const data = await fetchProjects(nextUrl);
                grid.insertAdjacentHTML('beforeend', data.items);
                updateLoadMoreButton(data.next_page_url);
            } catch (e) { console.error(e); }
            finally { loadMoreBtn.textContent = 'Load More'; loadMoreBtn.disabled = false; }
        });
    })();
</script>
@endpush

@push('seo')
<meta property="og:title" content="Our Portfolio — Recent Projects & Case Studies | {{ $siteSettings->site_name ?? 'AK Tech SOL' }}">
<meta property="og:description" content="Browse {{ $siteSettings->site_name ?? 'AK Tech SOL' }} portfolio: real projects, case studies and client work across web development, mobile apps, UI/UX design and more.">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "ItemList",
    "name": "Our Portfolio",
    "url": "{{ url()->current() }}",
    "itemListElement": [
        @foreach($projects as $i => $p)
        {
            "@@type": "ListItem",
            "position": {{ $i + 1 }},
            "url": "{{ route('projects.show', $p->slug) }}",
            "name": "{{ $p->title }}"
        }{{ !$loop->last ? ',' : '' }}
        @endforeach
    ]
}
</script>
@endpush

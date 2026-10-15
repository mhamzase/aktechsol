@extends('layouts.app')

@section('title', 'Our Services — Web Development, Mobile Apps, UI/UX Design | ' . ($siteSettings->site_name ?? 'AK Tech SOL'))
@section('meta_description', 'Explore AK Tech SOL services: web development, mobile app development, UI/UX design, cloud solutions and more. Professional digital services tailored for your business growth.')

@push('seo')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ItemList",
    "name": "Our Services",
    "itemListElement": [
        @foreach($services as $i => $service)
        {
            "@type": "ListItem",
            "position": {{ $i + 1 }},
            "name": "{{ $service->title }}",
            "url": "{{ route('services.show', $service->slug) }}"
        }{{ !$loop->last ? ',' : '' }}
        @endforeach
    ]
}
</script>
@endpush

@section('content')
    <x-frontend.page-banner
        title="Our Services"
        subtitle="Explore what we can do for you."
    />

    <section class="py-16 bg-slate-50/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Modern filter bar --}}
            <form id="services-filter" class="mb-10">
                <div class="max-w-md mb-4">
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" name="search" id="services-search" value="{{ request('search') }}" placeholder="Search services..."
                               class="w-full rounded-2xl border border-slate-200 bg-white pl-11 pr-10 py-3 text-sm shadow-sm focus:border-[#1d4ed8] focus:ring-2 focus:ring-[#1d4ed8]/20 focus:outline-none transition">
                        <button type="button" id="services-search-clear" class="absolute inset-y-0 right-0 px-3.5 text-slate-300 hover:text-slate-500 transition hidden" aria-label="Clear search">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
                <input type="hidden" name="category" id="services-category" value="{{ request('category') }}">
                <div class="flex flex-wrap gap-2" id="category-pills">
                    <button type="button" data-cat="" class="cat-pill px-4 py-2.5 rounded-xl text-sm font-semibold transition {{ !request('category') ? 'bg-[#1d4ed8] text-white shadow-md shadow-blue-600/25' : 'bg-white text-slate-600 border border-slate-200 hover:border-[#1d4ed8] hover:text-[#1d4ed8]' }}">All</button>
                    @foreach($categories as $cat)
                        <button type="button" data-cat="{{ $cat->slug }}" class="cat-pill px-4 py-2.5 rounded-xl text-sm font-semibold transition {{ request('category') === $cat->slug ? 'bg-[#1d4ed8] text-white shadow-md shadow-blue-600/25' : 'bg-white text-slate-600 border border-slate-200 hover:border-[#1d4ed8] hover:text-[#1d4ed8]' }}">{{ $cat->name }}</button>
                    @endforeach
                </div>
            </form>

            <div class="relative">
                <div id="services-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @include('services._cards', ['services' => $services])
                </div>
                <div id="services-loading" class="absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center hidden rounded-2xl z-10">
                    <div class="flex flex-col items-center gap-3">
                        <svg class="animate-spin h-10 w-10 text-[#1d4ed8]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <p class="text-sm text-slate-500 font-medium">Loading...</p>
                    </div>
                </div>
            </div>

            <div id="load-more-wrapper" class="text-center mt-12 {{ $services->nextPageUrl() ? '' : 'hidden' }}">
                <button id="load-more-services" class="px-8 py-3.5 bg-white border-2 border-[#1d4ed8] text-[#1d4ed8] font-bold rounded-2xl hover:bg-[#1d4ed8] hover:text-white transition disabled:opacity-50 disabled:cursor-not-allowed shadow-sm" data-next-url="{{ $services->nextPageUrl() }}">Load More</button>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    function clearSearchInput(id) { const el = document.getElementById(id); if (el) el.value = ''; }

    (function() {
        const grid = document.getElementById('services-grid');
        const filterForm = document.getElementById('services-filter');
        const loadMoreBtn = document.getElementById('load-more-services');
        const loadMoreWrapper = document.getElementById('load-more-wrapper');
        const loadingOverlay = document.getElementById('services-loading');
        const searchInput = document.getElementById('services-search');
        const categoryInput = document.getElementById('services-category');
        const pills = document.querySelectorAll('.cat-pill');

        function showLoading() { loadingOverlay.classList.remove('hidden'); }
        function hideLoading() { loadingOverlay.classList.add('hidden'); }

        function updatePills(activeCat) {
            pills.forEach(p => {
                const isActive = p.dataset.cat === activeCat;
                p.className = 'cat-pill px-4 py-2.5 rounded-xl text-sm font-semibold transition ' + (isActive
                    ? 'bg-[#1d4ed8] text-white shadow-md shadow-blue-600/25'
                    : 'bg-white text-slate-600 border border-slate-200 hover:border-[#1d4ed8] hover:text-[#1d4ed8]');
            });
        }

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

        async function fetchServices(url) {
            const params = new URLSearchParams(new FormData(filterForm)).toString();
            const separator = url.includes('?') ? '&' : '?';
            const fetchUrl = `${url}${separator}${params}`;
            const response = await fetch(fetchUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            return await response.json();
        }

        async function applyFilter() {
            showLoading();
            try {
                const data = await fetchServices("{{ route('services.index') }}");
                grid.innerHTML = data.items;
                updateLoadMoreButton(data.next_page_url);
            } catch (error) {
                console.error('Filter error:', error);
            } finally {
                hideLoading();
            }
        }

        // Debounced live search
        let searchTimer = null;
        const clearBtn = document.getElementById('services-search-clear');
        const toggleClear = () => clearBtn.classList.toggle('hidden', !searchInput.value.trim());
        toggleClear();
        searchInput.addEventListener('input', () => {
            toggleClear();
            clearTimeout(searchTimer);
            searchTimer = setTimeout(applyFilter, 400);
        });
        clearBtn.addEventListener('click', () => {
            searchInput.value = '';
            toggleClear();
            applyFilter();
        });

        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            applyFilter();
        });

        // Category pills
        pills.forEach(p => {
            p.addEventListener('click', () => {
                categoryInput.value = p.dataset.cat;
                updatePills(p.dataset.cat);
                applyFilter();
            });
        });

        loadMoreBtn.addEventListener('click', async function() {
            const nextUrl = loadMoreBtn.getAttribute('data-next-url');
            if (!nextUrl) return;
            loadMoreBtn.disabled = true;
            loadMoreBtn.textContent = 'Loading...';
            showLoading();
            try {
                const data = await fetchServices(nextUrl);
                grid.insertAdjacentHTML('beforeend', data.items);
                updateLoadMoreButton(data.next_page_url);
            } catch (error) {
                console.error('Load more error:', error);
            } finally {
                loadMoreBtn.textContent = 'Load More';
                loadMoreBtn.disabled = false;
                hideLoading();
            }
        });
    })();
</script>
@endpush

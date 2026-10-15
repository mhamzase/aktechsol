@extends('layouts.app')

@section('title', 'Blog — Insights, Tutorials & Updates | ' . ($siteSettings->site_name ?? 'AK Tech SOL'))
@section('meta_description', 'Read the latest insights, tutorials and updates from ' . ($siteSettings->site_name ?? 'AK Tech SOL') . ' on web development, mobile apps, design and digital growth.')

@push('seo')
<meta property="og:title" content="Blog — Insights, Tutorials & Updates | {{ $siteSettings->site_name ?? 'AK Tech SOL' }}">
<meta property="og:description" content="Latest insights, tutorials and updates on web development, mobile apps, design and digital growth.">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "Blog",
    "name": "Blog | {{ $siteSettings->site_name ?? 'AK Tech SOL' }}",
    "url": "{{ url()->current() }}",
    "blogPost": [
        @foreach($posts as $i => $p)
        {
            "@@type": "BlogPosting",
            "headline": "{{ $p->title }}",
            "url": "{{ route('blog.show', $p->slug) }}",
            "datePublished": "{{ $p->published_at?->toIso8601String() }}"
        }{{ !$loop->last ? ',' : '' }}
        @endforeach
    ]
}
</script>
@endpush

@section('content')
    <x-frontend.page-banner
        title="Our Blog"
        subtitle="Insights, tutorials, and updates from our team."
    />

    <section class="py-12 sm:py-16 bg-slate-50/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Filters --}}
            <form id="blog-filter" class="mb-8 space-y-4">
                <div class="relative max-w-xl">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" id="blog-search" value="{{ request('search') }}" placeholder="Search articles..."
                           class="w-full rounded-2xl border border-slate-200 bg-white pl-11 pr-10 py-3 text-sm focus:border-[#1d4ed8] focus:ring-2 focus:ring-[#1d4ed8]/20 focus:outline-none transition shadow-sm">
                    <button type="button" id="blog-search-clear" class="absolute inset-y-0 right-0 px-3.5 text-slate-400 hover:text-slate-600 {{ request('search') ? '' : 'hidden' }}">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button type="button" data-cat="" class="cat-pill px-4 py-2 rounded-full text-sm font-semibold transition {{ !request('category') ? 'bg-[#1d4ed8] text-white shadow-lg shadow-blue-600/25' : 'bg-white text-slate-600 border border-slate-200 hover:border-[#1d4ed8] hover:text-[#1d4ed8]' }}">All</button>
                    @foreach($categories as $cat)
                        <button type="button" data-cat="{{ $cat->slug }}" class="cat-pill px-4 py-2 rounded-full text-sm font-semibold transition {{ request('category') === $cat->slug ? 'bg-[#1d4ed8] text-white shadow-lg shadow-blue-600/25' : 'bg-white text-slate-600 border border-slate-200 hover:border-[#1d4ed8] hover:text-[#1d4ed8]' }}">{{ $cat->name }}</button>
                    @endforeach
                </div>
                <input type="hidden" name="category" id="blog-category" value="{{ request('category') }}">
            </form>

            <div class="relative">
                <div id="blog-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @include('blog._cards', ['posts' => $posts])
                </div>
                <div id="blog-loading" class="absolute inset-0 bg-white/70 backdrop-blur-sm hidden items-center justify-center rounded-2xl z-10">
                    <svg class="animate-spin h-10 w-10 text-[#1d4ed8]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </div>
            </div>

            <div id="load-more-wrapper" class="text-center mt-10 {{ $posts->hasMorePages() ? '' : 'hidden' }}">
                <button id="load-more-blog" class="px-8 py-3.5 bg-white border-2 border-[#1d4ed8] text-[#1d4ed8] font-bold rounded-2xl hover:bg-[#1d4ed8] hover:text-white transition disabled:opacity-50" data-next-url="{{ $posts->nextPageUrl() }}">Load More Articles</button>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    (function() {
        const grid = document.getElementById('blog-grid');
        const filterForm = document.getElementById('blog-filter');
        const loadMoreBtn = document.getElementById('load-more-blog');
        const loadMoreWrapper = document.getElementById('load-more-wrapper');
        const loadingOverlay = document.getElementById('blog-loading');
        const searchInput = document.getElementById('blog-search');
        const searchClear = document.getElementById('blog-search-clear');
        const categoryInput = document.getElementById('blog-category');
        const catPills = document.querySelectorAll('.cat-pill');
        let searchTimer = null;

        function showLoading() { loadingOverlay.classList.remove('hidden'); loadingOverlay.classList.add('flex'); }
        function hideLoading() { loadingOverlay.classList.add('hidden'); loadingOverlay.classList.remove('flex'); }

        function updateLoadMoreButton(nextUrl) {
            if (nextUrl) {
                loadMoreBtn.setAttribute('data-next-url', nextUrl);
                loadMoreBtn.disabled = false;
                loadMoreWrapper.classList.remove('hidden');
            } else {
                loadMoreWrapper.classList.add('hidden');
            }
        }

        function setActivePill() {
            catPills.forEach(p => {
                const active = p.dataset.cat === categoryInput.value;
                p.className = 'cat-pill px-4 py-2 rounded-full text-sm font-semibold transition ' +
                    (active ? 'bg-[#1d4ed8] text-white shadow-lg shadow-blue-600/25' : 'bg-white text-slate-600 border border-slate-200 hover:border-[#1d4ed8] hover:text-[#1d4ed8]');
            });
        }

        async function fetchPosts(url, append = false) {
            const params = new URLSearchParams(new FormData(filterForm)).toString();
            const separator = url.includes('?') ? '&' : '?';
            const response = await fetch(`${url}${separator}${params}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await response.json();
            if (append) grid.insertAdjacentHTML('beforeend', data.items);
            else grid.innerHTML = data.items;
            updateLoadMoreButton(data.next_page_url);
        }

        async function applyFilters() {
            showLoading();
            try { await fetchPosts("{{ route('blog.index') }}"); }
            catch (e) { console.error('Filter error:', e); }
            finally { hideLoading(); }
        }

        searchInput.addEventListener('input', () => {
            searchClear.classList.toggle('hidden', !searchInput.value);
            clearTimeout(searchTimer);
            searchTimer = setTimeout(applyFilters, 400);
        });
        searchClear.addEventListener('click', () => {
            searchInput.value = '';
            searchClear.classList.add('hidden');
            applyFilters();
        });
        catPills.forEach(p => p.addEventListener('click', () => {
            categoryInput.value = p.dataset.cat;
            setActivePill();
            applyFilters();
        }));

        loadMoreBtn.addEventListener('click', async function() {
            const nextUrl = loadMoreBtn.getAttribute('data-next-url');
            if (!nextUrl) return;
            loadMoreBtn.disabled = true;
            loadMoreBtn.textContent = 'Loading...';
            try { await fetchPosts(nextUrl, true); }
            catch (e) { console.error('Load more error:', e); }
            finally { loadMoreBtn.textContent = 'Load More Articles'; loadMoreBtn.disabled = false; }
        });
    })();
</script>
@endpush

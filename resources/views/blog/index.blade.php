@extends('layouts.app')

@section('title', 'Blog')
@section('meta_description', 'Latest insights and updates from AK Tech SOL.')

@section('content')
    <x-frontend.page-banner
        title="Our Blog"
        subtitle="Insights, tutorials, and updates."
    />

    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form id="blog-filter" class="mt-10 mb-8 flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1 sm:flex-none sm:w-72">
                    <input type="text" name="search" id="blog-search" value="{{ request('search') }}" placeholder="Search blog posts..." class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 pr-10 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <button type="button" class="absolute inset-y-0 right-0 px-3 text-gray-400 hover:text-gray-600" onclick="clearSearchInput('blog-search')">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <select name="category" id="blog-category" class="block rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">Filter</button>
                <button type="button" id="clear-blog-filter" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">Clear</button>
            </form>

            <div class="relative">
                <div id="blog-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @include('blog._cards', ['posts' => $posts])
                </div>
                <div id="blog-loading" class="absolute inset-0 bg-white flex items-center justify-center hidden rounded-xl z-10">
                    <svg class="animate-spin h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </div>
            </div>

            <div id="load-more-wrapper" class="text-center mt-10">
                <button id="load-more-blog" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed" data-next-url="{{ $posts->nextPageUrl() }}">Load More</button>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    function clearSearchInput(id) { const el = document.getElementById(id); if (el) el.value = ''; }

    (function() {
        const grid = document.getElementById('blog-grid');
        const filterForm = document.getElementById('blog-filter');
        const loadMoreBtn = document.getElementById('load-more-blog');
        const loadMoreWrapper = document.getElementById('load-more-wrapper');
        const loadingOverlay = document.getElementById('blog-loading');
        const searchInput = document.getElementById('blog-search');
        const categorySelect = document.getElementById('blog-category');
        const clearFilterBtn = document.getElementById('clear-blog-filter');

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

        async function fetchPosts(url) {
            const params = new URLSearchParams(new FormData(filterForm)).toString();
            const separator = url.includes('?') ? '&' : '?';
            const fetchUrl = `${url}${separator}${params}`;
            const response = await fetch(fetchUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            return await response.json();
        }

        filterForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            loadMoreBtn.disabled = true;
            loadMoreBtn.textContent = 'Loading...';
            showLoading();
            try {
                const data = await fetchPosts("{{ route('blog.index') }}");
                grid.innerHTML = data.items;
                updateLoadMoreButton(data.next_page_url);
            } catch (error) {
                console.error('Filter error:', error);
            } finally {
                loadMoreBtn.textContent = 'Load More';
                loadMoreBtn.disabled = false;
                hideLoading();
            }
        });

        clearFilterBtn.addEventListener('click', function() {
            searchInput.value = '';
            categorySelect.value = '';
            filterForm.dispatchEvent(new Event('submit'));
        });

        loadMoreBtn.addEventListener('click', async function() {
            const nextUrl = loadMoreBtn.getAttribute('data-next-url');
            if (!nextUrl) return;
            loadMoreBtn.disabled = true;
            loadMoreBtn.textContent = 'Loading...';
            showLoading();
            try {
                const data = await fetchPosts(nextUrl);
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

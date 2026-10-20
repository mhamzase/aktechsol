@extends('layouts.app')

@section('title', 'Careers | ' . ($siteSettings->site_name ?? 'AK Tech SOL'))
@section('meta_description', 'Join the ' . ($siteSettings->site_name ?? 'AK Tech SOL') . ' team — see open jobs and internships and apply with your portfolio.')

@push('seo')
{{-- Structured data: the open positions as an ItemList + breadcrumbs.
     (Open Graph / Twitter tags come from the layout — no duplicates.) --}}
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "CollectionPage",
    "name": "Careers | {{ $siteSettings->site_name ?? 'AK Tech SOL' }}",
    "url": "{{ url()->current() }}",
    "mainEntity": {
        "@@type": "ItemList",
        "itemListElement": [
            @foreach($jobs as $i => $job)
            {
                "@@type": "ListItem",
                "position": {{ $i + 1 }},
                "name": {!! json_encode($job->title) !!},
                "url": "{{ route('careers.show', $job->slug) }}"
            }{{ !$loop->last ? ',' : '' }}
            @endforeach
        ]
    }
}
</script>
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ url('/') }}" },
        { "@@type": "ListItem", "position": 2, "name": "Careers", "item": "{{ url()->current() }}" }
    ]
}
</script>
@endpush

@section('content')
    <x-frontend.page-banner
        title="Careers"
        subtitle="Join our team — work on real projects for clients worldwide, from anywhere."
    />

    <section class="py-12 sm:py-16 bg-slate-50/60">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            {{-- Search + count --}}
            <form id="careers-search-form" action="{{ route('careers.index') }}" method="GET" class="flex flex-col sm:flex-row sm:items-center gap-3 mb-6">
                <div class="relative flex-1">
                    <svg class="w-5 h-5 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/></svg>
                    <input type="text" id="careers-search" name="search" value="{{ $search }}" placeholder="Search positions — e.g. video editor, designer…"
                           class="w-full rounded-2xl border border-slate-200 bg-white pl-12 pr-11 py-3.5 text-sm shadow-sm focus:border-[#1d4ed8] focus:ring-2 focus:ring-[#1d4ed8]/20 focus:outline-none transition">
                    @if($search)
                        <a href="{{ route('careers.index') }}" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600" title="Clear search">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                </div>
                <button type="submit" class="px-6 py-3.5 rounded-2xl bg-gradient-to-r from-[#1d4ed8] to-[#2f6fed] text-white text-sm font-bold hover:shadow-lg hover:shadow-blue-600/25 transition shrink-0">Search</button>
                <p id="careers-count" class="text-sm text-slate-500 shrink-0 sm:ml-1">{{ $jobs->total() }} open position{{ $jobs->total() === 1 ? '' : 's' }}</p>
            </form>

            {{-- List (first page server-rendered, more appended via AJAX) --}}
            <div id="careers-list">
                @include('careers._cards', ['jobs' => $jobs, 'search' => $search])
            </div>

            {{-- Load more --}}
            <div id="load-more-wrap" class="text-center mt-8 {{ $jobs->hasMorePages() ? '' : 'hidden' }}">
                <button type="button" id="load-more-btn" data-next-page="{{ $jobs->currentPage() + 1 }}"
                        class="inline-flex items-center gap-2 px-8 py-3.5 rounded-2xl bg-white border-2 border-[#1d4ed8] text-[#1d4ed8] font-bold text-sm hover:bg-[#1d4ed8] hover:text-white transition disabled:opacity-60">
                    Load More Positions
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    (function() {
        const listEl = document.getElementById('careers-list');
        const countEl = document.getElementById('careers-count');
        const wrapEl = document.getElementById('load-more-wrap');
        const moreBtn = document.getElementById('load-more-btn');
        const form = document.getElementById('careers-search-form');
        const input = document.getElementById('careers-search');
        if (!listEl || !moreBtn) return;

        let loading = false;

        async function fetchPage(page, append) {
            if (loading) return;
            loading = true;
            const original = moreBtn.innerHTML;
            if (append) { moreBtn.disabled = true; moreBtn.textContent = 'Loading...'; }
            try {
                const params = new URLSearchParams({ ajax: '1', page: String(page), search: input.value.trim() });
                const res = await fetch(`{{ route('careers.index') }}?${params}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (!res.ok) throw new Error('request failed');
                if (append) {
                    listEl.insertAdjacentHTML('beforeend', data.html);
                } else {
                    listEl.innerHTML = data.html;
                }
                countEl.textContent = `${data.total} open position${data.total === 1 ? '' : 's'}`;
                wrapEl.classList.toggle('hidden', !data.has_more);
                moreBtn.dataset.nextPage = String(data.next_page);
                // Keep the URL shareable without a reload.
                const url = new URL(location.href);
                const q = input.value.trim();
                q ? url.searchParams.set('search', q) : url.searchParams.delete('search');
                history.replaceState(null, '', url);
            } catch (e) {
                window.notify.error('Could not load positions. Please try again.');
            } finally {
                loading = false;
                if (append) { moreBtn.disabled = false; moreBtn.innerHTML = original; }
            }
        }

        moreBtn.addEventListener('click', () => fetchPage(parseInt(moreBtn.dataset.nextPage || '2', 10), true));

        // Search: submit or a short pause while typing reloads page 1.
        let debounce;
        form.addEventListener('submit', (e) => { e.preventDefault(); fetchPage(1, false); });
        input.addEventListener('input', () => {
            clearTimeout(debounce);
            debounce = setTimeout(() => fetchPage(1, false), 500);
        });
    })();
</script>
@endpush

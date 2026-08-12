@extends('layouts.app')

@section('title', 'Our Projects')
@section('meta_description', 'Explore our portfolio of successful projects.')

@section('content')
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold text-center text-gray-900">Our Projects</h1>
        <p class="text-center text-gray-500 mt-2">Take a look at our recent work.</p>

        <div id="projects-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-12">
            @include('projects._cards', ['projects' => $projects])
        </div>

        <div id="load-more-wrapper" class="text-center mt-10">
            <button id="load-more-projects" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition"
                    data-next-url="{{ $projects->nextPageUrl() }}">
                Load More
            </button>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    (function() {
        const button = document.getElementById('load-more-projects');
        const grid = document.getElementById('projects-grid');

        button.addEventListener('click', async function() {
            const nextUrl = button.getAttribute('data-next-url');
            if (!nextUrl) return;

            button.disabled = true;
            button.textContent = 'Loading...';

            try {
                const response = await fetch(nextUrl, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();

                grid.insertAdjacentHTML('beforeend', data.items);

                if (data.next_page_url) {
                    button.setAttribute('data-next-url', data.next_page_url);
                    button.textContent = 'Load More';
                    button.disabled = false;
                } else {
                    button.remove();
                }
            } catch (error) {
                button.textContent = 'Load More';
                button.disabled = false;
                console.error('Failed to load more projects:', error);
            }
        });
    })();
</script>
@endpush

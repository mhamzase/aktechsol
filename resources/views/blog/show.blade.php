@extends('layouts.app')

@section('title', $post->seo_title ?? $post->title)
@section('meta_description', $post->meta_description)

@section('content')
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4">
        @if($post->getFeaturedImageUrl())
            <img src="{{ $post->getFeaturedImageUrl() }}" alt="{{ $post->title }}"
                 loading="lazy"
                 class="w-full h-64 object-cover rounded-xl mb-8">
        @endif

        <div class="flex items-center gap-2 text-sm text-blue-600 font-medium mb-3">
            <span>{{ $post->category?->name ?? 'General' }}</span>
            <span class="text-gray-300">|</span>
            <span>{{ $post->published_at?->format('M d, Y') }}</span>
        </div>

        <h1 class="text-4xl font-bold text-gray-900">{{ $post->title }}</h1>
        @if($post->excerpt)
            <p class="mt-4 text-lg text-gray-600 italic">{{ $post->excerpt }}</p>
        @endif

        {{-- Social Share Buttons --}}
        <div class="mt-6 flex flex-wrap items-center gap-3">
            <span class="text-sm text-gray-500">Share:</span>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
               target="_blank" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                Share
            </a>
            <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}"
               target="_blank" class="inline-flex items-center px-3 py-2 bg-sky-500 text-white text-sm rounded-lg hover:bg-sky-600 transition">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                Tweet
            </a>
            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}"
               target="_blank" class="inline-flex items-center px-3 py-2 bg-blue-800 text-white text-sm rounded-lg hover:bg-blue-900 transition">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                Share
            </a>
        </div>

        <div class="mt-8 text-gray-700 leading-relaxed">
            {!! $post->content !!}
        </div>

        <a href="{{ route('blog.index') }}" class="inline-block mt-8 text-blue-600 hover:underline">&larr; Back to Blog</a>
    </div>
</section>

@if($related->count() > 0)
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-gray-900">Related Posts</h2>
        </div>

        @if($related->count() > 3)
            {{-- Slider --}}
            <div class="relative px-2 md:px-4">
                <button id="related-prev" class="absolute -left-2 top-1/2 -translate-y-1/2 z-10 bg-white p-2 rounded-full shadow-lg hover:bg-blue-50 transition border border-gray-200">
                    <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button id="related-next" class="absolute -right-2 top-1/2 -translate-y-1/2 z-10 bg-white p-2 rounded-full shadow-lg hover:bg-blue-50 transition border border-gray-200">
                    <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>

                <div id="related-slider" class="overflow-hidden cursor-grab active:cursor-grabbing">
                    <div id="related-track" class="flex transition-transform duration-300">
                        @foreach($related as $relatedPost)
                            <div class="related-slide w-full md:w-1/2 lg:w-1/3 flex-shrink-0 px-2 md:px-3">
                                <a href="{{ route('blog.show', $relatedPost->slug) }}" class="block bg-white rounded-xl overflow-hidden border border-gray-100 hover:shadow-md transition h-full">
                                    @if($relatedPost->getFeaturedImageUrl())
                                        <img src="{{ $relatedPost->getFeaturedImageUrl() }}" alt="{{ $relatedPost->title }}" loading="lazy" class="h-40 w-full object-cover">
                                    @else
                                        <div class="h-40 bg-gray-200"></div>
                                    @endif
                                    <div class="p-4">
                                        <p class="text-xs text-blue-600 font-semibold">{{ $relatedPost->category?->name ?? 'General' }}</p>
                                        <h3 class="font-semibold text-gray-900 mt-1">{{ $relatedPost->title }}</h3>
                                        <p class="text-sm text-gray-500 mt-1">{{ $relatedPost->published_at?->format('M d, Y') }}</p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($related as $relatedPost)
                    <a href="{{ route('blog.show', $relatedPost->slug) }}" class="block bg-white rounded-xl overflow-hidden border border-gray-100 hover:shadow-md transition h-full">
                        @if($relatedPost->getFeaturedImageUrl())
                            <img src="{{ $relatedPost->getFeaturedImageUrl() }}" alt="{{ $relatedPost->title }}" loading="lazy" class="h-40 w-full object-cover">
                        @else
                            <div class="h-40 bg-gray-200"></div>
                        @endif
                        <div class="p-4">
                            <p class="text-xs text-blue-600 font-semibold">{{ $relatedPost->category?->name ?? 'General' }}</p>
                            <h3 class="font-semibold text-gray-900 mt-1">{{ $relatedPost->title }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $relatedPost->published_at?->format('M d, Y') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endif
@endsection

@push('scripts')
@if($related->count() > 3)
<script>
    (function() {
        // existing slider code (same as before)
        const track = document.getElementById('related-track');
        const prevBtn = document.getElementById('related-prev');
        const nextBtn = document.getElementById('related-next');
        const slider = document.getElementById('related-slider');
        if (!track || !prevBtn || !nextBtn || !slider) return;
        const slides = track.querySelectorAll('.related-slide');
        let currentIndex = 0;

        function getVisibleCount() {
            if (window.innerWidth < 768) return 1;
            if (window.innerWidth < 1024) return 2;
            return 3;
        }

        function getSlideWidth() {
            const visible = getVisibleCount();
            const slide = slides[0];
            if (!slide) return 0;
            const gap = 16;
            const totalWidth = slider.offsetWidth;
            return (totalWidth - (visible - 1) * gap) / visible;
        }

        function updateSlider(animate = true) {
            if (!track || !slides.length) return;
            const visible = getVisibleCount();
            const slideWidth = getSlideWidth();
            const maxIndex = Math.max(0, slides.length - visible);
            if (currentIndex > maxIndex) currentIndex = maxIndex;
            if (currentIndex < 0) currentIndex = 0;
            if (!animate) track.style.transition = 'none';
            track.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
            if (!animate) {
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        track.style.transition = 'transform 300ms';
                    });
                });
            }
        }

        if (slides.length > 0) {
            nextBtn.addEventListener('click', () => { const visible = getVisibleCount(); const maxIndex = Math.max(0, slides.length - visible); if (currentIndex < maxIndex) currentIndex++; else currentIndex = 0; updateSlider(); });
            prevBtn.addEventListener('click', () => { const visible = getVisibleCount(); const maxIndex = Math.max(0, slides.length - visible); if (currentIndex > 0) currentIndex--; else currentIndex = maxIndex; updateSlider(); });

            // Drag / touch (same as before)
            let isDragging = false;
            let startX = 0;
            let currentTranslate = 0;

            slider.addEventListener('mousedown', (e) => { e.preventDefault(); isDragging = true; startX = e.clientX; currentTranslate = -currentIndex * getSlideWidth(); track.style.transition = 'none'; slider.classList.add('cursor-grabbing'); });
            window.addEventListener('mousemove', (e) => { if (!isDragging) return; const delta = e.clientX - startX; track.style.transform = `translateX(${currentTranslate + delta}px)`; });
            window.addEventListener('mouseup', () => { if (!isDragging) return; isDragging = false; slider.classList.remove('cursor-grabbing'); track.style.transition = 'transform 300ms'; const delta = currentTranslate - parseInt(track.style.transform.replace('translateX(', '').replace('px)', '')) || 0; const slideWidth = getSlideWidth(); const threshold = slideWidth / 4; if (delta > threshold && currentIndex > 0) currentIndex--; else if (delta < -threshold && currentIndex < slides.length - getVisibleCount()) currentIndex++; updateSlider(); });

            slider.addEventListener('touchstart', (e) => { isDragging = true; startX = e.touches[0].clientX; currentTranslate = -currentIndex * getSlideWidth(); track.style.transition = 'none'; }, { passive: true });
            slider.addEventListener('touchmove', (e) => { if (!isDragging) return; const delta = e.touches[0].clientX - startX; track.style.transform = `translateX(${currentTranslate + delta}px)`; }, { passive: true });
            slider.addEventListener('touchend', () => { if (!isDragging) return; isDragging = false; track.style.transition = 'transform 300ms'; const delta = currentTranslate - parseInt(track.style.transform.replace('translateX(', '').replace('px)', '')) || 0; const slideWidth = getSlideWidth(); const threshold = slideWidth / 4; if (delta > threshold && currentIndex > 0) currentIndex--; else if (delta < -threshold && currentIndex < slides.length - getVisibleCount()) currentIndex++; updateSlider(); });

            window.addEventListener('resize', () => { currentIndex = 0; updateSlider(false); });
            window.addEventListener('load', () => { updateSlider(false); });
            updateSlider(false);
        }
    })();
</script>
@endif
@endpush

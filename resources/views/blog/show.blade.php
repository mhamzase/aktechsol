@extends('layouts.app')

@section('title', $post->seo_title ?? $post->title)
@section('meta_description', $post->meta_description)

@section('content')
<x-frontend.page-banner
    title="{{ $post->seo_title ?? $post->title }}"
    subtitle="{{ $post->excerpt }}"
/>
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4">
            @if ($post->getFeaturedImageUrl())
                <img src="{{ $post->getFeaturedImageUrl() }}" alt="{{ $post->title }}"
                    class="w-full h-64 object-cover rounded-xl mb-8">
            @endif
            <div class="flex items-center gap-2 text-sm text-blue-600 font-medium mb-3">
                <span>{{ $post->category?->name ?? 'General' }}</span>
                <span class="text-gray-300">|</span>
                <span>{{ $post->published_at?->format('M d, Y') }}</span>
            </div>
            <h1 class="text-4xl font-bold text-gray-900">{{ $post->title }}</h1>
            @if ($post->excerpt)
                <p class="mt-4 text-lg text-gray-600 italic">{{ $post->excerpt }}</p>
            @endif
            <div class="mt-8 text-gray-700 leading-relaxed">
                {!! $post->content !!}
            </div>

            <a href="{{ route('blog.index') }}" class="inline-block mt-8 text-blue-600 hover:underline">&larr; Back to
                Blog</a>
        </div>
    </section>

    @if ($related->count() > 0)
        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-bold text-gray-900">Related Posts</h2>
                </div>

                @if ($related->count() > 3)
                    {{-- Slider (4+ posts) --}}
                    <div class="relative px-2 md:px-4">
                        <button id="related-prev"
                            class="absolute -left-2 top-1/2 -translate-y-1/2 z-10 bg-white p-2 rounded-full shadow-lg hover:bg-blue-50 transition border border-gray-200">
                            <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button id="related-next"
                            class="absolute -right-2 top-1/2 -translate-y-1/2 z-10 bg-white p-2 rounded-full shadow-lg hover:bg-blue-50 transition border border-gray-200">
                            <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                        <div id="related-slider" class="overflow-hidden cursor-grab active:cursor-grabbing">
                            <div id="related-track" class="flex transition-transform duration-300">
                                @foreach ($related as $relatedPost)
                                    <div class="related-slide w-full md:w-1/2 lg:w-1/3 flex-shrink-0 px-2 md:px-3">
                                        <a href="{{ route('blog.show', $relatedPost->slug) }}"
                                            class="block bg-white rounded-xl overflow-hidden border border-gray-100 hover:shadow-md transition h-full">
                                            @if ($relatedPost->getFeaturedImageUrl())
                                                <img src="{{ $relatedPost->getFeaturedImageUrl() }}"
                                                    alt="{{ $relatedPost->title }}" class="h-40 w-full object-cover">
                                            @else
                                                <div class="h-40 bg-gray-200"></div>
                                            @endif
                                            <div class="p-4">
                                                <p class="text-xs text-blue-600 font-semibold">
                                                    {{ $relatedPost->category?->name ?? 'General' }}</p>
                                                <h3 class="font-semibold text-gray-900 mt-1">{{ $relatedPost->title }}</h3>
                                                <p class="text-sm text-gray-500 mt-1">
                                                    {{ $relatedPost->published_at?->format('M d, Y') }}</p>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Static grid (3 or fewer) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach ($related as $relatedPost)
                            <a href="{{ route('blog.show', $relatedPost->slug) }}"
                                class="block bg-white rounded-xl overflow-hidden border border-gray-100 hover:shadow-md transition h-full">
                                @if ($relatedPost->getFeaturedImageUrl())
                                    <img src="{{ $relatedPost->getFeaturedImageUrl() }}" alt="{{ $relatedPost->title }}"
                                        class="h-40 w-full object-cover">
                                @else
                                    <div class="h-40 bg-gray-200"></div>
                                @endif
                                <div class="p-4">
                                    <p class="text-xs text-blue-600 font-semibold">
                                        {{ $relatedPost->category?->name ?? 'General' }}</p>
                                    <h3 class="font-semibold text-gray-900 mt-1">{{ $relatedPost->title }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $relatedPost->published_at?->format('M d, Y') }}</p>
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
        // same slider code as before, but only runs when slider exists
        const track = document.getElementById('related-track');
        const prevBtn = document.getElementById('related-prev');
        const nextBtn = document.getElementById('related-next');
        const slider = document.getElementById('related-slider');
        const slides = track?.querySelectorAll('.related-slide') || [];
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
            nextBtn.addEventListener('click', () => {
                const visible = getVisibleCount();
                const maxIndex = Math.max(0, slides.length - visible);
                if (currentIndex < maxIndex) {
                    currentIndex++;
                } else {
                    currentIndex = 0;
                }
                updateSlider();
            });

            prevBtn.addEventListener('click', () => {
                const visible = getVisibleCount();
                const maxIndex = Math.max(0, slides.length - visible);
                if (currentIndex > 0) {
                    currentIndex--;
                } else {
                    currentIndex = maxIndex;
                }
                updateSlider();
            });

            // Drag / touch
            let isDragging = false;
            let startX = 0;
            let currentTranslate = 0;

            slider.addEventListener('mousedown', (e) => {
                e.preventDefault();
                isDragging = true;
                startX = e.clientX;
                currentTranslate = -currentIndex * getSlideWidth();
                track.style.transition = 'none';
                slider.classList.add('cursor-grabbing');
            });

            window.addEventListener('mousemove', (e) => {
                if (!isDragging) return;
                const delta = e.clientX - startX;
                track.style.transform = `translateX(${currentTranslate + delta}px)`;
            });

            window.addEventListener('mouseup', () => {
                if (!isDragging) return;
                isDragging = false;
                slider.classList.remove('cursor-grabbing');
                track.style.transition = 'transform 300ms';
                const delta = currentTranslate - parseInt(track.style.transform.replace('translateX(', '').replace('px)', '')) || 0;
                const slideWidth = getSlideWidth();
                const threshold = slideWidth / 4;
                if (delta > threshold && currentIndex > 0) {
                    currentIndex--;
                } else if (delta < -threshold && currentIndex < slides.length - getVisibleCount()) {
                    currentIndex++;
                }
                updateSlider();
            });

            slider.addEventListener('touchstart', (e) => {
                isDragging = true;
                startX = e.touches[0].clientX;
                currentTranslate = -currentIndex * getSlideWidth();
                track.style.transition = 'none';
            }, { passive: true });

            slider.addEventListener('touchmove', (e) => {
                if (!isDragging) return;
                const delta = e.touches[0].clientX - startX;
                track.style.transform = `translateX(${currentTranslate + delta}px)`;
            }, { passive: true });

            slider.addEventListener('touchend', () => {
                if (!isDragging) return;
                isDragging = false;
                track.style.transition = 'transform 300ms';
                const delta = currentTranslate - parseInt(track.style.transform.replace('translateX(', '').replace('px)', '')) || 0;
                const slideWidth = getSlideWidth();
                const threshold = slideWidth / 4;
                if (delta > threshold && currentIndex > 0) {
                    currentIndex--;
                } else if (delta < -threshold && currentIndex < slides.length - getVisibleCount()) {
                    currentIndex++;
                }
                updateSlider();
            });

            window.addEventListener('resize', () => {
                currentIndex = 0;
                updateSlider(false);
            });

            window.addEventListener('load', () => {
                updateSlider(false);
            });

            updateSlider(false);
        }
    })();
</script>
@endif
@endpush

@push('scripts')
    <script>
        (function() {
            const track = document.getElementById('related-track');
            const prevBtn = document.getElementById('related-prev');
            const nextBtn = document.getElementById('related-next');
            const slider = document.getElementById('related-slider');
            const slides = track?.querySelectorAll('.related-slide') || [];
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
                nextBtn.addEventListener('click', () => {
                    const visible = getVisibleCount();
                    const maxIndex = Math.max(0, slides.length - visible);
                    if (currentIndex < maxIndex) {
                        currentIndex++;
                    } else {
                        currentIndex = 0;
                    }
                    updateSlider();
                });

                prevBtn.addEventListener('click', () => {
                    const visible = getVisibleCount();
                    const maxIndex = Math.max(0, slides.length - visible);
                    if (currentIndex > 0) {
                        currentIndex--;
                    } else {
                        currentIndex = maxIndex;
                    }
                    updateSlider();
                });

                // Drag / touch
                let isDragging = false;
                let startX = 0;
                let currentTranslate = 0;

                slider.addEventListener('mousedown', (e) => {
                    e.preventDefault();
                    isDragging = true;
                    startX = e.clientX;
                    currentTranslate = -currentIndex * getSlideWidth();
                    track.style.transition = 'none';
                    slider.classList.add('cursor-grabbing');
                });

                window.addEventListener('mousemove', (e) => {
                    if (!isDragging) return;
                    const delta = e.clientX - startX;
                    track.style.transform = `translateX(${currentTranslate + delta}px)`;
                });

                window.addEventListener('mouseup', () => {
                    if (!isDragging) return;
                    isDragging = false;
                    slider.classList.remove('cursor-grabbing');
                    track.style.transition = 'transform 300ms';
                    const delta = currentTranslate - parseInt(track.style.transform.replace('translateX(', '')
                        .replace('px)', '')) || 0;
                    const slideWidth = getSlideWidth();
                    const threshold = slideWidth / 4;
                    if (delta > threshold && currentIndex > 0) {
                        currentIndex--;
                    } else if (delta < -threshold && currentIndex < slides.length - getVisibleCount()) {
                        currentIndex++;
                    }
                    updateSlider();
                });

                slider.addEventListener('touchstart', (e) => {
                    isDragging = true;
                    startX = e.touches[0].clientX;
                    currentTranslate = -currentIndex * getSlideWidth();
                    track.style.transition = 'none';
                }, {
                    passive: true
                });

                slider.addEventListener('touchmove', (e) => {
                    if (!isDragging) return;
                    const delta = e.touches[0].clientX - startX;
                    track.style.transform = `translateX(${currentTranslate + delta}px)`;
                }, {
                    passive: true
                });

                slider.addEventListener('touchend', () => {
                    if (!isDragging) return;
                    isDragging = false;
                    track.style.transition = 'transform 300ms';
                    const delta = currentTranslate - parseInt(track.style.transform.replace('translateX(', '')
                        .replace('px)', '')) || 0;
                    const slideWidth = getSlideWidth();
                    const threshold = slideWidth / 4;
                    if (delta > threshold && currentIndex > 0) {
                        currentIndex--;
                    } else if (delta < -threshold && currentIndex < slides.length - getVisibleCount()) {
                        currentIndex++;
                    }
                    updateSlider();
                });

                window.addEventListener('resize', () => {
                    currentIndex = 0;
                    updateSlider(false);
                });

                window.addEventListener('load', () => {
                    updateSlider(false);
                });

                updateSlider(false);
            }
        })();
    </script>
@endpush

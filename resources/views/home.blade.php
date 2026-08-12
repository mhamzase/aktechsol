@extends('layouts.app')

@section('title', 'Home - ' . ($siteSettings->site_name ?? 'AK Tech SOL'))
@section('meta_description',
    'AK Tech SOL is a professional software and freelancing agency delivering top-notch digital
    solutions.')

@section('content')
    {{-- Hero --}}
    <section class="bg-gradient-to-br from-blue-950 via-blue-900 to-blue-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
            <div class="max-w-3xl">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight">
                    We Build <span class="text-blue-400">Digital Products</span> That Matter
                </h1>
                <p class="mt-6 text-lg sm:text-xl text-blue-100/80 max-w-2xl">
                    AK Tech SOL is a full-service software agency helping startups and businesses design, develop, and
                    launch exceptional web and mobile experiences.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ url('/services') }}"
                        class="inline-flex items-center px-6 py-3 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 transition shadow-lg">Our
                        Services</a>
                    <a href="{{ url('/contact') }}"
                        class="inline-flex items-center px-6 py-3 rounded-lg border border-blue-400 text-blue-100 font-semibold hover:bg-white/10 transition">Get
                        in Touch</a>
                </div>
            </div>
        </div>
    </section>

    {{-- Services placeholder --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">What We Do</h2>
                <p class="mt-4 text-gray-500 max-w-2xl mx-auto">We offer a wide range of digital services to help your
                    business grow.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($services as $service)
                    <a href="{{ route('services.show', $service->slug) }}"
                        class="block bg-gray-50 rounded-xl p-6 border border-gray-100 hover:shadow-md transition">
                        @if ($service->getThumbnailUrl())
                            <img src="{{ $service->getThumbnailUrl() }}" alt="{{ $service->title }}"
                                class="h-32 w-full object-cover rounded-lg mb-4">
                        @endif
                        <h3 class="text-lg font-semibold text-gray-900 mb-2 hover:text-blue-600">{{ $service->title }}</h3>
                        <p class="text-sm text-gray-500">{{ $service->short_description }}</p>
                    </a>
                @empty
                    <div class="col-span-full text-center text-gray-500">No services available yet.</div>
                @endforelse
            </div>
            <div class="text-center mt-10">
                <a href="{{ route('services.index') }}"
                    class="inline-flex items-center px-6 py-3 border border-blue-600 text-blue-600 font-semibold rounded-lg hover:bg-blue-600 hover:text-white transition">
                    View All Services
                </a>
            </div>
        </div>
    </section>

    {{-- Portfolio placeholder --}}
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">Our Work</h2>
                <p class="mt-4 text-gray-500 max-w-2xl mx-auto">Take a look at some of our recent projects.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($projects as $project)
                    <a href="{{ route('projects.show', $project->slug) }}"
                        class="block bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-md transition">
                        @if ($project->getFeaturedImageUrl())
                            <img src="{{ $project->getFeaturedImageUrl() }}" alt="{{ $project->title }}"
                                class="h-48 w-full object-cover">
                        @endif
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 hover:text-blue-600">{{ $project->title }}</h3>
                            <p class="text-sm text-gray-500 mt-2">{{ $project->short_description }}</p>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center text-gray-500">No projects available yet.</div>
                @endforelse
            </div>
            <div class="text-center mt-10">
                <a href="{{ route('projects.index') }}"
                    class="inline-flex items-center px-6 py-3 border border-blue-600 text-blue-600 font-semibold rounded-lg hover:bg-blue-600 hover:text-white transition">
                    View All Projects
                </a>
            </div>
        </div>
    </section>

    {{-- Testimonials --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">What Our Clients Say</h2>
            </div>

            <div class="relative px-2 md:px-4">
                {{-- Arrows --}}
                <button id="testimonial-prev"
                    class="absolute -left-2 top-1/2 -translate-y-1/2 z-10 bg-white p-2 rounded-full shadow-lg hover:bg-blue-50 transition border border-gray-200">
                    <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button id="testimonial-next"
                    class="absolute -right-2 top-1/2 -translate-y-1/2 z-10 bg-white p-2 rounded-full shadow-lg hover:bg-blue-50 transition border border-gray-200">
                    <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                {{-- Slider container --}}
                <div id="testimonial-slider" class="overflow-hidden cursor-grab active:cursor-grabbing">
                    <div id="testimonial-track" class="flex transition-transform duration-300">
                        @forelse($testimonials as $testimonial)
                            <div class="testimonial-slide w-full md:w-1/2 lg:w-1/3 flex-shrink-0 px-2 md:px-3">
                                <div class="bg-gray-50 rounded-xl border border-gray-100 h-full flex flex-col p-6">
                                    {{-- Stars --}}
                                    <div class="flex items-center gap-1 text-yellow-400 mb-3">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $testimonial->rating)
                                                <span>★</span>
                                            @else
                                                <span class="text-gray-300">★</span>
                                            @endif
                                        @endfor
                                    </div>

                                    {{-- Message (fixed height + ellipsis) --}}
                                    <div class="message-box flex-1 overflow-hidden">
                                        <p class="text-gray-600 italic line-clamp-4 md:line-clamp-5">
                                            "{{ $testimonial->content }}"
                                        </p>
                                    </div>

                                    {{-- Client info (fixed at bottom) --}}
                                    <div class="mt-4 pt-4 border-t border-gray-200 flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-blue-200 overflow-hidden flex-shrink-0">
                                            @if ($testimonial->getPhotoUrl())
                                                <img src="{{ $testimonial->getPhotoUrl() }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <div
                                                    class="w-full h-full flex items-center justify-center text-blue-600 font-semibold">
                                                    {{ strtoupper(substr($testimonial->client_name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-medium text-gray-900 truncate">{{ $testimonial->client_name }}
                                            </p>
                                            <p class="text-sm text-gray-500 truncate">
                                                {{ $testimonial->client_position }} @if ($testimonial->company)
                                                    , {{ $testimonial->company }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 w-full">No testimonials available yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Blog preview placeholder --}}
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">Latest from the Blog</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($posts as $post)
                    <a href="{{ route('blog.show', $post->slug) }}"
                        class="block bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-md transition">
                        @if ($post->getFeaturedImageUrl())
                            <img src="{{ $post->getFeaturedImageUrl() }}" alt="{{ $post->title }}"
                                class="h-40 w-full object-cover">
                        @else
                            <div class="h-40 bg-gray-200"></div>
                        @endif
                        <div class="p-4">
                            <p class="text-xs text-blue-600 font-semibold">{{ $post->category?->name ?? 'General' }}</p>
                            <h3 class="font-semibold text-gray-900 mt-1">{{ $post->title }}</h3>
                            <p class="text-sm text-gray-500 mt-2 line-clamp-2">{{ $post->excerpt }}</p>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center text-gray-500">No blog posts available yet.</div>
                @endforelse
            </div>
            <div class="text-center mt-10">
                <a href="{{ route('blog.index') }}"
                    class="inline-flex items-center px-6 py-3 border border-blue-600 text-blue-600 font-semibold rounded-lg hover:bg-blue-600 hover:text-white transition">
                    View All Posts
                </a>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="bg-white text-gray-900 py-16">
        <div class="max-w-4xl mx-auto text-center px-4">
            <h2 class="text-3xl font-bold ">Ready to Start Your Project?</h2>
            <p class="mt-4">Let's discuss your ideas and turn them into reality.</p>
            <a href="{{ url('/contact') }}"
                class="mt-6 inline-block px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">Contact
                Us</a>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        (function() {
            const track = document.getElementById('testimonial-track');
            const prevBtn = document.getElementById('testimonial-prev');
            const nextBtn = document.getElementById('testimonial-next');
            const slider = document.getElementById('testimonial-slider');
            const slides = track.querySelectorAll('.testimonial-slide');
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
                const gap = 16; // px between slides (adjust if needed)
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
                nextBtn.addEventListener('click', function() {
                    const visible = getVisibleCount();
                    const maxIndex = Math.max(0, slides.length - visible);
                    if (currentIndex < maxIndex) {
                        currentIndex++;
                    } else {
                        currentIndex = 0; // loop
                    }
                    updateSlider();
                });

                prevBtn.addEventListener('click', function() {
                    const visible = getVisibleCount();
                    const maxIndex = Math.max(0, slides.length - visible);
                    if (currentIndex > 0) {
                        currentIndex--;
                    } else {
                        currentIndex = maxIndex; // loop
                    }
                    updateSlider();
                });

                // Drag / touch support
                let isDragging = false;
                let startX = 0;
                let currentTranslate = 0;

                function handleDragStart(clientX) {
                    isDragging = true;
                    startX = clientX;
                    currentTranslate = -currentIndex * getSlideWidth();
                    track.style.transition = 'none';
                    track.classList.add('cursor-grabbing');
                }

                function handleDragMove(clientX) {
                    if (!isDragging) return;
                    const delta = clientX - startX;
                    track.style.transform = `translateX(${currentTranslate + delta}px)`;
                }

                function handleDragEnd() {
                    if (!isDragging) return;
                    isDragging = false;
                    track.classList.remove('cursor-grabbing');
                    track.style.transition = 'transform 300ms';
                    const delta = currentTranslate - parseInt(track.style.transform.replace('translateX(', '').replace(
                        'px)', '')) || 0;
                    const slideWidth = getSlideWidth();
                    const threshold = slideWidth / 4;
                    if (delta > threshold && currentIndex > 0) {
                        currentIndex--;
                    } else if (delta < -threshold && currentIndex < slides.length - getVisibleCount()) {
                        currentIndex++;
                    }
                    updateSlider();
                }

                // Mouse events
                slider.addEventListener('mousedown', (e) => {
                    e.preventDefault();
                    handleDragStart(e.clientX);
                });
                window.addEventListener('mousemove', (e) => {
                    if (isDragging) handleDragMove(e.clientX);
                });
                window.addEventListener('mouseup', handleDragEnd);

                // Touch events
                slider.addEventListener('touchstart', (e) => {
                    handleDragStart(e.touches[0].clientX);
                }, {
                    passive: true
                });
                slider.addEventListener('touchmove', (e) => {
                    if (isDragging) handleDragMove(e.touches[0].clientX);
                }, {
                    passive: true
                });
                slider.addEventListener('touchend', handleDragEnd);

                window.addEventListener('resize', function() {
                    currentIndex = 0;
                    updateSlider(false);
                });

                window.addEventListener('load', function() {
                    updateSlider(false);
                });

                updateSlider(false);
            }
        })();
    </script>
@endpush

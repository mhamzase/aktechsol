@extends('layouts.app')

@section('title', ($siteSettings->site_name ?? 'AK Tech SOL') . ' — Web Development, Mobile Apps & Digital Services Agency')
@section('meta_description', 'AK Tech SOL is a full-service digital agency offering web development, mobile app development, UI/UX design, and cloud solutions. 50+ projects delivered with 5-star client ratings. Get a free quote today.')

@push('seo')
<meta property="og:title" content="{{ ($siteSettings->site_name ?? 'AK Tech SOL') . ' — Web Development, Mobile Apps & Digital Services Agency' }}">
<meta property="og:description" content="AK Tech SOL is a full-service digital agency offering web development, mobile app development, UI/UX design, and cloud solutions. Trusted by clients worldwide.">
<meta property="og:url" content="{{ url('/') }}">
<meta property="og:type" content="website">
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "Organization",
    "name": "{{ $siteSettings->site_name ?? 'AK Tech SOL' }}",
    "url": "{{ url('/') }}",
    "description": "Full-service digital agency offering web development, mobile apps, UI/UX design and cloud solutions.",
    @if($logoUrl ?? null)"logo": "{{ $logoUrl }}",@endif
    "sameAs": [
        @if($siteSettings->facebook_url ?? null)"{{ $siteSettings->facebook_url }}",@endif
        @if($siteSettings->linkedin_url ?? null)"{{ $siteSettings->linkedin_url }}",@endif
        @if($siteSettings->instagram_url ?? null)"{{ $siteSettings->instagram_url }}",@endif
        @if($siteSettings->twitter_url ?? null)"{{ $siteSettings->twitter_url }}"@endif
    ]
}
</script>
@endpush

@section('content')
    {{-- ============ HERO ============ --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-[#0a1f44] via-[#12307e] to-[#1d4ed8] text-white">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(rgba(255,255,255,0.4) 1px, transparent 1px); background-size: 28px 28px;"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-blue-500/20 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-indigo-500/20 blur-3xl"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
            <div class="max-w-3xl">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-sm font-medium text-blue-100 mb-6">
                    <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                    Trusted by clients in 15+ countries
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight tracking-tight">
                    We Build <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-cyan-300">Digital Products</span> That Matter
                </h1>
                <p class="mt-6 text-lg sm:text-xl text-blue-100/80 max-w-2xl leading-relaxed">
                    AK Tech SOL is a full-service software agency helping startups and businesses <strong class="text-white">worldwide</strong> design, develop, and launch exceptional web and mobile experiences.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ url('/services') }}" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-xl bg-white text-[#12307e] font-bold hover:bg-blue-50 transition shadow-xl shadow-blue-900/30">
                        Our Services
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                    <a href="{{ url('/contact') }}" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-xl border-2 border-white/30 text-white font-bold hover:bg-white/10 transition">
                        Get in Touch
                    </a>
                </div>

                {{-- Stats: slots 1-3 are editable in Settings; the Avg. Rating
                     is ALWAYS static (4.9) and never settings-driven. --}}
                <div class="mt-10 grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 max-w-2xl">
                    <div>
                        <p class="text-2xl sm:text-3xl font-extrabold">{{ $siteSettings->stat1_value ?? '50+' }}</p>
                        <p class="text-xs sm:text-sm text-blue-200/70 mt-1">{{ $siteSettings->stat1_label ?? 'Projects Done' }}</p>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-extrabold">{{ $siteSettings->stat2_value ?? '15+' }}</p>
                        <p class="text-xs sm:text-sm text-blue-200/70 mt-1">{{ $siteSettings->stat2_label ?? 'Countries Served' }}</p>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-extrabold">{{ $siteSettings->stat3_value ?? '100+' }}</p>
                        <p class="text-xs sm:text-sm text-blue-200/70 mt-1">{{ $siteSettings->stat3_label ?? 'Happy Clients' }}</p>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-extrabold">4.9★</p>
                        <p class="text-xs sm:text-sm text-blue-200/70 mt-1">Avg. Rating</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Wave divider --}}
        <svg class="block w-full text-white -mb-px" viewBox="0 0 1440 60" fill="currentColor" preserveAspectRatio="none"><path d="M0,30 C360,60 1080,0 1440,30 L1440,60 L0,60 Z"/></svg>
    </section>

    {{-- ============ SERVICES ============ --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-sm font-bold text-[#1d4ed8] uppercase tracking-widest">What we do</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-2">Services Built for Growth</h2>
                <p class="mt-4 text-slate-500 max-w-2xl mx-auto">From idea to launch — we handle every part of your digital journey.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($services as $service)
                    <a href="{{ route('services.show', $service->slug) }}" class="group bg-slate-50 hover:bg-white rounded-2xl p-6 border border-slate-100 hover:border-blue-200 hover:shadow-xl hover:shadow-blue-100/50 transition-all duration-300">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#1d4ed8] to-[#12307e] text-white flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            @if($service->icon)
                                <i class="{{ $service->icon }} text-xl"></i>
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            @endif
                        </div>
                        <h3 class="font-bold text-lg text-slate-900 group-hover:text-[#1d4ed8] transition-colors">{{ $service->title }}</h3>
                        <p class="text-sm text-slate-500 mt-2 line-clamp-2">{{ $service->short_description }}</p>
                        <span class="inline-flex items-center gap-1 text-sm font-semibold text-[#1d4ed8] mt-4">
                            Learn more
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </span>
                    </a>
                @empty
                    <p class="col-span-full text-center text-slate-400">Services coming soon.</p>
                @endforelse
            </div>
            <div class="text-center mt-10">
                <a href="{{ url('/services') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl border-2 border-[#1d4ed8] text-[#1d4ed8] font-bold hover:bg-[#1d4ed8] hover:text-white transition">
                    View All Services
                </a>
            </div>
        </div>
    </section>

    {{-- ============ WHY US ============ --}}
    <section class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="text-sm font-bold text-[#1d4ed8] uppercase tracking-widest">Why choose us</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-2">A Partner, Not Just a Vendor</h2>
                    <p class="mt-4 text-slate-500 leading-relaxed">We treat your project like our own. Transparent process, regular updates, and code you'll be proud of.</p>
                    <div class="mt-8 space-y-5">
                        @foreach([
                            ['Global clientele', 'We serve clients in 15+ countries, working smoothly across all timezones.'],
                            ['Dedicated team', 'You work directly with the people building your product.'],
                            ['Transparent pricing', 'Clear quotes, no hidden costs, milestone-based payments.'],
                            ['On-time delivery', 'Realistic timelines we actually meet.'],
                        ] as [$title, $desc])
                            <div class="flex gap-4">
                                <span class="w-10 h-10 rounded-xl bg-green-100 text-green-600 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                <div>
                                    <p class="font-bold text-slate-900">{{ $title }}</p>
                                    <p class="text-sm text-slate-500 mt-0.5">{{ $desc }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="relative">
                    <div class="absolute -inset-4 bg-gradient-to-br from-[#1d4ed8]/10 to-transparent rounded-3xl blur-2xl"></div>
                    <div class="relative bg-white rounded-3xl shadow-2xl p-8 border border-slate-100">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#1d4ed8] to-[#12307e] text-white flex items-center justify-center font-extrabold text-xl">A</span>
                            <div>
                                <p class="font-bold text-slate-900">Project Dashboard</p>
                                <p class="text-xs text-slate-400">Track everything live</p>
                            </div>
                        </div>
                        @foreach([['Planning', 100], ['Design', 85], ['Development', 60], ['Testing', 30]] as [$label, $pct])
                            <div class="mb-4">
                                <div class="flex justify-between text-sm mb-1.5">
                                    <span class="font-medium text-slate-700">{{ $label }}</span>
                                    <span class="text-slate-400">{{ $pct }}%</span>
                                </div>
                                <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                                    <div class="h-full rounded-full bg-gradient-to-r from-[#1d4ed8] to-cyan-400" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ PORTFOLIO ============ --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-sm font-bold text-[#1d4ed8] uppercase tracking-widest">Our work</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-2">Recent Projects</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($projects as $project)
                    <a href="{{ route('projects.show', $project->slug) }}" class="group rounded-2xl overflow-hidden border border-slate-100 hover:shadow-xl transition-all duration-300">
                        <div class="h-48 overflow-hidden bg-slate-100">
                            @if($project->getFeaturedImageUrl())
                                <img src="{{ $project->getFeaturedImageUrl() }}" alt="{{ $project->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-5">
                            <h3 class="font-bold text-slate-900 group-hover:text-[#1d4ed8] transition-colors">{{ $project->title }}</h3>
                            <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ $project->short_description }}</p>
                        </div>
                    </a>
                @empty
                    <p class="col-span-full text-center text-slate-400">Projects coming soon.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ============ TESTIMONIALS ============ --}}
    <section class="py-20 bg-gradient-to-br from-[#0a1f44] to-[#12307e] text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-5" style="background-image: radial-gradient(rgba(255,255,255,0.6) 1px, transparent 1px); background-size: 24px 24px;"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-sm font-bold text-blue-300 uppercase tracking-widest">Testimonials</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold mt-2">What Our Clients Say</h2>
            </div>
            <div class="relative px-0 sm:px-2 md:px-12">
                <button id="testimonial-prev" class="hidden sm:flex absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white/10 hover:bg-white/20 backdrop-blur p-2.5 rounded-full transition border border-white/20 items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button id="testimonial-next" class="hidden sm:flex absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white/10 hover:bg-white/20 backdrop-blur p-2.5 rounded-full transition border border-white/20 items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
                <div id="testimonial-slider" class="overflow-hidden cursor-grab active:cursor-grabbing">
                    <div id="testimonial-track" class="flex transition-transform duration-300">
                        @forelse($testimonials as $testimonial)
                            <div class="testimonial-slide w-full md:w-1/2 lg:w-1/3 flex-shrink-0 px-3">
                                <div class="bg-white/10 backdrop-blur rounded-2xl border border-white/15 h-full flex flex-col p-6">
                                    <div class="flex items-center gap-1 text-amber-400 mb-4">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <span class="{{ $i <= $testimonial->rating ? '' : 'opacity-30' }}">★</span>
                                        @endfor
                                    </div>
                                    <p class="text-blue-50/90 italic line-clamp-4 flex-1">"{{ $testimonial->content }}"</p>
                                    <div class="mt-5 pt-4 border-t border-white/15 flex items-center gap-3">
                                        @if ($testimonial->getPhotoUrl())
                                            <img src="{{ $testimonial->getPhotoUrl() }}" class="w-11 h-11 rounded-full object-cover ring-2 ring-white/20 shrink-0">
                                        @else
                                            <span class="w-11 h-11 rounded-full bg-white/20 flex items-center justify-center font-bold shrink-0">{{ strtoupper(substr($testimonial->client_name, 0, 1)) }}</span>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="font-semibold truncate">{{ $testimonial->client_name }}</p>
                                            <p class="text-xs text-blue-200/60 truncate">{{ $testimonial->client_position }}{{ $testimonial->company ? ', '.$testimonial->company : '' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-blue-200/60 w-full">No testimonials yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ BLOG ============ --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-sm font-bold text-[#1d4ed8] uppercase tracking-widest">Insights</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-2">Latest from the Blog</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($posts as $post)
                    <a href="{{ route('blog.show', $post->slug) }}" class="group bg-white rounded-2xl overflow-hidden border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <div class="h-44 overflow-hidden bg-slate-100">
                            @if ($post->getFeaturedImageUrl())
                                <img src="{{ $post->getFeaturedImageUrl() }}" alt="{{ $post->title }}" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @endif
                        </div>
                        <div class="p-5">
                            <p class="text-xs font-bold text-[#1d4ed8] uppercase tracking-wide">{{ $post->category?->name ?? 'General' }}</p>
                            <h3 class="font-bold text-slate-900 mt-1.5 group-hover:text-[#1d4ed8] transition-colors line-clamp-2">{{ $post->title }}</h3>
                            <p class="text-sm text-slate-500 mt-2 line-clamp-2">{{ $post->excerpt }}</p>
                            <p class="text-xs text-slate-400 mt-3">{{ $post->published_at?->format('M d, Y') }}</p>
                        </div>
                    </a>
                @empty
                    <p class="col-span-full text-center text-slate-400">No posts yet.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ============ CTA ============ --}}
    <section class="relative py-20 bg-gradient-to-r from-[#1d4ed8] to-[#12307e] text-white overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(rgba(255,255,255,0.5) 1px, transparent 1px); background-size: 26px 26px;"></div>
        <div class="relative max-w-4xl mx-auto text-center px-4">
            <h2 class="text-3xl sm:text-4xl font-extrabold">Ready to Start Your Project?</h2>
            <p class="mt-4 text-blue-100/80 text-lg">Let's discuss your ideas and turn them into reality.</p>
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <a href="{{ route('quote.create') }}" class="px-8 py-3.5 rounded-xl bg-white text-[#12307e] font-bold hover:bg-blue-50 transition shadow-xl">Get a Free Quote</a>
                <a href="{{ route('demo.create') }}" class="px-8 py-3.5 rounded-xl border-2 border-white/30 font-bold hover:bg-white/10 transition">Book a Free Demo</a>
                <a href="{{ url('/portfolio') }}" class="px-8 py-3.5 rounded-xl border-2 border-white/30 font-bold hover:bg-white/10 transition">See Our Work</a>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
(function () {
    const slider = document.getElementById('testimonial-slider');
    const track = document.getElementById('testimonial-track');
    if (!slider || !track) return;
    let slides = Array.from(track.querySelectorAll('.testimonial-slide'));
    if (!slides.length) return;

    // Duplicate slides for seamless infinite loop (need at least 2x perView)
    const perView = () => window.innerWidth >= 1024 ? 3 : window.innerWidth >= 768 ? 2 : 1;
    while (track.querySelectorAll('.testimonial-slide').length < perView() * 2 + 1) {
        slides.forEach(s => track.appendChild(s.cloneNode(true)));
    }
    slides = Array.from(track.querySelectorAll('.testimonial-slide'));

    let idx = 0, autoTimer = null;
    const stepPct = 100 / perView();

    const go = (i, animate = true) => {
        idx = i;
        track.style.transition = animate ? 'transform 0.5s ease' : 'none';
        track.style.transform = `translateX(-${idx * stepPct}%)`;
    };
    // Seamless wrap: when reaching the clones, jump back silently
    const normalize = () => {
        const total = slides.length;
        if (idx >= total - perView()) {
            const overflow = idx - (total - perView());
            go(overflow, false);
        }
    };
    const next = () => { go(idx + 1); setTimeout(normalize, 520); };
    const prev = () => {
        if (idx <= 0) { go(slides.length - perView(), false); idx = slides.length - perView(); }
        go(idx - 1);
    };

    document.getElementById('testimonial-prev')?.addEventListener('click', () => { prev(); restartAuto(); });
    document.getElementById('testimonial-next')?.addEventListener('click', () => { next(); restartAuto(); });

    // Auto-play every 4 seconds (pauses on hover)
    const startAuto = () => { stopAuto(); autoTimer = setInterval(next, 4000); };
    const stopAuto = () => { if (autoTimer) clearInterval(autoTimer); autoTimer = null; };
    const restartAuto = () => startAuto();
    slider.addEventListener('mouseenter', stopAuto);
    slider.addEventListener('mouseleave', startAuto);
    startAuto();

    // Drag / swipe (mouse + touch)
    let startX = 0, currentX = 0, dragging = false;
    slider.addEventListener('pointerdown', (e) => {
        dragging = true;
        startX = e.clientX; currentX = startX;
        track.style.transition = 'none';
        try { slider.setPointerCapture(e.pointerId); } catch (_) {}
        stopAuto();
    });
    slider.addEventListener('pointermove', (e) => {
        if (!dragging) return;
        currentX = e.clientX;
        const dxPct = ((currentX - startX) / slider.offsetWidth) * 100;
        track.style.transform = `translateX(${-(idx * stepPct) + dxPct}%)`;
    });
    const endDrag = () => {
        if (!dragging) return;
        dragging = false;
        const dx = currentX - startX;
        if (dx < -50) next();
        else if (dx > 50) prev();
        else go(idx);
        startAuto();
    };
    slider.addEventListener('pointerup', endDrag);
    slider.addEventListener('pointercancel', endDrag);

    window.addEventListener('resize', () => go(idx));
})();
</script>
@endpush

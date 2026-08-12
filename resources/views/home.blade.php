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
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-100 hover:shadow-md transition">
                        @if ($service->getThumbnailUrl())
                            <img src="{{ $service->getThumbnailUrl() }}" alt="{{ $service->title }}"
                                class="h-32 w-full object-cover rounded-lg mb-4">
                        @endif
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            <a href="{{ route('services.show', $service->slug) }}"
                                class="hover:text-blue-600">{{ $service->title }}</a>
                        </h3>
                        <p class="text-sm text-gray-500">{{ $service->short_description }}</p>
                    </div>
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
                    <div class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100">
                        @if ($project->getFeaturedImageUrl())
                            <img src="{{ $project->getFeaturedImageUrl() }}" alt="{{ $project->title }}"
                                class="h-48 w-full object-cover">
                        @endif
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900">
                                <a href="{{ route('projects.show', $project->slug) }}"
                                    class="hover:text-blue-600">{{ $project->title }}</a>
                            </h3>
                            <p class="text-sm text-gray-500 mt-2">{{ $project->short_description }}</p>
                        </div>
                    </div>
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

    {{-- Testimonials placeholder --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">What Our Clients Say</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @for ($i = 1; $i <= 3; $i++)
                    <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                        <p class="text-gray-600 italic">"Excellent work! Highly recommended."</p>
                        <div class="mt-4 flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-200 rounded-full"></div>
                            <div>
                                <p class="font-medium text-gray-900">Client Name {{ $i }}</p>
                                <p class="text-sm text-gray-500">CEO, Company {{ $i }}</p>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </section>

    {{-- Blog preview placeholder --}}
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">Latest from the Blog</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @for ($i = 1; $i <= 3; $i++)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="h-40 bg-gray-200"></div>
                        <div class="p-4">
                            <p class="text-xs text-blue-600 font-semibold">Category</p>
                            <h3 class="font-semibold text-gray-900 mt-1">Blog Post Title {{ $i }}</h3>
                            <p class="text-sm text-gray-500 mt-2">Brief excerpt of the blog post...</p>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </section>

    {{-- CTA --}}
 <section class="bg-white text-gray-900 py-16">
    <div class="max-w-4xl mx-auto text-center px-4">
        <h2 class="text-3xl font-bold ">Ready to Start Your Project?</h2>
        <p class="mt-4">Let's discuss your ideas and turn them into reality.</p>
        <a href="{{ url('/contact') }}" class="mt-6 inline-block px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">Contact Us</a>
    </div>
</section>
@endsection

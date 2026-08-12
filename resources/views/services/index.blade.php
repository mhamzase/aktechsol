@extends('layouts.app')

@section('title', 'Our Services')

@section('content')
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <h1 class="text-4xl font-extrabold text-center text-gray-900">Our Services</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-12">
                @forelse($services as $service)
                <div class="bg-gray-50 rounded-xl p-6 border border-gray-100 hover:shadow-md transition">
                    @if($service->getThumbnailUrl())
                        <img src="{{ $service->getThumbnailUrl() }}" alt="{{ $service->title }}" class="h-32 w-full object-cover rounded-lg mb-4">
                    @endif
                    <h3 class="text-xl font-semibold text-gray-900"><a href="{{ route('services.show', $service->slug) }}" class="hover:text-blue-600">{{ $service->title }}</a></h3>
                    <p class="text-gray-500 mt-2">{{ $service->short_description }}</p>
                </div>
                @empty
                <p class="text-center text-gray-500 col-span-full">No services found.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection

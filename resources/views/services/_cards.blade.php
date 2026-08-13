@forelse($services as $service)
    <a href="{{ route('services.show', $service->slug) }}"
       class="block bg-gray-50 rounded-xl p-6 border border-gray-100 hover:shadow-md transition group h-full">
        @if($service->getThumbnailUrl())
            <img src="{{ $service->getThumbnailUrl() }}" alt="{{ $service->title }}" loading="lazy"
                 class="h-32 w-full object-cover rounded-lg mb-4">
        @endif
        <h3 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-blue-600 transition">{{ $service->title }}</h3>
        <p class="text-sm text-gray-500">{{ $service->short_description }}</p>
    </a>
@empty
    <p class="text-center text-gray-500 col-span-full">No services found.</p>
@endforelse

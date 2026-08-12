@forelse($services as $service)
<div class="bg-gray-50 rounded-xl p-6 border border-gray-100 hover:shadow-md transition">
    @if($service->getThumbnailUrl())
        <img src="{{ $service->getThumbnailUrl() }}" alt="{{ $service->title }}" class="h-32 w-full object-cover rounded-lg mb-4">
    @endif
    <h3 class="text-xl font-semibold text-gray-900">
        <a href="{{ route('services.show', $service->slug) }}" class="hover:text-blue-600">{{ $service->title }}</a>
    </h3>
    <p class="text-gray-500 mt-2">{{ $service->short_description }}</p>
</div>
@empty
<p class="text-center text-gray-500 col-span-full">No services found.</p>
@endforelse

@forelse($projects as $project)
<div class="bg-gray-50 rounded-xl overflow-hidden border border-gray-100 hover:shadow-md transition">
    @if($project->getFeaturedImageUrl())
        <img src="{{ $project->getFeaturedImageUrl() }}" alt="{{ $project->title }}" class="h-48 w-full object-cover">
    @endif
    <div class="p-4">
        <h3 class="text-xl font-semibold text-gray-900">
            <a href="{{ route('projects.show', $project->slug) }}" class="hover:text-blue-600">{{ $project->title }}</a>
        </h3>
        @if($project->client_name)
            <p class="text-sm text-gray-400 mt-1">Client: {{ $project->client_name }}</p>
        @endif
        <p class="text-gray-500 mt-2">{{ $project->short_description }}</p>
    </div>
</div>
@empty
<p class="text-center text-gray-500 col-span-full">No projects found.</p>
@endforelse

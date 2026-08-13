@forelse($projects as $project)
    <a href="{{ route('projects.show', $project->slug) }}"
       class="block bg-white rounded-xl overflow-hidden border border-gray-100 hover:shadow-md transition group h-full">
        @if($project->getFeaturedImageUrl())
            <img src="{{ $project->getFeaturedImageUrl() }}" alt="{{ $project->title }}"
                 class="h-48 w-full object-cover">
        @endif
        <div class="p-4">
            <h3 class="font-semibold text-gray-900 group-hover:text-blue-600 transition">
                {{ $project->title }}
            </h3>
            @if($project->client_name)
                <p class="text-sm text-gray-400 mt-1">Client: {{ $project->client_name }}</p>
            @endif
            <p class="text-sm text-gray-500 mt-2">{{ $project->short_description }}</p>
        </div>
    </a>
@empty
    <p class="text-center text-gray-500 col-span-full">No projects found.</p>
@endforelse

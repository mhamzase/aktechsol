@forelse($posts as $post)
    <a href="{{ route('blog.show', $post->slug) }}"
       class="block bg-white rounded-xl overflow-hidden border border-gray-100 hover:shadow-md transition group h-full">
        @if($post->getFeaturedImageUrl())
            <img src="{{ $post->getFeaturedImageUrl() }}" alt="{{ $post->title }}"
                 class="h-48 w-full object-cover">
        @else
            <div class="h-48 bg-gray-200"></div>
        @endif
        <div class="p-5">
            <p class="text-xs text-blue-600 font-semibold">{{ $post->category?->name ?? 'General' }}</p>
            <h3 class="text-xl font-semibold text-gray-900 mt-1 group-hover:text-blue-600 transition">
                {{ $post->title }}
            </h3>
            <p class="text-sm text-gray-500 mt-2 line-clamp-3">{{ $post->excerpt }}</p>
            <div class="mt-4 text-xs text-gray-400">{{ $post->published_at?->format('M d, Y') }}</div>
        </div>
    </a>
@empty
    <p class="text-center text-gray-500 col-span-full">No blog posts found.</p>
@endforelse

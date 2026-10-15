{{-- Row partial for the blog-posts AJAX table. --}}
@forelse ($posts as $post)
    <tr>
        <td data-label="Image">
            @if ($post->getFeaturedImageUrl())
                <img src="{{ $post->getFeaturedImageUrl() }}?v={{ $post->updated_at->timestamp }}" alt="" class="h-10 w-16 object-cover rounded-lg">
            @else
                <span class="inline-flex h-10 w-16 bg-slate-100 dark:bg-slate-700 rounded-lg items-center justify-center text-slate-400 dark:text-slate-500 text-xs">No img</span>
            @endif
        </td>
        <td data-label="Title" class="font-medium text-slate-800 dark:text-slate-100">{{ $post->title }}</td>
        <td data-label="Category" class="text-slate-500 dark:text-slate-400">{{ $post->category?->name ?? '—' }}</td>
        <td data-label="Published" class="text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ $post->published_at?->format('M d, Y') ?? '—' }}</td>
        <td data-label="Status">
            <button onclick="togglePostStatus('{{ route('admin.blog-posts.toggle-status', $post) }}', this)"
                    title="Click to {{ $post->status ? 'deactivate' : 'activate' }}"
                    class="badge {{ $post->status ? 'badge-green' : 'badge-red' }} hover:opacity-80 transition-opacity cursor-pointer">
                {{ $post->status ? 'Active' : 'Inactive' }}
            </button>
        </td>
        <td data-label="Actions" class="no-label whitespace-nowrap">
            <x-admin.row-actions
                :viewUrl="route('admin.blog-posts.show', $post)"
                viewTitle="View Details"
                :editUrl="route('admin.blog-posts.edit', $post)"
                editTitle="Edit Post"
                :deleteUrl="route('admin.blog-posts.destroy', $post)"
                tableId="blog-posts-table" />
        </td>
    </tr>
@empty
    <tr><td colspan="6" class="text-center py-10 text-slate-400 text-sm">No posts found.</td></tr>
@endforelse

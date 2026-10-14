{{-- Row partial for the blog-posts AJAX table. --}}
@forelse ($posts as $post)
    <tr>
        <td data-label="Image">
            @if ($post->getFeaturedImageUrl())
                <img src="{{ $post->getFeaturedImageUrl() }}" alt="" class="h-10 w-16 object-cover rounded">
            @else
                <span class="inline-flex h-10 w-16 bg-slate-100 rounded items-center justify-center text-slate-400 text-xs">No img</span>
            @endif
        </td>
        <td data-label="Title" class="font-medium text-slate-800">{{ $post->title }}</td>
        <td data-label="Category" class="text-slate-500">{{ $post->category?->name ?? '—' }}</td>
        <td data-label="Published" class="text-slate-500 whitespace-nowrap">{{ $post->published_at?->format('M d, Y') ?? '—' }}</td>
        <td data-label="Status"><span class="badge {{ $post->status ? 'badge-green' : 'badge-red' }}">{{ $post->status ? 'Active' : 'Inactive' }}</span></td>
        <td data-label="Actions" class="no-label whitespace-nowrap">
            <button type="button" onclick="openDrawer('{{ route('admin.blog-posts.edit', $post) }}', 'Edit Post')" class="btn btn-secondary !py-1.5 !px-3 !text-xs">Edit</button>
            <button class="btn btn-danger !py-1.5 !px-3 !text-xs" onclick="confirmDelete('{{ route('admin.blog-posts.destroy', $post) }}', 'blog-posts-table', 'Delete this post?')">Delete</button>
        </td>
    </tr>
@empty
    <tr><td colspan="6" class="text-center py-10 text-slate-400 text-sm">No posts found.</td></tr>
@endforelse

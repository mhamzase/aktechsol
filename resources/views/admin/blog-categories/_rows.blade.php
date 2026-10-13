{{-- Row partial for the blog-categories AJAX table. --}}
@forelse ($categories as $category)
    <tr>
        <td data-label="Name" class="font-medium text-slate-800">{{ $category->name }}</td>
        <td data-label="Slug" class="text-slate-500">{{ $category->slug }}</td>
        <td data-label="Sort Order" class="text-slate-500">{{ $category->sort_order }}</td>
        <td data-label="Status"><span class="badge {{ $category->status ? 'badge-green' : 'badge-red' }}">{{ $category->status ? 'Active' : 'Inactive' }}</span></td>
        <td data-label="Actions" class="no-label whitespace-nowrap">
            <a href="{{ route('admin.blog-categories.edit', $category) }}" class="btn btn-secondary !py-1.5 !px-3 !text-xs">Edit</a>
            <button class="btn btn-danger !py-1.5 !px-3 !text-xs" onclick="confirmDelete('{{ route('admin.blog-categories.destroy', $category) }}', 'blog-categories-table', 'Delete this category?')">Delete</button>
        </td>
    </tr>
@empty
    <tr><td colspan="5" class="text-center py-10 text-slate-400 text-sm">No categories found.</td></tr>
@endforelse

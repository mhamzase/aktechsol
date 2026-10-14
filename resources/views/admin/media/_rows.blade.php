{{-- Row partial for the media AJAX table. --}}
@forelse ($mediaItems as $media)
    <tr>
        <td data-label="Preview">
            @if (Str::startsWith($media->mime_type, 'image/'))
                <img src="{{ $media->getUrl() }}" alt="" class="h-10 w-16 object-cover rounded">
            @else
                <span class="inline-flex h-10 w-16 bg-slate-100 rounded items-center justify-center text-slate-500 text-xs">{{ Str::upper(Str::afterLast($media->mime_type, '/')) }}</span>
            @endif
        </td>
        <td data-label="File Name" class="font-medium text-slate-800 break-all">{{ $media->file_name }}</td>
        <td data-label="Collection" class="text-slate-500">{{ $media->collection_name }}</td>
        <td data-label="Size" class="text-slate-500 whitespace-nowrap">{{ $media->human_readable_size }}</td>
        <td data-label="Uploaded" class="text-slate-500 whitespace-nowrap">{{ $media->created_at->format('M d, Y') }}</td>
        <td data-label="Actions" class="no-label whitespace-nowrap">
            <a href="{{ $media->getUrl() }}" target="_blank" rel="noopener" class="btn btn-secondary !py-1.5 !px-3 !text-xs">View</a>
            <button class="btn btn-danger !py-1.5 !px-3 !text-xs" onclick="confirmDelete('{{ route('admin.media.destroy', $media) }}', 'media-table', 'Delete this file?')">Delete</button>
        </td>
    </tr>
@empty
    <tr><td colspan="6" class="text-center py-10 text-slate-400 text-sm">No media found.</td></tr>
@endforelse

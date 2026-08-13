@extends('admin.layouts.app')

@section('title', 'Media Library')

@section('breadcrumbs')
    <x-admin.breadcrumbs :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Media Library']]" />
@endsection

@section('content')
    <x-admin.page-banner
        title="Media Library"
        subtitle="Manage all uploaded media"
        stats="{{ $mediaItems->total() }} total files"
    />

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100">
            <form method="GET" action="{{ route('admin.media.index') }}" class="flex gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search file name, collection..."
                       class="block w-full sm:w-72 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">Search</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Preview</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">File Name</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Collection</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Size</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Uploaded</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($mediaItems as $media)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-4 py-3 text-sm text-gray-500">
                            {{ ($mediaItems->currentPage() - 1) * $mediaItems->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-4 py-3">
                            @if (Str::startsWith($media->mime_type, 'image/'))
                                <img src="{{ $media->getUrl() }}" alt="{{ $media->file_name }}" class="h-10 w-16 object-cover rounded cursor-pointer">
                            @else
                                <div class="h-10 w-16 bg-gray-200 rounded flex items-center justify-center text-gray-500 text-xs">
                                    {{ Str::upper(Str::afterLast($media->mime_type, '/')) }}
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $media->file_name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $media->collection_name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $media->human_readable_size }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $media->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="relative dropdown-container">
                                <button onclick="toggleDropdown(this)"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-gray-200 focus:outline-none">
                                    <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zm0 6a2 2 0 110-4 2 2 0 010 4zm0 6a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                </button>
                                <div class="dropdown-menu hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 py-1 z-50">
                                    <a href="{{ $media->getUrl() }}" target="_blank"
                                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View
                                    </a>
                                    <form id="delete-form-{{ $media->id }}"
                                          action="{{ route('admin.media.destroy', $media) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="button"
                                                onclick="confirmAction('Delete Media', 'Are you sure you want to delete this file?', () => document.getElementById('delete-form-{{ $media->id }}').submit())"
                                                class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">No media files found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $mediaItems->links('admin.pagination') }}
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function toggleDropdown(btn) {
        const container = btn.closest('.dropdown-container');
        const menu = container.querySelector('.dropdown-menu');
        const isOpen = !menu.classList.contains('hidden');

        document.querySelectorAll('.dropdown-menu').forEach((m) => {
            m.classList.add('hidden');
            m.style.position = '';
            m.style.top = '';
            m.style.left = '';
            m.style.bottom = '';
            m.style.right = '';
        });

        if (isOpen) return;

        menu.classList.remove('hidden');

        const btnRect = btn.getBoundingClientRect();
        const menuHeight = menu.offsetHeight;
        const spaceBelow = window.innerHeight - btnRect.bottom;
        const openUpward = spaceBelow < menuHeight + 12;

        menu.style.position = 'fixed';
        menu.style.left = `${btnRect.right - menu.offsetWidth}px`;
        if (openUpward) {
            menu.style.top = `${btnRect.top - menuHeight - 4}px`;
        } else {
            menu.style.top = `${btnRect.bottom + 4}px`;
        }
    }

    document.addEventListener('click', (e) => {
        if (!e.target.closest('.dropdown-container')) {
            document.querySelectorAll('.dropdown-menu').forEach((m) => m.classList.add('hidden'));
        }
    });
</script>
@endpush

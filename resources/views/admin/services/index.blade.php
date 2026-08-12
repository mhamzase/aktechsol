@extends('admin.layouts.app')

@section('title', 'Services')

@section('breadcrumbs')
    <x-admin.breadcrumbs :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Services']]" />
@endsection

@section('content')
    <x-admin.page-banner title="Services" subtitle="Manage your services and their details"
        stats="{{ $services->total() }} total services" />
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h2 class="text-2xl font-bold text-gray-800"></h2>
        <a href="{{ route('admin.services.create') }}"
            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">
            + Add Service
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex flex-col sm:flex-row gap-3">
            <form method="GET" action="{{ route('admin.services.index') }}" class="flex-1 flex gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search all fields..."
                    class="block w-full sm:w-72 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <select name="status"
                    class="block rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">Filter</button>
            </form>
        </div>

        <div class="overflow-x-auto overflow-y-visible">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Image</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Title</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Category</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($services as $service)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-4 py-3 text-sm text-gray-500">
                                {{ ($services->currentPage() - 1) * $services->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-4 py-3">
                                @if ($service->getThumbnailUrl())
                                    <img src="{{ $service->getThumbnailUrl() }}" class="h-10 w-16 object-cover rounded">
                                @else
                                    <div
                                        class="h-10 w-16 bg-gray-200 rounded flex items-center justify-center text-gray-400 text-xs">
                                        No img</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $service->title }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $service->category?->name ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full {{ $service->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $service->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="relative dropdown-container">
                                    <button onclick="toggleDropdown(this)"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-gray-200 focus:outline-none">
                                        <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M10 6a2 2 0 110-4 2 2 0 010 4zm0 6a2 2 0 110-4 2 2 0 010 4zm0 6a2 2 0 110-4 2 2 0 010 4z" />
                                        </svg>
                                    </button>
                                    <div
                                        class="dropdown-menu hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 py-1 z-50">
                                        <a href="{{ route('admin.services.edit', $service) }}"
                                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>
                                        <form id="delete-form-{{ $service->id }}"
                                            action="{{ route('admin.services.destroy', $service) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="button"
                                                onclick="confirmAction('Delete Service', 'Are you sure you want to delete \'{{ addslashes($service->title) }}\'?', () => document.getElementById('delete-form-{{ $service->id }}').submit())"
                                                class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">No services found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $services->links('admin.pagination') }}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleDropdown(btn) {
            const container = btn.closest('.dropdown-container');
            const menu = container.querySelector('.dropdown-menu');
            const isOpen = !menu.classList.contains('hidden');

            // Close every open dropdown first, and reset any inline positioning
            document.querySelectorAll('.dropdown-menu').forEach((m) => {
                m.classList.add('hidden');
                m.style.position = '';
                m.style.top = '';
                m.style.left = '';
                m.style.bottom = '';
                m.style.right = '';
            });

            if (isOpen) return; // it was already open -> we just wanted to close it

            menu.classList.remove('hidden');

            // Reposition with fixed coords so it's never clipped by overflow-x-auto,
            // and flip upward automatically if there isn't enough room below.
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

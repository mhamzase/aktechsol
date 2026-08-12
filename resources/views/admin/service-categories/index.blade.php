@extends('admin.layouts.app')

@section('title', 'Service Categories')

@section('breadcrumbs')
    <x-admin.breadcrumbs :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Service Categories']]" />
@endsection

@section('content')
    <x-admin.page-banner title="Service Categories" subtitle="Manage categories for your services"
        stats="{{ $categories->total() }} total categories" />

    <div class="mb-6 flex justify-end">
        <a href="{{ route('admin.service-categories.create') }}"
            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">
            + Add Category
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex flex-col sm:flex-row gap-3">
            <form method="GET" action="{{ route('admin.service-categories.index') }}" class="flex-1 flex gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search categories..."
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
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Slug</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Sort Order</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($categories as $category)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-4 py-3 text-sm text-gray-500">
                                {{ ($categories->currentPage() - 1) * $categories->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $category->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $category->slug }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $category->sort_order }}</td>
                            <td class="px-4 py-3">
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full {{ $category->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $category->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <!-- existing action dropdown -->
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $categories->links('admin.pagination') }}
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

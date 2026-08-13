@extends('admin.layouts.app')

@section('title', 'FAQs')

@section('breadcrumbs')
    <x-admin.breadcrumbs :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'FAQs']]" />
@endsection

@section('content')
    <x-admin.page-banner
        title="FAQs"
        subtitle="Manage frequently asked questions"
        stats="{{ $faqs->total() }} total FAQs"
    />

    <div class="mb-6 flex justify-end">
        <a href="{{ route('admin.faqs.create') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">
            + Add FAQ
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex flex-col sm:flex-row gap-3">
            <form method="GET" action="{{ route('admin.faqs.index') }}" class="flex-1 flex gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search FAQs..."
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
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Question</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Sort Order</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($faqs as $faq)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-4 py-3 text-sm text-gray-500">
                            {{ ($faqs->currentPage() - 1) * $faqs->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $faq->question }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $faq->sort_order }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $faq->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $faq->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="relative dropdown-container">
                                <button onclick="toggleDropdown(this)"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-gray-200 focus:outline-none">
                                    <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zm0 6a2 2 0 110-4 2 2 0 010 4zm0 6a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                </button>
                                <div class="dropdown-menu hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 py-1 z-50">
                                    <a href="{{ route('admin.faqs.edit', $faq) }}"
                                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a>
                                    <form id="delete-form-{{ $faq->id }}"
                                          action="{{ route('admin.faqs.destroy', $faq) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="button"
                                                onclick="confirmAction('Delete FAQ', 'Are you sure you want to delete this FAQ?', () => document.getElementById('delete-form-{{ $faq->id }}').submit())"
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
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">No FAQs found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $faqs->links('admin.pagination') }}
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

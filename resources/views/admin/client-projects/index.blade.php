@extends('admin.layouts.app')

@section('title', 'Client Projects')

@section('content')
<div class="space-y-6">
    {{-- Page header --}}
    <x-admin.page-header title="Client Projects" subtitle="Track projects created from accepted proposals.">
        <x-slot:actions>
            <button type="button" onclick="window.openDrawer('{{ route('admin.client-projects.create') }}', 'New Project', true)"
                    class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Project
            </button>
        </x-slot:actions>
    </x-admin.page-header>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4" id="project-stats">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total</p>
            <p class="text-2xl font-extrabold text-slate-800 dark:text-slate-100 mt-1" data-stat="total">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Received</p>
            <p class="text-2xl font-extrabold text-slate-500 mt-1" data-stat="received">{{ $stats['received'] }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">In Progress</p>
            <p class="text-2xl font-extrabold text-blue-600 mt-1" data-stat="in_progress">{{ $stats['in_progress'] }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Review</p>
            <p class="text-2xl font-extrabold text-amber-600 mt-1" data-stat="review">{{ $stats['review'] }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Delivered</p>
            <p class="text-2xl font-extrabold text-green-600 mt-1" data-stat="delivered">{{ $stats['delivered'] }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-100 dark:border-slate-700">
        <form id="project-filters" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px]">
                <x-ui.input name="search" placeholder="Search projects…" :value="request('search')" />
            </div>
            <div class="w-48">
                <x-ui.select name="status" :value="request('status')"
                             :options="['' => 'All Statuses', 'received' => 'Received', 'in_progress' => 'In Progress', 'review' => 'In Review', 'revision' => 'Revision', 'delivered' => 'Delivered']" />
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.client-projects.index') }}" class="btn btn-secondary">Clear</a>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="projects-table">
                <thead>
                    <tr class="text-left text-xs uppercase tracking-wider text-slate-400 border-b border-slate-100 dark:border-slate-700">
                        <th class="px-6 py-4 font-bold">Project</th>
                        <th class="px-6 py-4 font-bold">Client</th>
                        <th class="px-6 py-4 font-bold">Assigned To</th>
                        <th class="px-6 py-4 font-bold">Status</th>
                        <th class="px-6 py-4 font-bold">Progress</th>
                        <th class="px-6 py-4 font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="projects-tbody">
                    @include('admin.client-projects._rows')
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between" id="projects-pagination">
            <p class="text-sm text-slate-500" id="projects-count">
                Showing {{ $projects->firstItem() }}–{{ $projects->lastItem() }} of {{ $projects->total() }}
            </p>
            <div id="projects-links">{{ $projects->links() }}</div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// AJAX filters + pagination (delegated).
(function() {
    const form = document.getElementById('project-filters');
    const tbody = document.getElementById('projects-tbody');

    async function load(url) {
        const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const data = await res.json();
        tbody.innerHTML = data.rows;
        document.getElementById('projects-links').innerHTML = data.links;
        document.getElementById('projects-count').textContent =
            `Showing ${data.from ?? 0}–${data.to ?? 0} of ${data.total ?? 0}`;
        // Update stats.
        if (data.stats) {
            Object.entries(data.stats).forEach(([key, val]) => {
                const el = document.querySelector(`#project-stats [data-stat="${key}"]`);
                if (el) el.textContent = val;
            });
        }
    }

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const params = new URLSearchParams(new FormData(form));
        load(`{{ route('admin.client-projects.index') }}?${params.toString()}`);
        history.replaceState(null, '', `?${params.toString()}`);
    });

    document.addEventListener('click', (e) => {
        const link = e.target.closest('#projects-links a');
        if (link) {
            e.preventDefault();
            load(link.href);
            history.replaceState(null, '', link.href);
        }
    });
})();
</script>
@endpush
@endsection

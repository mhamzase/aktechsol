@extends('admin.layouts.app')

@section('title', 'Client Projects')

@section('content')
<div class="space-y-6">
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
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6" id="project-stats">
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

    <x-admin.data-table id="projects-table"
                        :ajax-url="route('admin.client-projects.index')"
                        :columns="['Project', 'Client', 'Team', 'Status', 'Progress', 'Actions']"
                        search-placeholder="Search projects…">
        <x-slot:filters>
            <select data-table-filter="status" class="form-control table-select !w-auto !py-1.5 text-sm">
                <option value="">All Statuses</option>
                <option value="received">Received</option>
                <option value="in_progress">In Progress</option>
                <option value="review">In Review</option>
                <option value="revision">Revision</option>
                <option value="delivered">Delivered</option>
            </select>
        </x-slot:filters>
    </x-admin.data-table>
</div>

@push('scripts')
<script>
// Refresh stat cards after create/update from the drawer (no page refresh).
document.addEventListener('ajaxFormSuccess', (e) => {
    const stats = e.detail?.stats;
    if (!stats) return;
    Object.entries(stats).forEach(([key, val]) => {
        const el = document.querySelector(`#project-stats [data-stat="${key}"]`);
        if (el) el.textContent = val;
    });
});
</script>
@endpush
@endsection

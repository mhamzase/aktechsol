@extends('admin.layouts.app')

@section('title', 'Jobs')

@section('content')
    <x-admin.page-header title="Jobs" subtitle="Manage job and internship listings shown on the careers page"
                        icon="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
        <x-slot:actions>
            <button type="button" onclick="openDrawer('{{ route('admin.jobs.create') }}', 'Add Job')" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Job
            </button>
        </x-slot:actions>
    </x-admin.page-header>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6" id="job-stats">
        <div class="bg-white rounded-2xl p-5 border border-slate-100">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Jobs</p>
            <p class="text-2xl font-extrabold text-slate-800 mt-1" data-stat="total">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Open</p>
            <p class="text-2xl font-extrabold text-green-600 mt-1" data-stat="open">{{ $stats['open'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Internships</p>
            <p class="text-2xl font-extrabold text-purple-600 mt-1" data-stat="internships">{{ $stats['internships'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Applications</p>
            <p class="text-2xl font-extrabold text-blue-600 mt-1" data-stat="applications">{{ $stats['applications'] }}</p>
        </div>
    </div>

    <x-admin.data-table id="jobs-table"
                        :ajax-url="route('admin.jobs.index')"
                        :columns="['Title', 'Type', 'Location', 'Applications', 'Status', 'Actions']"
                        search-placeholder="Search jobs…">
        <x-slot:filters>
            <select data-table-filter="type" class="form-control table-select !w-auto !py-1.5 text-sm">
                <option value="">All Types</option>
                <option value="job">Job</option>
                <option value="internship">Internship</option>
            </select>
            <select data-table-filter="status" class="form-control table-select !w-auto !py-1.5 text-sm">
                <option value="">All Status</option>
                <option value="open">Open</option>
                <option value="closed">Closed</option>
            </select>
        </x-slot:filters>
    </x-admin.data-table>
@endsection

@push('scripts')
<script>
// Auto-open job view drawer when landing with ?view=id.
(function () {
    const viewId = new URLSearchParams(location.search).get('view');
    if (viewId) {
        const tryOpen = () => {
            if (window.openDrawer) {
                window.openDrawer(`/admin/jobs/${viewId}`, 'Job Details');
                history.replaceState(null, '', location.pathname);
            } else {
                setTimeout(tryOpen, 100);
            }
        };
        tryOpen();
    }
})();

// Update stats cards when a job is saved (no page refresh).
document.addEventListener('ajaxFormSuccess', function(e) {
    const stats = e.detail?.stats;
    if (!stats) return;
    Object.entries(stats).forEach(([key, val]) => {
        const el = document.querySelector(`#job-stats [data-stat="${key}"]`);
        if (el) el.textContent = val;
    });
});
</script>
@endpush

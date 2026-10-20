@extends('admin.layouts.app')

@section('title', 'Job Applications')

@section('content')
    <x-admin.page-header title="Job Applications" subtitle="Applications submitted from the careers page"
                        icon="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
    </x-admin.page-header>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6" id="application-stats">
        <div class="bg-white rounded-2xl p-5 border border-slate-100">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">New</p>
            <p class="text-2xl font-extrabold text-amber-600 mt-1" data-stat="new">{{ $stats['new'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Reviewed</p>
            <p class="text-2xl font-extrabold text-blue-600 mt-1" data-stat="reviewed">{{ $stats['reviewed'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Shortlisted</p>
            <p class="text-2xl font-extrabold text-green-600 mt-1" data-stat="shortlisted">{{ $stats['shortlisted'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total</p>
            <p class="text-2xl font-extrabold text-slate-800 mt-1" data-stat="total">{{ $stats['total'] }}</p>
        </div>
    </div>

    <x-admin.data-table id="job-applications-table"
                        :ajax-url="route('admin.job-applications.index')"
                        :columns="['Candidate', 'Job', 'Portfolio', 'Status', 'Applied', 'Actions']"
                        search-placeholder="Search applications…">
        <x-slot:filters>
            <select data-table-filter="job_id" class="form-control table-select !w-auto !py-1.5 text-sm">
                <option value="">All Jobs</option>
                @foreach($jobs as $job)
                    <option value="{{ $job->id }}">{{ $job->title }}</option>
                @endforeach
            </select>
            <select data-table-filter="status" class="form-control table-select !w-auto !py-1.5 text-sm">
                <option value="">All Statuses</option>
                <option value="new">New</option>
                <option value="reviewed">Reviewed</option>
                <option value="shortlisted">Shortlisted</option>
                <option value="rejected">Rejected</option>
            </select>
        </x-slot:filters>
    </x-admin.data-table>
@endsection

@push('scripts')
<script>
// Auto-open application drawer when landing via notification (?view=id).
(function () {
    const viewId = new URLSearchParams(location.search).get('view');
    if (viewId) {
        const tryOpen = () => {
            if (window.openDrawer) {
                window.openDrawer(`/admin/job-applications/${viewId}`, 'Application Details');
                history.replaceState(null, '', location.pathname);
            } else {
                setTimeout(tryOpen, 100);
            }
        };
        tryOpen();
    }
})();

// Update stats cards when an application status changes (no page refresh).
document.addEventListener('ajaxFormSuccess', function(e) {
    const stats = e.detail?.stats;
    if (!stats) return;
    Object.entries(stats).forEach(([key, val]) => {
        const el = document.querySelector(`#application-stats [data-stat="${key}"]`);
        if (el) el.textContent = val;
    });
});
</script>
@endpush

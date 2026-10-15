@extends('admin.layouts.app')

@section('title', 'Quote Requests')

@section('content')
    <x-admin.page-header title="Quote Requests" subtitle="Manage quote requests from the website"
                        icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
    </x-admin.page-header>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6" id="quote-stats">
        <div class="bg-white rounded-2xl p-5 border border-slate-100">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">New</p>
            <p class="text-2xl font-extrabold text-amber-600 mt-1" data-stat="new">{{ $stats['new'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Reviewed</p>
            <p class="text-2xl font-extrabold text-blue-600 mt-1" data-stat="reviewed">{{ $stats['reviewed'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Quoted</p>
            <p class="text-2xl font-extrabold text-green-600 mt-1" data-stat="quoted">{{ $stats['quoted'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total</p>
            <p class="text-2xl font-extrabold text-slate-800 mt-1" data-stat="total">{{ $stats['total'] }}</p>
        </div>
    </div>

    <x-admin.data-table id="quotes-table"
                        :ajax-url="route('admin.quotes.index')"
                        :columns="['Name', 'Service', 'Budget', 'Status', 'Received', 'Actions']"
                        search-placeholder="Search quotes…">
        <x-slot:filters>
            <select data-table-filter="status" class="form-control table-select !w-auto !py-1.5 text-sm">
                <option value="">All Statuses</option>
                <option value="new">New</option>
                <option value="reviewed">Reviewed</option>
                <option value="quoted">Quoted</option>
                <option value="rejected">Rejected</option>
            </select>
        </x-slot:filters>
    </x-admin.data-table>
@endsection

@push('scripts')
<script>
// Auto-open quote drawer when landing via notification (?view=id).
(function () {
    const viewId = new URLSearchParams(location.search).get('view');
    if (viewId && window.openDrawer) {
        const tryOpen = () => {
            if (window.openDrawer) {
                window.openDrawer(`/admin/quotes/${viewId}`, 'Quote Details');
                history.replaceState(null, '', location.pathname);
            } else {
                setTimeout(tryOpen, 100);
            }
        };
        tryOpen();
    }
})();

// Update stats cards when quote status changes (no page refresh).
document.addEventListener('ajaxFormSuccess', function(e) {
    const stats = e.detail?.stats;
    if (!stats) return;
    Object.entries(stats).forEach(([key, val]) => {
        const el = document.querySelector(`#quote-stats [data-stat="${key}"]`);
        if (el) el.textContent = val;
    });
});
</script>
@endpush

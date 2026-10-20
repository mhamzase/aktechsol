@extends('admin.layouts.app')

@section('title', 'Demo Bookings')

@section('content')
    <x-admin.page-header title="Demo Bookings" subtitle="Manage demo requests from the website"
                        icon="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
    </x-admin.page-header>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6" id="demo-stats">
        <div class="bg-white rounded-2xl p-5 border border-slate-100">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">New</p>
            <p class="text-2xl font-extrabold text-amber-600 mt-1" data-stat="new">{{ $stats['new'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Scheduled</p>
            <p class="text-2xl font-extrabold text-purple-600 mt-1" data-stat="scheduled">{{ $stats['scheduled'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Done</p>
            <p class="text-2xl font-extrabold text-green-600 mt-1" data-stat="done">{{ $stats['done'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total</p>
            <p class="text-2xl font-extrabold text-slate-800 mt-1" data-stat="total">{{ $stats['total'] }}</p>
        </div>
    </div>

    <x-admin.data-table id="demo-bookings-table"
                        :ajax-url="route('admin.demo-bookings.index')"
                        :columns="['Name', 'Service', 'Preferred Slot', 'Status', 'Received', 'Actions']"
                        search-placeholder="Search demo bookings…">
        <x-slot:filters>
            <select data-table-filter="status" class="form-control table-select !w-auto !py-1.5 text-sm">
                <option value="">All Statuses</option>
                <option value="new">New</option>
                <option value="contacted">Contacted</option>
                <option value="scheduled">Scheduled</option>
                <option value="done">Done</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </x-slot:filters>
    </x-admin.data-table>
@endsection

@push('scripts')
<script>
// Auto-open demo drawer when landing via notification (?view=id).
(function () {
    const viewId = new URLSearchParams(location.search).get('view');
    if (viewId && window.openDrawer) {
        const tryOpen = () => {
            if (window.openDrawer) {
                window.openDrawer(`/admin/demo-bookings/${viewId}`, 'Demo Booking Details');
                history.replaceState(null, '', location.pathname);
            } else {
                setTimeout(tryOpen, 100);
            }
        };
        tryOpen();
    }
})();

// Update stats cards when a demo status changes (no page refresh).
document.addEventListener('ajaxFormSuccess', function(e) {
    const stats = e.detail?.stats;
    if (!stats) return;
    Object.entries(stats).forEach(([key, val]) => {
        const el = document.querySelector(`#demo-stats [data-stat="${key}"]`);
        if (el) el.textContent = val;
    });
});
</script>
@endpush

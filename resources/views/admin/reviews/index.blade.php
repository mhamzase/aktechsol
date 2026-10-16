@extends('admin.layouts.app')

@section('title', 'Reviews')

@section('content')
<div class="space-y-6">
    <x-admin.page-header title="Reviews" subtitle="Client reviews submitted after project delivery. Moderate them here."
                        icon="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
        <x-slot:actions>
            <button type="button" onclick="window.openDrawer('{{ route('admin.reviews.request-form') }}', 'Send Review Request')"
                    class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Send Review Request
            </button>
        </x-slot:actions>
    </x-admin.page-header>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6" id="review-stats">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total</p>
            <p class="text-2xl font-extrabold text-slate-800 dark:text-slate-100 mt-1" data-stat="total">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pending</p>
            <p class="text-2xl font-extrabold text-amber-600 mt-1" data-stat="pending">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Approved</p>
            <p class="text-2xl font-extrabold text-green-600 mt-1" data-stat="approved">{{ $stats['approved'] }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Rejected</p>
            <p class="text-2xl font-extrabold text-red-600 mt-1" data-stat="rejected">{{ $stats['rejected'] }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Avg Rating</p>
            <p class="text-2xl font-extrabold text-blue-600 mt-1" data-stat="avg">{{ $stats['avg'] }} ★</p>
        </div>
    </div>

    <x-admin.data-table id="reviews-table"
                        :ajax-url="route('admin.reviews.index')"
                        :columns="['Reviewer', 'Project', 'Rating', 'Review', 'Status', 'Actions']"
                        search-placeholder="Search reviews…">
        <x-slot:filters>
            <select data-table-filter="status" class="form-control table-select !w-auto !py-1.5 text-sm">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </x-slot:filters>
    </x-admin.data-table>
</div>

@push('scripts')
<script>
// Refresh stat cards after approve/reject/delete/send-request (no page refresh).
function refreshReviewStats(stats) {
    if (!stats) return;
    Object.entries(stats).forEach(([key, val]) => {
        const el = document.querySelector(`#review-stats [data-stat="${key}"]`);
        if (el) el.textContent = key === 'avg' ? val + ' ★' : val;
    });
}
document.addEventListener('ajaxFormSuccess', (e) => refreshReviewStats(e.detail?.stats));
document.addEventListener('rowDeleted', (e) => refreshReviewStats(e.detail?.stats));
</script>
@endpush
@endsection

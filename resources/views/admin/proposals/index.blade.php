@extends('admin.layouts.app')

@section('title', 'Proposals')

@section('content')
    <x-admin.page-header title="Proposals" subtitle="Create and send proposals to clients"
                        icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
        <x-slot:actions>
            <button type="button" onclick="window.openDrawer('{{ route('admin.proposals.create') }}', 'New Proposal', true)"
                    class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Proposal
            </button>
        </x-slot:actions>
    </x-admin.page-header>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6" id="proposal-stats">
        <div class="bg-white rounded-2xl p-5 border border-slate-100">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Draft</p>
            <p class="text-2xl font-extrabold text-slate-500 mt-1" data-stat="draft">{{ $stats['draft'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Sent</p>
            <p class="text-2xl font-extrabold text-blue-600 mt-1" data-stat="sent">{{ $stats['sent'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Accepted</p>
            <p class="text-2xl font-extrabold text-green-600 mt-1" data-stat="accepted">{{ $stats['accepted'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total</p>
            <p class="text-2xl font-extrabold text-slate-800 mt-1" data-stat="total">{{ $stats['total'] }}</p>
        </div>
    </div>

    <x-admin.data-table id="proposals-table"
                        :ajax-url="route('admin.proposals.index')"
                        :columns="['Title', 'Client', 'Amount', 'Status', 'Sent', 'Actions']"
                        search-placeholder="Search proposals…">
        <x-slot:filters>
            <select data-table-filter="status" class="form-control table-select !w-auto !py-1.5 text-sm">
                <option value="">All Statuses</option>
                @foreach(\App\Models\Proposal::STATUSES as $s)
                    <option value="{{ $s }}">{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                @endforeach
            </select>
        </x-slot:filters>
    </x-admin.data-table>
@endsection

@push('scripts')
<script>
// Auto-open proposal drawer when landing via notification (?view=id).
(function () {
    const viewId = new URLSearchParams(location.search).get('view');
    if (viewId && window.openDrawer) {
        const tryOpen = () => {
            if (window.openDrawer) {
                window.openDrawer(`/admin/proposals/${viewId}`, 'Proposal Details');
                history.replaceState(null, '', location.pathname);
            } else {
                setTimeout(tryOpen, 100);
            }
        };
        tryOpen();
    }
})();

// Update stats cards on changes (no page refresh).
document.addEventListener('ajaxFormSuccess', function(e) {
    const stats = e.detail?.stats;
    if (!stats) return;
    Object.entries(stats).forEach(([key, val]) => {
        const el = document.querySelector(`#proposal-stats [data-stat="${key}"]`);
        if (el) el.textContent = val;
    });
});
</script>
@endpush

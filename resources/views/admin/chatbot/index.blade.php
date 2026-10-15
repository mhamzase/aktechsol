@extends('admin.layouts.app')

@section('title', 'Chatbot Queries')

@section('content')
    <x-admin.page-header title="Chatbot Queries" subtitle="Answer visitor questions the chatbot couldn't handle"
                        icon="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
    </x-admin.page-header>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6" id="chatbot-stats">
        <div class="bg-white rounded-2xl p-5 border border-slate-100">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Queries</p>
            <p class="text-2xl font-extrabold text-slate-800 mt-1" data-stat="total">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Answered</p>
            <p class="text-2xl font-extrabold text-green-600 mt-1" data-stat="answered">{{ $stats['answered'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Unanswered</p>
            <p class="text-2xl font-extrabold text-amber-600 mt-1" data-stat="unanswered">{{ $stats['unanswered'] }}</p>
        </div>
    </div>

    <x-admin.data-table id="chatbot-table"
                        :ajax-url="route('admin.chatbot.index')"
                        :columns="['Query', 'Status', 'Asked', 'Actions']"
                        search-placeholder="Search queries…">
        <x-slot:filters>
            <select data-table-filter="filter" class="form-control table-select !w-auto !py-1.5 text-sm">
                <option value="">All Queries</option>
                <option value="unanswered">Unanswered Only</option>
            </select>
        </x-slot:filters>
    </x-admin.data-table>
@endsection

@push('scripts')
<script>
// Auto-open query drawer when landing via notification (?view=id).
(function () {
    const viewId = new URLSearchParams(location.search).get('view');
    if (viewId && window.openDrawer) {
        const tryOpen = () => {
            if (window.openDrawer) {
                window.openDrawer(`/admin/chatbot/${viewId}`, 'Query Details');
                history.replaceState(null, '', location.pathname);
            } else {
                setTimeout(tryOpen, 100);
            }
        };
        tryOpen();
    }
})();

// Update stats cards when an answer is saved (no page refresh).
document.addEventListener('ajaxFormSuccess', function(e) {
    const stats = e.detail?.stats;
    if (!stats) return;
    Object.entries(stats).forEach(([key, val]) => {
        const el = document.querySelector(`#chatbot-stats [data-stat="${key}"]`);
        if (el) el.textContent = val;
    });
});
</script>
@endpush

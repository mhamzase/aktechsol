@extends('admin.layouts.app')

@section('title', 'Invoices')

@section('content')
<div class="space-y-6">
    <x-admin.page-header title="Invoices" subtitle="Project and milestone invoices. Clients pay by uploading a screenshot proof.">
        <x-slot:actions>
            <button type="button" onclick="window.openDrawer('{{ route('admin.invoices.create') }}', 'New Invoice', true)"
                    class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Invoice
            </button>
        </x-slot:actions>
    </x-admin.page-header>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6" id="invoice-stats">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total</p>
            <p class="text-2xl font-extrabold text-slate-800 dark:text-slate-100 mt-1" data-stat="total">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Unpaid</p>
            <p class="text-2xl font-extrabold text-red-600 mt-1" data-stat="unpaid">{{ $stats['unpaid'] }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Partially Paid</p>
            <p class="text-2xl font-extrabold text-amber-600 mt-1" data-stat="partially_paid">{{ $stats['partially_paid'] }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Paid</p>
            <p class="text-2xl font-extrabold text-green-600 mt-1" data-stat="paid">{{ $stats['paid'] }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Overdue</p>
            <p class="text-2xl font-extrabold text-orange-600 mt-1" data-stat="overdue">{{ $stats['overdue'] }}</p>
        </div>
    </div>

    <x-admin.data-table id="invoices-table"
                        :ajax-url="route('admin.invoices.index')"
                        :columns="['Invoice', 'Title', 'Client', 'Amount', 'Paid', 'Due', 'Status', 'Actions']"
                        search-placeholder="Search invoices…">
        <x-slot:filters>
            <select data-table-filter="status" class="form-control table-select !w-auto !py-1.5 text-sm">
                <option value="">All Statuses</option>
                <option value="unpaid">Unpaid</option>
                <option value="partially_paid">Partially Paid</option>
                <option value="paid">Paid</option>
                <option value="overdue">Overdue</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </x-slot:filters>
    </x-admin.data-table>
</div>

@push('scripts')
<script>
// Refresh stat cards after create/update/delete from the drawer (no page refresh).
document.addEventListener('ajaxFormSuccess', (e) => {
    const stats = e.detail?.stats;
    if (!stats) return;
    Object.entries(stats).forEach(([key, val]) => {
        const el = document.querySelector(`#invoice-stats [data-stat="${key}"]`);
        if (el) el.textContent = val;
    });
});

// Row action: send invoice email + mark as paid (delegated, drawer-safe).
document.addEventListener('click', async (e) => {
    const emailBtn = e.target.closest('[data-send-invoice-email]');
    if (emailBtn) {
        window.confirmAction({
            title: 'Email Invoice?',
            message: 'The invoice will be emailed to the client.',
            confirmLabel: 'Send Email',
            onConfirm: async () => {
                emailBtn.disabled = true;
                try {
                    const res = await fetch(emailBtn.dataset.url, {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': window.csrfToken, 'Accept': 'application/json' }
                    });
                    const data = await res.json().catch(() => ({}));
                    window.notify[res.ok ? 'success' : 'error'](data.message || (res.ok ? 'Sent!' : 'Failed.'));
                } catch (err) {
                    window.notify.error('Network error.');
                } finally {
                    emailBtn.disabled = false;
                }
            },
        });
        return;
    }

    const paidBtn = e.target.closest('[data-mark-invoice-paid]');
    if (paidBtn) {
        window.confirmAction({
            title: 'Mark as Paid?',
            message: 'This will mark the full invoice amount as paid.',
            confirmLabel: 'Mark Paid',
            onConfirm: async () => {
                try {
                    const res = await fetch(paidBtn.dataset.url, {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': window.csrfToken, 'Accept': 'application/json' }
                    });
                    const data = await res.json().catch(() => ({}));
                    if (res.ok) {
                        window.notify.success(data.message || 'Done!');
                        window.reloadAdminTable('invoices-table');
                        if (data.stats) {
                            Object.entries(data.stats).forEach(([key, val]) => {
                                const el = document.querySelector(`#invoice-stats [data-stat="${key}"]`);
                                if (el) el.textContent = val;
                            });
                        }
                    } else {
                        window.notify.error(data.message || 'Failed.');
                    }
                } catch (err) {
                    window.notify.error('Network error.');
                }
            },
        });
    }
});

// Auto-open invoice drawer when landing via email / notification (?view=id).
(function () {
    const viewId = new URLSearchParams(location.search).get('view');
    if (viewId && window.openDrawer) {
        const tryOpen = () => {
            if (window.openDrawer) {
                window.openDrawer(`/admin/invoices/${viewId}`, 'Invoice Details');
                history.replaceState(null, '', location.pathname);
            } else {
                setTimeout(tryOpen, 100);
            }
        };
        tryOpen();
    }
})();
</script>
@endpush
@endsection

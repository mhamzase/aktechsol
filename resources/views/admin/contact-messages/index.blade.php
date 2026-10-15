@extends('admin.layouts.app')

@section('title', 'Contact Messages')

@section('content')
    <x-admin.page-header title="Contact Messages" subtitle="Manage messages from the contact form"
                        icon="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
    </x-admin.page-header>

    <x-admin.data-table id="contact-messages-table"
                        :ajax-url="route('admin.contact-messages.index')"
                        :columns="['Name', 'Email', 'Subject', 'Status', 'Received', 'Actions']"
                        search-placeholder="Search messages…">
        <x-slot:filters>
            <select data-table-filter="status" class="form-control table-select !w-auto !py-1.5 text-sm">
                <option value="">All Statuses</option>
                <option value="new">New</option>
                <option value="read">Read</option>
                <option value="replied">Replied</option>
            </select>
        </x-slot:filters>
    </x-admin.data-table>
@endsection

@push('scripts')
<script>
// Auto-open message drawer when landing via notification (?view=id).
(function () {
    const viewId = new URLSearchParams(location.search).get('view');
    if (viewId && window.openDrawer) {
        // Wait for drawer system to init.
        const tryOpen = () => {
            if (window.openDrawer) {
                window.openDrawer(`/admin/contact-messages/${viewId}`, 'Message Details');
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

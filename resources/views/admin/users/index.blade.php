@extends('admin.layouts.app')

@section('title', 'Users & Roles')

@section('content')
    <x-admin.page-header title="Users & Roles" subtitle="Manage team members and their roles" />

    <x-admin.data-table id="users-table"
        :ajax-url="route('admin.users.index')"
        :columns="['User', 'Email', 'Phone', 'Roles', 'Joined', 'Actions']"
        create-url="{{ route('admin.users.create') }}"
        create-title="Add User"
        search-placeholder="Search name or email…" />
@endsection

@push('scripts')
<script>
// Wire row action buttons (delegated, works after AJAX reloads)
document.getElementById('users-table').addEventListener('click', (e) => {
    const btn = e.target.closest('[data-action]');
    if (!btn) return;
    const id = btn.dataset.id;
    if (btn.dataset.action === 'edit') openDrawer(`{{ url('admin/users') }}/${id}/edit`, 'Edit User');
    if (btn.dataset.action === 'delete') confirmDelete(`{{ url('admin/users') }}/${id}`, 'users-table');
});
</script>
@endpush

@extends('admin.layouts.app')

@section('title', 'Users & Roles')

@section('content')
    <x-admin.page-header title="Users & Roles" subtitle="Manage team members and their roles">
        <x-slot:actions>
            <button class="btn btn-primary" onclick="openDrawer('{{ route('admin.users.create') }}', 'Add User')">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add New
            </button>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.data-table id="users-table"
        :ajax-url="route('admin.users.index')"
        :columns="['User', 'Email', 'Phone', 'Roles', 'Joined', 'Actions']"
        search-placeholder="Search name or email…" />
@endsection

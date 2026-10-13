@extends('admin.layouts.app')

@section('title', 'Projects')

@section('content')
    <x-admin.page-header title="Projects" subtitle="Manage your portfolio projects"
                        icon="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
        <x-slot:actions>
            <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Project
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.data-table id="projects-table"
                        :ajax-url="route('admin.projects.index')"
                        :columns="['Image', 'Title', 'Client', 'Sort Order', 'Status', 'Actions']"
                        search-placeholder="Search projects…">
        <x-slot:filters>
            <select data-table-filter="status" class="form-control !w-auto !py-1.5 text-sm">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </x-slot:filters>
    </x-admin.data-table>
@endsection

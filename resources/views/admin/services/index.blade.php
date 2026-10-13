@extends('admin.layouts.app')

@section('title', 'Services')

@section('content')
    <x-admin.page-header title="Services" subtitle="Manage your services and their details"
                        icon="M13 10V3L4 14h7v7l9-11h-7z">
        <x-slot:actions>
            <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Service
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.data-table id="services-table"
                        :ajax-url="route('admin.services.index')"
                        :columns="['Image', 'Title', 'Category', 'Sort Order', 'Status', 'Actions']"
                        search-placeholder="Search services…">
        <x-slot:filters>
            <select data-table-filter="status" class="form-control !w-auto !py-1.5 text-sm">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </x-slot:filters>
    </x-admin.data-table>
@endsection

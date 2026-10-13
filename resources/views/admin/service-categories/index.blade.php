@extends('admin.layouts.app')

@section('title', 'Service Categories')

@section('content')
    <x-admin.page-header title="Service Categories" subtitle="Manage categories for your services"
                        icon="M7 7h.01M7 3h5.586a1 1 0 01.707.293l7.414 7.414a1 1 0 010 1.414l-5.414 5.414a1 1 0 01-1.414 0L5.879 10.12a1 1 0 01-.293-.707V7a2 2 0 012-2z">
        <x-slot:actions>
            <a href="{{ route('admin.service-categories.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Category
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.data-table id="service-categories-table"
                        :ajax-url="route('admin.service-categories.index')"
                        :columns="['Name', 'Slug', 'Sort Order', 'Status', 'Actions']"
                        search-placeholder="Search categories…">
        <x-slot:filters>
            <select data-table-filter="status" class="form-control !w-auto !py-1.5 text-sm">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </x-slot:filters>
    </x-admin.data-table>
@endsection

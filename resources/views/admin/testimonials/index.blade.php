@extends('admin.layouts.app')

@section('title', 'Testimonials')

@section('content')
    <x-admin.page-header title="Testimonials" subtitle="Manage client feedback"
                        icon="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
        <x-slot:actions>
            <button type="button" onclick="openDrawer('{{ route('admin.testimonials.create') }}', 'Add Testimonial')" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Testimonial
            </button>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.data-table id="testimonials-table"
                        :ajax-url="route('admin.testimonials.index')"
                        :columns="['Photo', 'Client', 'Company', 'Rating', 'Status', 'Actions']"
                        search-placeholder="Search testimonials…">
        <x-slot:filters>
            <select data-table-filter="status" class="form-control !w-auto !py-1.5 text-sm">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </x-slot:filters>
    </x-admin.data-table>
@endsection

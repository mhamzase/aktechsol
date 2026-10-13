@extends('admin.layouts.app')

@section('title', 'FAQs')

@section('content')
    <x-admin.page-header title="FAQs" subtitle="Manage frequently asked questions"
                        icon="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
        <x-slot:actions>
            <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add FAQ
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.data-table id="faqs-table"
                        :ajax-url="route('admin.faqs.index')"
                        :columns="['Question', 'Sort Order', 'Status', 'Actions']"
                        search-placeholder="Search FAQs…">
        <x-slot:filters>
            <select data-table-filter="status" class="form-control !w-auto !py-1.5 text-sm">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </x-slot:filters>
    </x-admin.data-table>
@endsection

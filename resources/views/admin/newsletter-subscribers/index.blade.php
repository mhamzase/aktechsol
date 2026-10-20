@extends('admin.layouts.app')

@section('title', 'Newsletter Subscribers')

@section('content')
    <x-admin.page-header title="Newsletter Subscribers" subtitle="Manage newsletter subscriptions"
                        icon="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
        <x-slot:actions>
            <button type="button" onclick="openDrawer('{{ route('admin.newsletter-subscribers.compose') }}', 'Write Email')" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Write Email
            </button>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.data-table id="newsletter-table"
                        :ajax-url="route('admin.newsletter-subscribers.index')"
                        :columns="['Email', 'Status', 'Subscribed At', 'Actions']"
                        search-placeholder="Search subscribers…">
        <x-slot:filters>
            <select data-table-filter="status" class="form-control table-select !w-auto !py-1.5 text-sm">
                <option value="">All Statuses</option>
                <option value="subscribed">Subscribed</option>
                <option value="unsubscribed">Unsubscribed</option>
            </select>
        </x-slot:filters>
    </x-admin.data-table>
@endsection

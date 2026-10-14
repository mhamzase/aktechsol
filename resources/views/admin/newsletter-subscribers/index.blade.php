@extends('admin.layouts.app')

@section('title', 'Newsletter Subscribers')

@section('content')
    <x-admin.page-header title="Newsletter Subscribers" subtitle="Manage newsletter subscriptions"
                        icon="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
    </x-admin.page-header>

    <x-admin.data-table id="newsletter-table"
                        :ajax-url="route('admin.newsletter-subscribers.index')"
                        :columns="['Email', 'Status', 'Subscribed At', 'Actions']"
                        search-placeholder="Search subscribers…">
        <x-slot:filters>
            <select data-table-filter="status" class="form-control !w-auto !py-1.5 text-sm">
                <option value="">All Statuses</option>
                <option value="subscribed">Subscribed</option>
                <option value="unsubscribed">Unsubscribed</option>
            </select>
        </x-slot:filters>
    </x-admin.data-table>
@endsection

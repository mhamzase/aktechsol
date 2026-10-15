@extends('admin.layouts.app')

@section('title', 'Activity Log')

@section('content')
    <x-admin.page-header title="Activity Log" subtitle="Audit trail of admin actions" />

    <x-admin.data-table id="activity-table"
        :ajax-url="route('admin.activity-log.index')"
        :columns="['When', 'User', 'Action', 'Details', 'IP', 'Actions']"
        :per-page="15"
        search-placeholder="Search action or details…" />
@endsection

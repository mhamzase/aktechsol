@extends('admin.layouts.app')

@section('title', 'Pages')

@section('content')
    <x-admin.page-header title="Pages" subtitle="Legal & policy pages (rich text)" />

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach ($pages as $page)
            <div class="admin-card p-5">
                <h3 class="font-bold text-[#0f2a5c] mb-1">{{ $page['title'] }}</h3>
                <p class="text-xs mb-4">
                    {!! $page['has_content']
                        ? '<span class="badge badge-green">Content set</span>'
                        : '<span class="badge badge-amber">Empty</span>' !!}
                </p>
                <button class="btn btn-secondary w-full"
                        onclick="openDrawer('{{ route('admin.pages.edit', $page['key']) }}', 'Edit {{ $page['title'] }}')">
                    Edit Page
                </button>
            </div>
        @endforeach
    </div>
@endsection

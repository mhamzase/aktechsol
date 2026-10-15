@extends('admin.layouts.app')

@section('title', 'Blog Posts')

@section('content')
    @include('admin.blog-posts._tabs', ['blogTab' => 'posts'])

    <x-admin.page-header title="Blog Posts" subtitle="Manage your blog content"
                        icon="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
        <x-slot:actions>
            <button type="button" onclick="openDrawer('{{ route('admin.blog-posts.create') }}', 'Add Post')" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Post
            </button>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.data-table id="blog-posts-table"
                        :ajax-url="route('admin.blog-posts.index')"
                        :columns="['Image', 'Title', 'Category', 'Published', 'Status', 'Actions']"
                        search-placeholder="Search posts…">
        <x-slot:filters>
            <select data-table-filter="status" class="form-control table-select !w-auto !py-1.5 text-sm">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </x-slot:filters>
    </x-admin.data-table>
@endsection

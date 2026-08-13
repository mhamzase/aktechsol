@extends('admin.layouts.app')

@section('title', 'View Message')

@section('breadcrumbs')
    <x-admin.breadcrumbs :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Contact Messages', 'url' => route('admin.contact-messages.index')], ['label' => 'View']]" />
@endsection

@section('content')
    <x-admin.page-banner title="Message Details" subtitle="View contact message" />

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-semibold text-gray-500">Name</p>
                    <p class="text-gray-900">{{ $contactMessage->name }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-500">Email</p>
                    <p class="text-gray-900">{{ $contactMessage->email }}</p>
                </div>
                @if($contactMessage->phone)
                <div>
                    <p class="text-sm font-semibold text-gray-500">Phone</p>
                    <p class="text-gray-900">{{ $contactMessage->phone }}</p>
                </div>
                @endif
                <div>
                    <p class="text-sm font-semibold text-gray-500">Received</p>
                    <p class="text-gray-900">{{ $contactMessage->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-sm font-semibold text-gray-500">Subject</p>
                <p class="text-gray-900 font-medium">{{ $contactMessage->subject }}</p>
            </div>
            <div class="mt-4">
                <p class="text-sm font-semibold text-gray-500">Message</p>
                <div class="text-gray-700 leading-relaxed">{{ $contactMessage->message }}</div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Update Status</h3>
            <form action="{{ route('admin.contact-messages.update-status', $contactMessage) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="flex items-center gap-3">
                    <select name="status" class="block rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="new" {{ $contactMessage->status === 'new' ? 'selected' : '' }}>New</option>
                        <option value="read" {{ $contactMessage->status === 'read' ? 'selected' : '' }}>Read</option>
                        <option value="replied" {{ $contactMessage->status === 'replied' ? 'selected' : '' }}>Replied</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@extends('admin.layouts.app')

@section('title', 'Notifications')

@section('content')
    <x-admin.page-header title="Notifications" subtitle="Platform events that need your attention">
        <x-slot name="actions">
            <form method="POST" action="{{ route('admin.notifications.read-all') }}">
                @csrf
                <button class="btn btn-secondary">Mark all as read</button>
            </form>
        </x-slot>
    </x-admin.page-header>

    <div class="admin-card overflow-hidden">
        @forelse ($notifications as $note)
            <div class="flex items-start gap-3 px-5 py-4 border-b border-slate-100 last:border-0 {{ $note->read_at ? 'opacity-60' : '' }}">
                <span class="w-2.5 h-2.5 rounded-full mt-1.5 shrink-0 {{ $note->read_at ? 'bg-slate-300' : 'bg-[#1d4ed8]' }}"></span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-slate-700">{{ $note->data['message'] ?? $note->type }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $note->created_at->diffForHumans() }} · {{ $note->created_at->format('d M Y, h:i A') }}</p>
                </div>
                @unless ($note->read_at)
                    <form method="POST" action="{{ route('admin.notifications.read', $note->id) }}">
                        @csrf
                        <button class="text-xs font-semibold text-[#1d4ed8] hover:underline shrink-0">Mark read</button>
                    </form>
                @endunless
            </div>
        @empty
            <p class="px-5 py-12 text-center text-slate-400 text-sm">No notifications yet. You're all caught up.</p>
        @endforelse
    </div>

    <div class="mt-4 flex justify-center">
        {{ $notifications->links() }}
    </div>
@endsection

@extends('admin.layouts.app')

@section('title', 'Notifications')

@section('content')
    <x-admin.page-header title="Notifications" subtitle="Platform events that need your attention">
        <x-slot name="actions">
            <button class="btn btn-primary" onclick="confirmMarkAllRead()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Mark all as read
            </button>
        </x-slot>
    </x-admin.page-header>

    <div class="admin-card overflow-hidden" id="notifications-list">
        @forelse ($notifications as $note)
            <div class="notif-row flex items-start gap-3 px-5 py-4 border-b border-slate-100 dark:border-slate-700 last:border-0 transition-colors {{ $note->read_at ? 'opacity-60' : 'bg-blue-50/40 dark:bg-blue-500/5' }}"
                 data-id="{{ $note->id }}">
                <span class="notif-dot w-2.5 h-2.5 rounded-full mt-1.5 shrink-0 {{ $note->read_at ? 'bg-slate-300 dark:bg-slate-600' : 'bg-[#1d4ed8]' }}"></span>
                <div class="flex-1 min-w-0">
                    @if ($note->data['url'] ?? null)
                        <a href="{{ $note->data['url'] }}" class="block group">
                            <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 group-hover:text-[#1d4ed8] dark:group-hover:text-blue-400">{{ $note->data['title'] ?? 'Notification' }}</p>
                            <p class="text-sm text-slate-600 dark:text-slate-300 mt-0.5">{{ $note->data['message'] ?? '' }}</p>
                        </a>
                    @else
                        <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $note->data['title'] ?? 'Notification' }}</p>
                        <p class="text-sm text-slate-600 dark:text-slate-300 mt-0.5">{{ $note->data['message'] ?? '' }}</p>
                    @endif
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">{{ $note->created_at->diffForHumans() }} · {{ $note->created_at->format('d M Y, h:i A') }}</p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    @unless ($note->read_at)
                        <button class="notif-read-btn text-xs font-semibold text-[#1d4ed8] dark:text-blue-400 hover:underline"
                                onclick="confirmMarkRead('{{ $note->id }}')">Mark read</button>
                    @endunless
                    <button class="text-xs font-semibold text-red-500 hover:text-red-700 dark:hover:text-red-400"
                            onclick="confirmDeleteNotification('{{ $note->id }}')" title="Delete">Delete</button>
                </div>
            </div>
        @empty
            <div class="px-5 py-16 text-center">
                <svg class="w-12 h-12 mx-auto text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                <p class="mt-3 text-sm text-slate-500 dark:text-slate-400 font-medium">No notifications yet</p>
                <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">You're all caught up.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4 flex justify-center">
        {{ $notifications->links() }}
    </div>

    {{-- Generic confirm modal --}}
    <div id="notif-confirm-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" onclick="closeNotifConfirm()"></div>
        <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-xl max-w-sm w-full p-6">
            <h3 class="font-bold text-slate-800 dark:text-slate-100" id="notif-confirm-title">Are you sure?</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2" id="notif-confirm-message"></p>
            <div class="flex justify-end gap-2 mt-5">
                <button class="btn btn-secondary" onclick="closeNotifConfirm()">Cancel</button>
                <button class="btn btn-primary" id="notif-confirm-btn" onclick="doNotifConfirm()">Confirm</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
let notifConfirmAction = null;

function openNotifConfirm(title, message, action) {
    document.getElementById('notif-confirm-title').textContent = title;
    document.getElementById('notif-confirm-message').textContent = message;
    notifConfirmAction = action;
    document.getElementById('notif-confirm-modal').classList.remove('hidden');
}
function closeNotifConfirm() {
    document.getElementById('notif-confirm-modal').classList.add('hidden');
    notifConfirmAction = null;
}
function doNotifConfirm() {
    const action = notifConfirmAction;
    closeNotifConfirm();
    if (action) action();
}
const notifCsrf = document.querySelector('meta[name="csrf-token"]').content;

function confirmMarkRead(id) {
    openNotifConfirm('Mark as read?', 'This notification will be marked as read.', () => {
        fetch(`/admin/notifications/${id}/read`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': notifCsrf, 'Accept': 'application/json' },
        }).then(() => {
            const row = document.querySelector(`.notif-row[data-id="${id}"]`);
            row?.classList.remove('bg-blue-50/40', 'dark:bg-blue-500/5');
            row?.classList.add('opacity-60');
            row?.querySelector('.notif-dot')?.classList.replace('bg-[#1d4ed8]', 'bg-slate-300');
            row?.querySelector('.notif-read-btn')?.remove();
            updateBellCount(-1);
            window.notify?.success('Marked as read.');
        });
    });
}

function confirmMarkAllRead() {
    openNotifConfirm('Mark all as read?', 'All notifications will be marked as read.', () => {
        fetch(`{{ route('admin.notifications.read-all') }}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': notifCsrf, 'Accept': 'application/json' },
        }).then(() => {
            document.querySelectorAll('.notif-row').forEach((row) => {
                row.classList.remove('bg-blue-50/40', 'dark:bg-blue-500/5');
                row.classList.add('opacity-60');
                row.querySelector('.notif-dot')?.classList.replace('bg-[#1d4ed8]', 'bg-slate-300');
                row.querySelector('.notif-read-btn')?.remove();
            });
            updateBellCount(0, true);
            window.notify?.success('All notifications marked as read.');
        });
    });
}

function confirmDeleteNotification(id) {
    openNotifConfirm('Delete notification?', 'This cannot be undone.', () => {
        fetch(`/admin/notifications/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': notifCsrf, 'Accept': 'application/json' },
        }).then((res) => res.json()).then((data) => {
            document.querySelector(`.notif-row[data-id="${id}"]`)?.remove();
            if (data.was_unread) updateBellCount(-1);
            window.notify?.success(data.message || 'Deleted.');
        });
    });
}

function updateBellCount(delta, absolute = false) {
    const badge = document.querySelector('.bell-dot');
    if (!badge && !absolute) return;
    let count = absolute ? delta : Math.max(0, (parseInt(badge?.textContent) || 0) + delta);
    if (count <= 0) { badge?.remove(); return; }
    if (badge) badge.textContent = count > 9 ? '9+' : count;
}
</script>
@endpush

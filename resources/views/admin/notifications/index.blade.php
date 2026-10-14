@extends('admin.layouts.app')

@section('title', 'Notifications')

@section('content')
    <x-admin.page-header title="Notifications" subtitle="Platform events that need your attention">
        <x-slot name="actions">
            <div class="flex gap-2">
                <button class="btn btn-secondary !text-red-600 dark:!text-red-400" onclick="confirmDeleteAll()">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Delete all
                </button>
                <button class="btn btn-primary" onclick="confirmMarkAllRead()">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Mark all as read
                </button>
            </div>
        </x-slot>
    </x-admin.page-header>

    <div class="admin-card overflow-hidden">
        <div id="notifications-list">
            @if ($notifications->count())
                @include('admin.notifications._rows')
            @else
                <div class="px-5 py-16 text-center" id="notif-empty">
                    <svg class="w-12 h-12 mx-auto text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <p class="mt-3 text-sm text-slate-500 dark:text-slate-400 font-medium">No notifications yet</p>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">You're all caught up.</p>
                </div>
            @endif
        </div>
        @if ($notifications->hasMorePages())
            <div class="px-5 py-4 text-center border-t border-slate-100 dark:border-slate-700" id="load-more-wrap">
                <button class="btn btn-secondary" id="load-more-btn" onclick="loadMoreNotifications(this)" data-next="{{ $notifications->currentPage() + 1 }}">
                    Load more
                </button>
            </div>
        @endif
    </div>

    {{-- Generic confirm modal --}}
    <div id="notif-confirm-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" onclick="closeNotifConfirm()"></div>
        <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-xl max-w-sm w-full p-6">
            <h3 class="font-bold text-slate-800 dark:text-slate-100" id="notif-confirm-title">Are you sure?</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2" id="notif-confirm-message"></p>
            <div id="notif-confirm-input-wrap" class="hidden mt-3">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-300">Type <span class="font-mono font-bold text-red-600">DELETE</span> to confirm</label>
                <input type="text" id="notif-confirm-input" class="ui-control mt-1" placeholder="DELETE" autocomplete="off">
            </div>
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
let notifConfirmNeedsTyping = false;

function openNotifConfirm(title, message, action, needsTyping = false) {
    document.getElementById('notif-confirm-title').textContent = title;
    document.getElementById('notif-confirm-message').textContent = message;
    notifConfirmAction = action;
    notifConfirmNeedsTyping = needsTyping;
    const wrap = document.getElementById('notif-confirm-input-wrap');
    const input = document.getElementById('notif-confirm-input');
    if (needsTyping) {
        wrap.classList.remove('hidden');
        input.value = '';
        setTimeout(() => input.focus(), 100);
    } else {
        wrap.classList.add('hidden');
    }
    document.getElementById('notif-confirm-modal').classList.remove('hidden');
}
function closeNotifConfirm() {
    document.getElementById('notif-confirm-modal').classList.add('hidden');
    notifConfirmAction = null;
    notifConfirmNeedsTyping = false;
}
function doNotifConfirm() {
    if (notifConfirmNeedsTyping) {
        const val = document.getElementById('notif-confirm-input').value.trim();
        if (val !== 'DELETE') {
            window.notify?.error('Please type DELETE to confirm.');
            return;
        }
    }
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
            row?.classList.remove('bg-blue-50/70', 'dark:bg-blue-500/10');
            row?.classList.add('bg-white', 'dark:bg-slate-800');
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
                row.classList.remove('bg-blue-50/70', 'dark:bg-blue-500/10');
                row.classList.add('bg-white', 'dark:bg-slate-800');
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

function loadMoreNotifications(btn) {
    const next = btn.dataset.next;
    btn.disabled = true;
    btn.textContent = 'Loading…';
    fetch(`{{ route('admin.notifications.index') }}?page=${next}`, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    })
        .then((res) => res.json())
        .then((data) => {
            document.getElementById('notifications-list').insertAdjacentHTML('beforeend', data.html);
            if (data.next_page) {
                btn.dataset.next = data.next_page;
                btn.disabled = false;
                btn.textContent = 'Load more';
            } else {
                document.getElementById('load-more-wrap')?.remove();
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.textContent = 'Load more';
        });
}

function confirmDeleteAll() {
    // Single popup: confirmation message + type DELETE.
    openNotifConfirm(
        'Delete all notifications?',
        'This will permanently delete every notification. This cannot be undone. To confirm, type DELETE below.',
        () => {
            fetch(`{{ route('admin.notifications.destroy-all') }}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': notifCsrf, 'Accept': 'application/json' },
            }).then(() => {
                document.getElementById('notifications-list').innerHTML = `
                    <div class="px-5 py-16 text-center">
                        <p class="mt-3 text-sm text-slate-500 dark:text-slate-400 font-medium">No notifications yet</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">You're all caught up.</p>
                    </div>`;
                document.getElementById('load-more-wrap')?.remove();
                updateBellCount(0, true);
                window.notify?.success('All notifications deleted.');
            });
        },
        true // needsTyping
    );
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

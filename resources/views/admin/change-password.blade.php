{{--
    Admin change password — AJAX with inline validation (x-ui kit).
--}}
@extends('admin.layouts.app')

@section('title', 'Change Password')

@section('content')
    <x-admin.page-header title="Change Password" subtitle="Keep your account secure"
                        icon="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />

    <div class="max-w-2xl mx-auto">
        <form id="password-form" action="{{ route('admin.change-password.update') }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            <div class="admin-card p-6 space-y-5">
                <h3 class="text-lg font-bold text-[#0f2a5c]">Update Password</h3>

                <x-ui.password name="current_password" label="Current Password" required autocomplete="current-password" />
                <x-ui.password name="new_password" label="New Password" required hint="Minimum 8 characters." />
                <x-ui.password name="new_password_confirmation" label="Confirm New Password" required />
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit" id="password-save-btn" class="btn btn-primary px-6 py-2.5">
                    Update Password
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';
    const form = document.getElementById('password-form');
    if (!form) return;
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

    function showErrors(errors) {
        Object.entries(errors).forEach(([field, msgs]) => {
            const input = form.querySelector(`[name="${field}"]`);
            if (!input) return;
            input.classList.add('ui-control-error');
            const err = document.createElement('p');
            err.className = 'ui-error';
            err.textContent = msgs[0];
            input.closest('.ui-input-wrap')?.after(err);
        });
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        e.stopPropagation();
        form.querySelectorAll('.ui-error').forEach((el) => el.remove());
        form.querySelectorAll('.ui-control-error').forEach((el) => el.classList.remove('ui-control-error'));

        // Client-side: new password + confirmation must match before hitting the server.
        const np = form.querySelector('[name="new_password"]').value;
        const cnp = form.querySelector('[name="new_password_confirmation"]').value;
        if (np !== cnp) {
            showErrors({ new_password_confirmation: ['Passwords do not match.'] });
            window.notify?.error('Passwords do not match.');
            return;
        }

        const btn = document.getElementById('password-save-btn');
        const original = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spin inline-block">◌</span> Updating…';

        try {
            const res = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
            });
            const data = await res.json().catch(() => ({}));
            if (!res.ok) {
                if (res.status === 422 && data.errors) {
                    showErrors(data.errors);
                    window.notify?.error('Please fix the highlighted errors.');
                } else {
                    window.notify?.error(data.message || 'Something went wrong.');
                }
                return;
            }
            form.reset();
            window.notify?.success(data.message || 'Password changed successfully.');
        } catch (err) {
            window.notify?.error('Network error. Please try again.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = original;
        }
    });
})();
</script>
@endpush

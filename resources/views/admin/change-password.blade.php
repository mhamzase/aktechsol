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

    function clearErrors() {
        form.querySelectorAll('.ui-error').forEach((el) => el.remove());
        form.querySelectorAll('.ui-control-error').forEach((el) => el.classList.remove('ui-control-error'));
    }

    function fieldError(input, message) {
        if (!input) return;
        input.classList.add('ui-control-error');
        const wrap = input.closest('.ui-input-wrap');
        const next = wrap ? wrap.nextElementSibling : null;
        if (next && next.classList.contains('ui-error')) next.remove();
        const err = document.createElement('p');
        err.className = 'ui-error';
        err.textContent = message;
        wrap?.after(err);
    }

    function showErrors(errors) {
        Object.entries(errors).forEach(([field, msgs]) => {
            fieldError(form.querySelector(`[name="${field}"]`), msgs[0]);
        });
    }

    // Client-side validation — instant feedback before any request is sent.
    function validate() {
        clearErrors();
        const cur = form.querySelector('[name="current_password"]');
        const np = form.querySelector('[name="new_password"]');
        const cnp = form.querySelector('[name="new_password_confirmation"]');
        let ok = true;
        if (!cur.value) { fieldError(cur, 'Please enter your current password.'); ok = false; }
        if (!np.value) {
            fieldError(np, 'Please enter a new password.');
            ok = false;
        } else if (np.value.length < 8) {
            fieldError(np, 'New password must be at least 8 characters.');
            ok = false;
        } else if (np.value === cur.value) {
            fieldError(np, 'New password must be different from the current one.');
            ok = false;
        }
        if (!cnp.value) {
            fieldError(cnp, 'Please confirm your new password.');
            ok = false;
        } else if (np.value && cnp.value !== np.value) {
            fieldError(cnp, 'Passwords do not match.');
            ok = false;
        }
        return ok;
    }

    // Live: clear a field's error as soon as the user fixes it.
    form.addEventListener('input', (e) => {
        const input = e.target.closest('.ui-control');
        if (!input || !input.classList.contains('ui-control-error')) return;
        input.classList.remove('ui-control-error');
        const next = input.closest('.ui-input-wrap')?.nextElementSibling;
        if (next && next.classList.contains('ui-error')) next.remove();
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        e.stopPropagation();
        if (!validate()) {
            window.notify?.error('Please fix the highlighted errors.');
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
                    clearErrors();
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

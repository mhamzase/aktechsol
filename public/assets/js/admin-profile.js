/* ==========================================================================
   AK Tech Sol — Admin profile page (vanilla JS).
   Avatar drag & drop upload (AJAX), profile info form (AJAX),
   two-factor authentication with Fortify confirm flow (AJAX).
   Loaded only by admin/profile.blade.php via @push('scripts').
   ========================================================================== */
(function () {
    'use strict';

    const root = document.getElementById('profile-root');
    if (!root) return;

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const URLS = {
        avatar: root.dataset.avatarUrl,
        tfaStatus: root.dataset.tfaStatus,
        tfaEnable: root.dataset.tfaEnable,
        tfaConfirm: root.dataset.tfaConfirm,
        tfaDisable: root.dataset.tfaDisable,
        tfaRegenerate: root.dataset.tfaRegenerate,
    };

    /* ---------------- tiny JSON helper ---------------- */
    async function api(url, { method = 'POST', body = null } = {}) {
        const res = await fetch(url, {
            method,
            body,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf,
            },
        });
        const data = await res.json().catch(() => ({}));
        return { res, data };
    }
    const notifyOk = (m) => window.notify?.success(m);
    const notifyErr = (m) => window.notify?.error(m || 'Something went wrong.');

    /* ---------------- generic confirm modal (promise) ---------------- */
    const modal = document.getElementById('pf-confirm-modal');
    function pfConfirm({ title, text, okLabel = 'Confirm', danger = true }) {
        return new Promise((resolve) => {
            document.getElementById('pf-cm-title').textContent = title;
            document.getElementById('pf-cm-text').textContent = text;
            const okBtn = document.getElementById('pf-cm-ok');
            okBtn.textContent = okLabel;
            okBtn.className = 'btn ' + (danger ? 'btn-danger' : 'btn-primary');
            const close = (val) => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                okBtn.onclick = null;
                resolve(val);
            };
            modal.querySelectorAll('[data-pf-close]').forEach((el) => { el.onclick = () => close(false); });
            okBtn.onclick = () => close(true);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    }

    /* ================= AVATAR ================= */
    const dz = document.getElementById('avatar-dropzone');
    const fileInput = document.getElementById('avatar-input');
    const previewImg = document.getElementById('avatar-preview');
    const previewInitial = document.getElementById('avatar-initial');
    const removeBtn = document.getElementById('avatar-remove');
    const statusEl = document.getElementById('avatar-status');
    let uploading = false;

    function setAvatarUI(url, initial) {
        if (url) {
            previewImg.src = url;
            previewImg.classList.remove('hidden');
            previewInitial.classList.add('hidden');
            removeBtn.classList.remove('hidden');
        } else {
            previewImg.src = '';
            previewImg.classList.add('hidden');
            previewInitial.classList.remove('hidden');
            if (initial) previewInitial.textContent = initial;
            removeBtn.classList.add('hidden');
        }
        // Topbar avatar (cache-busted so the new photo shows instantly).
        const tb = document.getElementById('topbar-avatar');
        if (tb) {
            tb.innerHTML = url
                ? `<img src="${url}${url.includes('?') ? '&' : '?'}t=${Date.now()}" alt="Avatar" class="w-full h-full object-cover">`
                : (initial || '');
        }
    }

    function validImage(file) {
        const okTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!okTypes.includes(file.type)) { notifyErr('Only JPG, PNG or WEBP images are allowed.'); return false; }
        if (file.size > 2 * 1024 * 1024) { notifyErr('Image must be under 2MB.'); return false; }
        return true;
    }

    async function uploadAvatar(file) {
        if (uploading || !validImage(file)) return;
        uploading = true;
        // Remember current state in case the upload fails.
        const prevSrc = previewImg.classList.contains('hidden') ? null : previewImg.src;
        const prevInitial = previewInitial.textContent;
        // Instant local preview.
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImg.src = e.target.result;
            previewImg.classList.remove('hidden');
            previewInitial.classList.add('hidden');
        };
        reader.readAsDataURL(file);
        statusEl.innerHTML = '<span class="spin inline-block">◌</span> Uploading…';

        const fd = new FormData();
        fd.append('avatar', file);
        try {
            const { res, data } = await api(URLS.avatar, { body: fd });
            if (!res.ok) throw new Error(data.message || 'Upload failed.');
            setAvatarUI(data.avatar_url, null);
            notifyOk(data.message);
        } catch (err) {
            if (prevSrc) {
                previewImg.src = prevSrc;
                previewImg.classList.remove('hidden');
                previewInitial.classList.add('hidden');
            } else {
                setAvatarUI(null, prevInitial);
            }
            notifyErr(err.message);
        } finally {
            uploading = false;
            statusEl.textContent = '';
            fileInput.value = '';
        }
    }

    dz.addEventListener('click', () => fileInput.click());
    fileInput.addEventListener('change', () => {
        if (fileInput.files[0]) uploadAvatar(fileInput.files[0]);
    });
    ['dragenter', 'dragover'].forEach((ev) => dz.addEventListener(ev, (e) => {
        e.preventDefault();
        dz.classList.add('dz-active');
    }));
    ['dragleave', 'drop'].forEach((ev) => dz.addEventListener(ev, (e) => {
        e.preventDefault();
        dz.classList.remove('dz-active');
    }));
    dz.addEventListener('drop', (e) => {
        const file = e.dataTransfer.files?.[0];
        if (file) uploadAvatar(file);
    });
    removeBtn.addEventListener('click', async (e) => {
        e.stopPropagation();
        const yes = await pfConfirm({
            title: 'Remove profile photo?',
            text: 'Your current photo will be deleted and replaced with your initial.',
            okLabel: 'Remove',
        });
        if (!yes) return;
        const fd = new FormData();
        fd.append('remove', '1');
        const { res, data } = await api(URLS.avatar, { body: fd });
        if (res.ok) {
            setAvatarUI(null, data.initial);
            notifyOk(data.message);
        } else notifyErr(data.message);
    });

    /* ================= PROFILE FORM ================= */
    const form = document.getElementById('profile-form');
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        e.stopPropagation(); // keep the generic data-ajax-form handler away
        const btn = document.getElementById('profile-save-btn');
        const original = btn.innerHTML;
        form.querySelectorAll('.ui-error').forEach((el) => el.remove());
        form.querySelectorAll('.ui-control-error').forEach((el) => el.classList.remove('ui-control-error'));
        btn.disabled = true;
        btn.innerHTML = '<span class="spin inline-block">◌</span> Saving…';
        try {
            const { res, data } = await api(form.action, { method: 'POST', body: new FormData(form) });
            if (!res.ok) {
                if (res.status === 422 && data.errors) {
                    Object.entries(data.errors).forEach(([field, msgs]) => {
                        const input = form.querySelector(`[name="${field}"]`);
                        if (!input) return;
                        input.classList.add('ui-control-error');
                        const err = document.createElement('p');
                        err.className = 'ui-error';
                        err.textContent = msgs[0];
                        input.closest('.ui-input-wrap')?.after(err);
                    });
                    notifyErr('Please fix the highlighted errors.');
                } else notifyErr(data.message);
                return;
            }
            notifyOk(data.message);
            // Sync name everywhere (topbar, sidebar, avatar initial).
            document.getElementById('topbar-username').textContent = data.user.name;
            document.getElementById('sidebar-username').textContent = data.user.name;
            document.getElementById('sidebar-avatar').textContent = data.user.initial;
            if (previewInitial && !previewInitial.classList.contains('hidden')) {
                previewInitial.textContent = data.user.initial;
            }
            const tb = document.getElementById('topbar-avatar');
            if (tb && !tb.querySelector('img')) tb.textContent = data.user.initial;
        } catch (err) {
            notifyErr('Network error. Please try again.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = original;
        }
    });

    /* ================= TWO-FACTOR ================= */
    const tfaBadge = document.getElementById('tfa-badge');
    const tfaBody = document.getElementById('tfa-body');

    function setBadge(state) {
        const map = {
            enabled: '<span class="badge badge-green">Enabled</span>',
            confirming: '<span class="badge badge-amber">Confirm pending</span>',
            disabled: '<span class="badge badge-gray">Disabled</span>',
        };
        tfaBadge.innerHTML = map[state] || '';
    }

    function codesGrid(codes) {
        return `<div class="grid grid-cols-1 sm:grid-cols-2 gap-2 my-4">` +
            codes.map((c) => `<code class="text-xs bg-slate-100 rounded-lg px-3 py-2 font-mono text-slate-700 select-all">${c}</code>`).join('') +
            `</div>`;
    }

    function renderDisabled() {
        setBadge('disabled');
        tfaBody.innerHTML = `
            <div class="flex items-start gap-4">
                <div class="w-11 h-11 rounded-xl bg-blue-50 text-[#1d4ed8] flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-700">Extra security for your account</p>
                    <p class="text-sm text-slate-500 mt-1">Use an authenticator app (Google Authenticator, Authy…) to get a 6-digit code at login.</p>
                </div>
            </div>
            <button id="tfa-enable" class="btn btn-primary mt-4">Enable Two-Factor Authentication</button>`;
        document.getElementById('tfa-enable').addEventListener('click', async (e) => {
            const yes = await pfConfirm({
                title: 'Enable two-factor authentication?',
                text: 'You will need an authenticator app (Google Authenticator, Authy…) to get a 6-digit code at every login.',
                okLabel: 'Enable',
                danger: false,
            });
            if (!yes) return;
            const btn = e.currentTarget;
            btn.disabled = true;
            btn.innerHTML = '<span class="spin inline-block">◌</span> Generating…';
            const { res, data } = await api(URLS.tfaEnable);
            if (res.ok) renderConfirming(data.qr_svg, data.secret);
            else { notifyErr(data.message); btn.disabled = false; btn.textContent = 'Enable Two-Factor Authentication'; }
        });
    }

    function renderConfirming(qrSvg, secret) {
        setBadge('confirming');
        tfaBody.innerHTML = `
            <ol class="space-y-5">
                <li class="flex gap-4">
                    <span class="w-7 h-7 rounded-full bg-[#1d4ed8] text-white text-xs font-bold flex items-center justify-center shrink-0">1</span>
                    <div>
                        <p class="text-sm font-semibold text-slate-700">Scan this QR code</p>
                        <div id="tfa-qr" class="w-44 h-44 mt-2 bg-white p-2 rounded-xl border border-slate-200">${qrSvg}</div>
                        <p class="text-xs text-slate-400 mt-2">Can't scan? Enter this key manually:</p>
                        <div class="flex items-center gap-2 mt-1">
                            <code class="text-xs bg-slate-100 rounded-lg px-3 py-2 font-mono text-slate-700 select-all break-all">${secret}</code>
                            <button id="tfa-copy-secret" class="btn btn-secondary !px-3 !py-1.5 text-xs">Copy</button>
                        </div>
                    </div>
                </li>
                <li class="flex gap-4">
                    <span class="w-7 h-7 rounded-full bg-[#1d4ed8] text-white text-xs font-bold flex items-center justify-center shrink-0">2</span>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-slate-700">Enter the 6-digit code from your app</p>
                        <form id="tfa-confirm-form" class="flex flex-wrap items-center gap-3 mt-2">
                            <input id="tfa-code" name="code" inputmode="numeric" autocomplete="one-time-code" maxlength="6"
                                   placeholder="••••••"
                                   class="tfa-code-input ui-control !w-44 font-mono">
                            <button class="btn btn-primary">Confirm &amp; Enable</button>
                        </form>
                        <p id="tfa-code-error" class="ui-error hidden mt-2"></p>
                        <button id="tfa-cancel" class="mt-3 text-xs font-semibold text-slate-400 hover:text-slate-600">Cancel setup</button>
                    </div>
                </li>
            </ol>`;
        document.getElementById('tfa-copy-secret').addEventListener('click', () => {
            navigator.clipboard?.writeText(secret).then(
                () => notifyOk('Secret copied.'),
                () => notifyErr('Copy failed.')
            );
        });
        document.getElementById('tfa-confirm-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const code = document.getElementById('tfa-code').value.trim();
            const errEl = document.getElementById('tfa-code-error');
            errEl.classList.add('hidden');
            if (code.length < 6) {
                errEl.textContent = 'Enter the 6-digit code.';
                errEl.classList.remove('hidden');
                return;
            }
            const fd = new FormData();
            fd.append('code', code);
            const { res, data } = await api(URLS.tfaConfirm, { body: fd });
            if (res.ok) {
                renderEnabled(data.recovery_codes, true);
                notifyOk(data.message);
            } else {
                errEl.textContent = data.message || 'Invalid code.';
                errEl.classList.remove('hidden');
            }
        });
        document.getElementById('tfa-cancel').addEventListener('click', async () => {
            const yes = await pfConfirm({
                title: 'Cancel 2FA setup?',
                text: 'The generated secret will be discarded.',
                okLabel: 'Cancel setup',
            });
            if (!yes) return;
            const fd = new FormData();
            fd.append('_method', 'DELETE');
            const { res } = await api(URLS.tfaDisable, { body: fd });
            if (res.ok) { renderDisabled(); notifyOk('Setup cancelled.'); }
        });
    }

    function renderEnabled(codes, justEnabled = false) {
        setBadge('enabled');
        tfaBody.innerHTML = `
            ${justEnabled ? `<div class="mb-4 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                <span class="font-semibold">2FA is now active.</span> Save these recovery codes somewhere safe — each works once if you lose your device.
            </div>` : `<p class="text-sm text-slate-500 mb-1">Your account is protected. Keep your recovery codes safe:</p>`}
            ${codesGrid(codes)}
            <div class="flex flex-wrap gap-2">
                <button id="tfa-copy-codes" class="btn btn-secondary">Copy codes</button>
                <button id="tfa-download-codes" class="btn btn-secondary">Download .txt</button>
                <button id="tfa-regenerate" class="btn btn-secondary">Regenerate codes</button>
                <button id="tfa-disable" class="btn btn-danger">Disable 2FA</button>
            </div>`;
        const codesText = codes.join('\n');
        document.getElementById('tfa-copy-codes').addEventListener('click', () => {
            navigator.clipboard?.writeText(codesText).then(
                () => notifyOk('Recovery codes copied.'),
                () => notifyErr('Copy failed.')
            );
        });
        document.getElementById('tfa-download-codes').addEventListener('click', () => {
            const blob = new Blob(['AK Tech Sol — 2FA recovery codes\n\n' + codesText], { type: 'text/plain' });
            const a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = 'aktechsol-2fa-recovery-codes.txt';
            a.click();
            URL.revokeObjectURL(a.href);
        });
        document.getElementById('tfa-regenerate').addEventListener('click', async () => {
            const yes = await pfConfirm({
                title: 'Regenerate recovery codes?',
                text: 'Your current codes will stop working immediately.',
                okLabel: 'Regenerate',
            });
            if (!yes) return;
            const { res, data } = await api(URLS.tfaRegenerate);
            if (res.ok) { renderEnabled(data.recovery_codes); notifyOk(data.message); }
            else notifyErr(data.message);
        });
        document.getElementById('tfa-disable').addEventListener('click', async () => {
            const yes = await pfConfirm({
                title: 'Disable two-factor authentication?',
                text: 'Your account will no longer ask for a code at login.',
                okLabel: 'Disable 2FA',
            });
            if (!yes) return;
            const fd = new FormData();
            fd.append('_method', 'DELETE');
            const { res, data } = await api(URLS.tfaDisable, { body: fd });
            if (res.ok) { renderDisabled(); notifyOk(data.message); }
            else notifyErr(data.message);
        });
    }

    async function loadTfa() {
        try {
            const { res, data } = await api(URLS.tfaStatus, { method: 'GET' });
            if (!res.ok) throw new Error();
            if (data.state === 'enabled') renderEnabled(data.recovery_codes);
            else if (data.state === 'confirming') renderConfirming(data.qr_svg, data.secret);
            else renderDisabled();
        } catch (err) {
            tfaBody.innerHTML = '<p class="text-sm text-red-500">Could not load 2FA status. Please refresh.</p>';
        }
    }
    loadTfa();
})();

/* ==========================================================================
   AK Tech Sol — Admin JS (vanilla, no framework)
   Loaded by admin/layouts/app.blade.php. Depends on: Notyf (local).
   Provides: sidebar toggle, dropdowns, Notyf toasts, AdminTable (AJAX
   listings), drawer system (AJAX forms), delete confirmations.
   ========================================================================== */
(function () {
    'use strict';

    /* ---------------- CSRF token for all AJAX ---------------- */
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    /* ---------------- Notyf toasts (bottom-right) ---------------- */
    const notyf = new Notyf({
        duration: 3500,
        position: { x: 'right', y: 'bottom' },
        dismissible: true,
    });
    // Toast notifications — only ONE toast per message visible at a time.
    // DOM-based dedup (no reliance on Notyf internals): any existing toast
    // with the same text is removed before showing the new one.
    window.notify = (() => {
        const show = (type, msg) => {
            const text = (msg || '').trim();
            document.querySelectorAll('.notyf__toast').forEach((t) => {
                if (t.textContent.trim() === text) t.remove();
            });
            if (type === 'success') notyf.success(msg);
            else if (type === 'error') notyf.error(msg);
            else notyf.open({ type: 'info', message: msg, background: '#1d4ed8', icon: false });
        };
        return {
            success: (msg) => show('success', msg),
            error: (msg) => show('error', msg),
            info: (msg) => show('info', msg),
        };
    })();
    // Flash messages rendered by the layout on page load
    document.addEventListener('DOMContentLoaded', () => {
        const flash = document.getElementById('flash-messages');
        if (flash) {
            const ok = flash.dataset.success, err = flash.dataset.error;
            if (ok) window.notify.success(ok);
            if (err) window.notify.error(err);
        }
    });

    /* ---------------- Sidebar (mobile) ---------------- */
    window.toggleSidebar = function () {
        document.getElementById('admin-sidebar')?.classList.toggle('-translate-x-full');
        document.getElementById('sidebar-overlay')?.classList.toggle('hidden');
    };

    /* ---------------- Dropdowns (user menu, notifications) ---------------- */
    window.toggleDropdown = function (id) {
        const el = document.getElementById(id);
        if (!el) return;
        const wasHidden = el.classList.contains('hidden');
        document.querySelectorAll('[data-dropdown]').forEach((d) => d.classList.add('hidden'));
        if (wasHidden) el.classList.remove('hidden');
    };
    document.addEventListener('click', (e) => {
        if (!e.target.closest('[data-dropdown-toggle]') && !e.target.closest('[data-dropdown]')) {
            document.querySelectorAll('[data-dropdown]').forEach((d) => d.classList.add('hidden'));
        }
    });

    /* ---------------- Global search (topbar, Ctrl+K) ----------------
       Builds its index from the rendered sidebar links, so it always
       respects the user's permissions with zero duplication. */
    (function initGlobalSearch() {
        const input = document.getElementById('global-search-input');
        const box = document.getElementById('global-search-results');
        if (!input || !box) return;

        const index = [];
        const seen = new Set();
        document.querySelectorAll('#admin-sidebar .nav-link').forEach((a) => {
            const label = a.textContent.replace(/[\d]+$/, '').trim();
            if (label && a.href && !seen.has(a.href)) {
                seen.add(a.href);
                index.push({ label, url: a.href });
            }
        });

        let activeIdx = -1;

        function close() { box.classList.add('hidden'); activeIdx = -1; }

        function render(matches) {
            if (!matches.length) {
                box.innerHTML = '<p class="px-4 py-6 text-center text-sm text-slate-400">No pages found.</p>';
            } else {
                box.innerHTML = matches.slice(0, 7).map((m, i) => `
                    <a href="${m.url}" data-gs-idx="${i}"
                       class="gs-item flex items-center justify-between px-4 py-2.5 text-sm ${i === activeIdx ? 'bg-blue-50' : ''} hover:bg-blue-50">
                        <span class="font-medium text-slate-700">${m.label}</span>
                        <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>`).join('');
            }
            box.classList.remove('hidden');
        }

        let current = [];
        input.addEventListener('input', () => {
            const q = input.value.trim().toLowerCase();
            activeIdx = -1;
            if (q.length < 2) { close(); return; }
            current = index.filter((m) => m.label.toLowerCase().includes(q));
            render(current);
        });
        input.addEventListener('keydown', (e) => {
            if (box.classList.contains('hidden')) return;
            if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                e.preventDefault();
                const n = Math.min(current.length, 7);
                activeIdx = e.key === 'ArrowDown'
                    ? (activeIdx + 1) % n
                    : (activeIdx - 1 + n) % n;
                render(current);
            } else if (e.key === 'Enter') {
                const pick = current[activeIdx >= 0 ? activeIdx : 0];
                if (pick) window.location.href = pick.url;
            } else if (e.key === 'Escape') {
                close(); input.blur();
            }
        });
        box.addEventListener('click', (e) => {
            const a = e.target.closest('.gs-item');
            if (a) window.location.href = a.href;
        });
        document.addEventListener('click', (e) => {
            if (!e.target.closest('#topbar-search')) close();
        });
        // Ctrl+K / Cmd+K focuses the search from anywhere.
        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                e.preventDefault();
                input.focus();
            }
        });
    })();

    /* ---------------- Dark / light mode (topbar toggle) ---------------- */
    function syncThemeIcon() {
        const dark = document.documentElement.classList.contains('dark');
        document.getElementById('theme-icon-moon')?.classList.toggle('hidden', dark);
        document.getElementById('theme-icon-sun')?.classList.toggle('hidden', !dark);
    }
    window.toggleTheme = function () {
        const dark = document.documentElement.classList.toggle('dark');
        try { localStorage.setItem('aktechsol-admin-theme', dark ? 'dark' : 'light'); } catch (e) {}
        syncThemeIcon();
        // Re-render dashboard charts with the matching theme.
        if (typeof window.renderAdminCharts === 'function') window.renderAdminCharts();
    };
    document.addEventListener('DOMContentLoaded', syncThemeIcon);

    /* ---------------- AdminTable: shared AJAX listing ----------------
       Usage in Blade:
         <x-admin.data-table id="quotes-table" ajax-url="route('admin.quotes.index')"
             :columns="['Quote', 'Client', 'Status', 'Date']" />
       The component renders: toolbar (search + per-page), <table> with the
       given <thead>, empty <tbody data-rows>, and <div data-pagination>.
       Server must return JSON for AJAX requests:
         { rows: "<tr>…", pagination: "…", stats: "…" }
    ------------------------------------------------------------------ */
    class AdminTable {
        constructor(root) {
            this.root = root;
            this.url = root.dataset.ajaxUrl;
            this.rowsEl = root.querySelector('[data-rows]');
            this.pagEl = root.querySelector('[data-pagination]');
            this.statsEl = root.querySelector('[data-stats]');
            this.searchEl = root.querySelector('[data-search]');
            this.perPageEl = root.querySelector('[data-per-page]');
            this.params = { page: 1, search: '', per_page: this.perPageEl?.value || 10 };
            this.bind();
            this.load();
        }
        bind() {
            let t;
            const clearBtn = this.root.querySelector('[data-clear-search]');
            const toggleClear = () => clearBtn?.classList.toggle('hidden', !this.searchEl?.value);
            this.searchEl?.addEventListener('input', (e) => {
                clearTimeout(t);
                toggleClear();
                t = setTimeout(() => { this.params.search = e.target.value; this.params.page = 1; this.load(); }, 350);
            });
            clearBtn?.addEventListener('click', () => {
                if (this.searchEl) this.searchEl.value = '';
                toggleClear();
                this.params.search = ''; this.params.page = 1; this.load();
            });
            this.perPageEl?.addEventListener('change', (e) => {
                this.params.per_page = e.target.value; this.params.page = 1; this.load();
            });
            // Extra filters: any [data-table-filter="param"] inside the table root.
            this.filterEls = Array.from(this.root.querySelectorAll('[data-table-filter]'));
            this.filterEls.forEach((el) => {
                const name = el.dataset.tableFilter;
                if (el.value) this.params[name] = el.value;
                el.addEventListener('change', () => {
                    this.params[name] = el.value; this.params.page = 1; this.load();
                });
            });
            this.pagEl?.addEventListener('click', (e) => {
                const a = e.target.closest('a[data-page]');
                if (!a) return;
                e.preventDefault();
                this.params.page = a.dataset.page;
                this.load();
                this.root.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        }
        async load() {
            this.rowsEl.innerHTML = `<tr><td colspan="20" class="text-center py-10">
                <div class="admin-loader al-lg"><span class="al-ring"></span><span>Loading…</span></div></td></tr>`;
            const qs = new URLSearchParams(this.params).toString();
            try {
                const res = await fetch(`${this.url}?${qs}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' },
                });
                const data = await res.json();
                this.rowsEl.innerHTML = data.rows || '';
                if (this.pagEl) this.pagEl.innerHTML = data.pagination || '';
                if (this.statsEl && data.stats !== undefined) this.statsEl.innerHTML = data.stats;
            } catch (err) {
                this.rowsEl.innerHTML = `<tr><td colspan="20" class="text-center py-8 text-red-500">
                    Failed to load. Please retry.</td></tr>`;
            }
        }
        reload() { this.load(); }
    }
    window.AdminTable = AdminTable;
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-admin-table]').forEach((el) => {
            el._table = new AdminTable(el);
        });
    });
    // Reload a table from anywhere: reloadAdminTable('quotes-table')
    window.reloadAdminTable = function (id) {
        document.getElementById(id)?._table?.reload();
    };

    /* ---------------- Drawer system (AJAX forms) ----------------
       openDrawer(url)  — GETs the form HTML into the drawer.
       Forms inside use data-ajax-form; on success the drawer closes,
       a toast shows, and the given table reloads.
    ------------------------------------------------------------------ */
    const overlay = () => document.getElementById('drawer-overlay');
    const drawer = () => document.getElementById('drawer');

    window.openDrawer = async function (url, title = '', wide = false) {
        const d = drawer();
        if (!d) return;
        d.classList.toggle('drawer-wide', !!wide);
        d.querySelector('[data-drawer-title]').textContent = title;
        d.querySelector('[data-drawer-body]').innerHTML =
            '<div class="p-10"><div class="admin-loader al-lg"><span class="al-ring"></span><span>Loading…</span></div></div>';
        overlay().classList.add('open');
        d.classList.add('open');
        document.body.style.overflow = 'hidden';
        try {
            const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            d.querySelector('[data-drawer-body]').innerHTML = await res.text();
        } catch (err) {
            d.querySelector('[data-drawer-body]').innerHTML =
                '<div class="p-8 text-center text-red-500">Failed to load form.</div>';
        }
    };
    window.closeDrawer = function () {
        overlay()?.classList.remove('open');
        drawer()?.classList.remove('open');
        document.body.style.overflow = '';
    };
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') window.closeDrawer(); });

    /* ------------------------------------------------------------------
       Row actions (x-admin.row-actions) — STANDARD 3-dots menu, delegated.
       Works for AJAX-loaded rows everywhere. Closes other open menus.
    ------------------------------------------------------------------ */
    document.addEventListener('click', (e) => {
        // Drawer close button (x-admin.drawer-footer Cancel).
        if (e.target.closest('[data-drawer-close]')) {
            closeDrawer();
            return;
        }
        const toggle = e.target.closest('[data-row-actions-toggle]');
        // Close menus that don't contain the clicked toggle.
        document.querySelectorAll('.row-actions-menu:not(.hidden)').forEach((m) => {
            if (!toggle || !m.closest('[data-row-actions]').contains(toggle)) {
                m.classList.add('hidden');
                m.style.position = ''; m.style.top = ''; m.style.right = ''; m.style.left = '';
            }
        });
        if (toggle) {
            const menu = toggle.closest('[data-row-actions]')?.querySelector('.row-actions-menu');
            if (menu && menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                // Position fixed: escapes overflow clipping from table wrappers.
                // Flips upward when there isn't enough space below.
                const rect = toggle.getBoundingClientRect();
                const mh = menu.offsetHeight;
                let top = rect.bottom + 4;
                if (top + mh > window.innerHeight - 8) top = rect.top - mh - 4;
                menu.style.position = 'fixed';
                menu.style.top = Math.max(8, top) + 'px';
                menu.style.left = 'auto';
                menu.style.right = Math.max(8, window.innerWidth - rect.right) + 'px';
            }
            return;
        }
        const editBtn = e.target.closest('[data-row-edit]');
        if (editBtn) {
            openDrawer(editBtn.dataset.rowEdit, editBtn.dataset.drawerTitle || 'Edit', editBtn.dataset.drawerWide === '1');
            return;
        }
        const delBtn = e.target.closest('[data-row-delete]');
        if (delBtn) {
            confirmDelete(delBtn.dataset.rowDelete, delBtn.dataset.table || '');
        }
    });

    // Close any open row-actions menu on scroll / resize (fixed positioning).
    const closeRowMenus = () => document.querySelectorAll('.row-actions-menu:not(.hidden)').forEach((m) => {
        m.classList.add('hidden');
        m.style.position = ''; m.style.top = ''; m.style.right = ''; m.style.left = '';
    });
    window.addEventListener('scroll', closeRowMenus, true);
    window.addEventListener('resize', closeRowMenus);

    /* ------------------------------------------------------------------
       Permissions drawer (data-perm-form) — delegated, works for AJAX-
       loaded drawer content. Inline <script> never runs via innerHTML,
       so all logic lives here.
    ------------------------------------------------------------------ */
    const permBoxes = (form) => [...form.querySelectorAll('.perm-check')];
    const refreshPermCounts = (form) => {
        const all = permBoxes(form);
        const on = all.filter((b) => b.checked).length;
        const totalEl = form.querySelector('[data-perm-total]');
        if (totalEl) totalEl.textContent = on;
        const master = form.querySelector('[data-perm-toggle]');
        if (master) master.textContent = on === all.length && all.length ? 'Deselect all' : 'Select all';
        form.querySelectorAll('[data-perm-group]').forEach((g) => {
            const gb = [...g.querySelectorAll('.perm-check')];
            const gon = gb.filter((b) => b.checked).length;
            const cnt = g.querySelector('[data-perm-count]');
            if (cnt) cnt.textContent = gon + ' / ' + gb.length + ' enabled';
            const allBtn = g.querySelector('[data-perm-group-all]');
            if (allBtn) allBtn.textContent = gon === gb.length && gb.length ? 'None' : 'All';
        });
    };
    // Init counts when the drawer content arrives (after openDrawer injects it).
    const drawerBody = document.querySelector('#drawer [data-drawer-body]');
    if (drawerBody) {
        new MutationObserver((mutations) => {
            mutations.forEach((m) => m.addedNodes.forEach((n) => {
                if (n.nodeType !== 1) return;
                const form = n.matches?.('form[data-perm-form]') ? n : n.querySelector?.('form[data-perm-form]');
                if (form) refreshPermCounts(form);
            }));
        }).observe(drawerBody, { childList: true });
    }

    document.addEventListener('change', (e) => {
        const form = e.target.closest('form[data-perm-form]');
        if (form) refreshPermCounts(form);
    });
    // Live-clear field errors as the user fixes them (all AJAX forms).
    document.addEventListener('input', (e) => {
        const form = e.target.closest('form[data-ajax-form]');
        if (!form || !e.target.classList?.contains('ui-control-error')) return;
        e.target.classList.remove('ui-control-error');
        const wrap = e.target.closest('.ui-input-wrap');
        const err = wrap ? wrap.nextElementSibling : e.target.closest('div')?.querySelector('.ui-error');
        if (err && err.classList.contains('ui-error')) err.remove();
    });
    // Avatar picker: instant preview + remove flag (delegated, drawer-safe).
    document.addEventListener('change', (e) => {
        const input = e.target.closest('[data-avatar-input]');
        if (!input) return;
        const form = input.closest('form');
        const preview = form?.querySelector('[data-avatar-preview]');
        const file = input.files?.[0];
        if (!file || !preview) return;
        const reader = new FileReader();
        reader.onload = (ev) => {
            preview.innerHTML = `<img src="${ev.target.result}" alt="" class="w-full h-full object-cover">`;
        };
        reader.readAsDataURL(file);
        const flag = form.querySelector('[data-avatar-remove-flag]');
        if (flag) flag.value = '0';
    });
    document.addEventListener('click', (e) => {
        const rmBtn = e.target.closest('[data-avatar-remove]');
        if (!rmBtn) return;
        const form = rmBtn.closest('form');
        const preview = form?.querySelector('[data-avatar-preview]');
        const input = form?.querySelector('[data-avatar-input]');
        const flag = form?.querySelector('[data-avatar-remove-flag]');
        if (input) input.value = '';
        if (flag) flag.value = '1';
        if (preview) {
            const name = form.querySelector('[name="name"]')?.value || 'U';
            preview.innerHTML = name.trim().charAt(0).toUpperCase() || 'U';
        }
        rmBtn.remove();
    });
    document.addEventListener('click', (e) => {
        const form = e.target.closest('form[data-perm-form]');
        if (!form) return;
        // Collapse / expand group.
        const grpToggle = e.target.closest('[data-perm-group-toggle]');
        if (grpToggle) {
            const list = grpToggle.closest('[data-perm-group]').querySelector('[data-perm-list]');
            const chev = grpToggle.querySelector('[data-perm-chevron]');
            list.classList.toggle('hidden');
            if (chev) chev.style.transform = list.classList.contains('hidden') ? 'rotate(-90deg)' : '';
            return;
        }
        // Per-group All / None.
        const grpAll = e.target.closest('[data-perm-group-all]');
        if (grpAll) {
            const gb = [...grpAll.closest('[data-perm-group]').querySelectorAll('.perm-check')];
            const target = gb.some((b) => !b.checked);
            gb.forEach((b) => { b.checked = target; });
            refreshPermCounts(form);
            return;
        }
        // Master Select all / Deselect all.
        if (e.target.closest('[data-perm-toggle]')) {
            const all = permBoxes(form);
            const target = all.some((b) => !b.checked);
            all.forEach((b) => { b.checked = target; });
            refreshPermCounts(form);
        }
    });

    // AJAX form submit (delegated — works for drawer-injected forms)
    // Shows a field error under an x-ui (or raw) input.
    const showFieldError = (form, input, message) => {
        if (!input) return;
        input.classList.add('ui-control-error');
        const err = document.createElement('p');
        err.className = 'ui-error';
        err.textContent = message;
        const wrap = input.closest('.ui-input-wrap');
        if (wrap) wrap.after(err);
        else (input.closest('div') || form).appendChild(err);
    };
    const clearFieldErrors = (form) => {
        form.querySelectorAll('.form-error, .ui-error').forEach((el) => el.remove());
        form.querySelectorAll('.ui-control-error').forEach((el) => el.classList.remove('ui-control-error'));
    };
    document.addEventListener('submit', async (e) => {
        const form = e.target.closest('form[data-ajax-form]');
        if (!form) return;
        e.preventDefault();
        const btn = form.querySelector('[type="submit"]');
        const original = btn ? btn.innerHTML : '';
        if (btn) { btn.disabled = true; btn.innerHTML = '<span class="spin inline-block">◌</span> Saving…'; }
        clearFieldErrors(form);

        // ---- Frontend validation (runs before the server call) ----
        let valid = true;
        form.querySelectorAll('[required]').forEach((input) => {
            if (input.disabled || input.type === 'hidden') return;
            const val = (input.value || '').trim();
            if (!val) {
                showFieldError(form, input, 'This field is required.');
                valid = false;
            } else if (input.type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
                showFieldError(form, input, 'Please enter a valid email address.');
                valid = false;
            } else if (input.type === 'url' && !/^(https?:\/\/)?([\w-]+\.)+[\w-]+(\/\S*)?$/.test(val)) {
                showFieldError(form, input, 'Please enter a valid URL (e.g. https://…).');
                valid = false;
            } else if (input.name === 'password' && val.length > 0 && val.length < 8) {
                showFieldError(form, input, 'Password must be at least 8 characters.');
                valid = false;
            }
        });
        const pw = form.querySelector('[name="password"]');
        const pwc = form.querySelector('[name="password_confirmation"]');
        if (pw && pwc && (pw.value || pwc.value) && pw.value !== pwc.value) {
            showFieldError(form, pwc, 'Passwords do not match.');
            valid = false;
        }
        // Payment methods: if any field in a card is filled, method name is required.
        form.querySelectorAll('.pm-card').forEach((card) => {
            const methodInput = card.querySelector('[name="payment_methods[][method]"]');
            if (!methodInput) return;
            const anyFilled = [...card.querySelectorAll('input')].some((i) => i.value.trim() !== '');
            if (anyFilled && !methodInput.value.trim()) {
                showFieldError(form, methodInput, 'Payment method name is required.');
                valid = false;
            }
        });
        if (!valid) {
            // Cooldown: keep the button disabled briefly so rapid double-clicks
            // can't spawn duplicate submissions/toasts.
            setTimeout(() => { if (btn) { btn.disabled = false; btn.innerHTML = original; } }, 1200);
            window.notify?.error('Please fix the highlighted errors.');
            return;
        }

        try {
            const res = await fetch(form.action, {
                method: form.method || 'POST',
                body: new FormData(form),
                headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken },
            });
            const data = await res.json().catch(() => ({}));
            if (!res.ok) {
                if (res.status === 422 && data.errors) {
                    Object.entries(data.errors).forEach(([field, msgs]) => {
                        showFieldError(form, form.querySelector(`[name="${field}"]`), msgs[0]);
                    });
                    window.notify?.error('Please fix the highlighted errors.');
                } else {
                    const detail = data.message ? `: ${data.message}` : ` (HTTP ${res.status})`;
                    window.notify?.error(`Something went wrong${detail}.`);
                    console.error('[AJAX form]', form.action, res.status, data);
                }
                return;
            }
            window.notify?.success(data.message || 'Saved successfully.');
            // Update branding previews (settings general tab) without a page refresh.
            if (data.logo_url || data.favicon_url) {
                const updatePreview = (key, url) => {
                    if (!url) return;
                    const box = form.querySelector(`[data-brand-preview="${key}"]`);
                    if (!box) return;
                    let img = box.querySelector('img');
                    if (!img) {
                        img = document.createElement('img');
                        img.alt = key;
                        img.className = 'h-10 mb-2 rounded-lg bg-slate-100 p-1 dark:bg-white/10';
                        box.prepend(img);
                    }
                    img.src = url + '?t=' + Date.now();
                };
                updatePreview('logo', data.logo_url);
                updatePreview('favicon', data.favicon_url);
                updatePreview('popup_image', data.popup_image_url);
                updatePreview('seo_og_image', data.seo_og_image_url);
            }
            window.closeDrawer();
            if (form.dataset.reloadTable) window.reloadAdminTable(form.dataset.reloadTable);
            if (data.redirect) window.location.href = data.redirect;
        } catch (err) {
            window.notify.error('Network error. Please try again.');
        } finally {
            if (btn) { btn.disabled = false; btn.innerHTML = original; }
        }
    });

    /* ---------------- Delete confirmation ---------------- */
    let pendingDelete = null;
    window.confirmDelete = function (url, tableId, message) {
        pendingDelete = { url, tableId };
        document.getElementById('confirm-delete-message').textContent =
            message || 'Are you sure you want to delete this? This cannot be undone.';
        document.getElementById('confirm-delete-modal').classList.remove('hidden');
    };
    window.closeConfirmDelete = function () {
        pendingDelete = null;
        document.getElementById('confirm-delete-modal').classList.add('hidden');
    };
    window.doDelete = async function () {
        if (!pendingDelete) return;
        const { url, tableId } = pendingDelete;
        window.closeConfirmDelete();
        try {
            const res = await fetch(url, {
                method: 'DELETE',
                headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken },
            });
            const data = await res.json().catch(() => ({}));
            if (!res.ok) throw new Error(data.message || 'Delete failed.');
            window.notify.success(data.message || 'Deleted successfully.');
            if (tableId) window.reloadAdminTable(tableId);
        } catch (err) {
            window.notify.error(err.message);
        }
    };
})();

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
                // Let listeners repaint dynamic row elements (progress bars, etc.).
                document.dispatchEvent(new CustomEvent('tableRowsLoaded'));
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
            const body = d.querySelector('[data-drawer-body]');
            body.innerHTML = await res.text();
            body.dataset.drawerUrl = url;
            // Paint dynamic elements (progress sliders, etc.) after drawer content loads.
            document.dispatchEvent(new CustomEvent('drawerOpened'));
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

        // ---- Confirmation (if the form declares one) ----
        if (form.dataset.confirmTitle && !form.dataset.confirmDone) {
            window.confirmAction({
                title: form.dataset.confirmTitle,
                message: form.dataset.confirmMessage || 'Are you sure?',
                confirmLabel: form.dataset.confirmLabel || 'Confirm',
                onConfirm: () => {
                    form.dataset.confirmDone = '1';
                    form.requestSubmit();
                },
            });
            return;
        }
        delete form.dataset.confirmDone;

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
            const data = await res.json().catch(() => null);
            // A redirected or non-JSON 200 (e.g. session expired -> login page)
            // is NOT a success — the record was never saved. Never fake it.
            if (res.ok && (!data || res.redirected)) {
                window.notify?.error('Session expired or unexpected response — nothing was saved. Please refresh the page and try again.');
                console.error('[AJAX form] Non-JSON response from', form.action);
                return;
            }
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
            document.dispatchEvent(new CustomEvent('ajaxFormSuccess', { detail: data }));
            // Forms may ask to reopen a drawer with fresh content (e.g. back
            // to a project's details after saving its revenue distribution).
            if (form.dataset.reopenDrawer && window.openDrawer) {
                window.openDrawer(form.dataset.reopenDrawer, form.dataset.reopenTitle || 'Details');
            }
            if (data.redirect) window.location.href = data.redirect;
        } catch (err) {
            window.notify.error('Network error. Please try again.');
        } finally {
            if (btn) { btn.disabled = false; btn.innerHTML = original; }
        }
    });

    /* ---------------- Revenue distribution live calculator ----------------
       The distribution drawer (delivered projects) recalculates each
       share's amount + the allocated/remaining totals as percentages
       are typed, and only enables Save at exactly 100%. */
    function initRevenueForm(form) {
        if (!form || form.dataset.revInit) return;
        form.dataset.revInit = '1';
        const base = parseFloat(form.dataset.base || '0');
        const symbol = form.dataset.symbol || 'Rs ';
        const decimals = parseInt(form.dataset.decimals || '0', 10);
        const fmt = (n) => symbol + n.toLocaleString('en-US', { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
        const recalc = () => {
            let total = 0;
            form.querySelectorAll('[data-revenue-pct]').forEach((input) => {
                const pct = parseFloat(input.value || '0') || 0;
                total += pct;
                const amtEl = input.closest('[data-revenue-row]')?.querySelector('[data-revenue-amt]');
                if (amtEl) amtEl.textContent = fmt(base * pct / 100);
            });
            total = Math.round(total * 100) / 100;
            const remaining = Math.round((100 - total) * 100) / 100;
            const allocEl = form.querySelector('[data-revenue-allocated]');
            const remEl = form.querySelector('[data-revenue-remaining]');
            const barEl = form.querySelector('[data-revenue-bar]');
            if (allocEl) allocEl.textContent = total + '%';
            if (remEl) remEl.textContent = remaining + '%';
            if (barEl) {
                barEl.style.width = Math.min(Math.max(total, 0), 100) + '%';
                barEl.style.backgroundColor = total === 100 ? '#16a34a' : (total > 100 ? '#ef4444' : '#3b82f6');
            }
            const saveBtn = form.querySelector('button[type="submit"]');
            if (saveBtn) saveBtn.disabled = Math.abs(total - 100) > 0.009;
        };
        form.addEventListener('input', (e) => { if (e.target.matches('[data-revenue-pct]')) recalc(); });
        recalc();
    }
    // Drawer content is injected dynamically, so watch for the form.
    new MutationObserver(() => {
        document.querySelectorAll('form[data-revenue-form]:not([data-rev-init])').forEach(initRevenueForm);
    }).observe(document.body, { childList: true, subtree: true });
    document.querySelectorAll('form[data-revenue-form]').forEach(initRevenueForm);

    /* ---------------- Searchable select (x-ui.search-select) ---------------- */
    document.addEventListener('click', (e) => {
        // Close all open menus when clicking outside.
        document.querySelectorAll('[data-search-select-menu]').forEach(menu => {
            const wrap = menu.closest('[data-search-select]');
            if (wrap && !wrap.contains(e.target)) menu.classList.add('hidden');
        });

        const toggle = e.target.closest('[data-search-select-toggle]');
        if (toggle) {
            const wrap = toggle.closest('[data-search-select]');
            const menu = wrap.querySelector('[data-search-select-menu]');
            const wasHidden = menu.classList.contains('hidden');
            document.querySelectorAll('[data-search-select-menu]').forEach(m => m.classList.add('hidden'));
            if (wasHidden) {
                menu.classList.remove('hidden');
                const search = wrap.querySelector('[data-search-select-search]');
                search.value = '';
                wrap.querySelectorAll('[data-search-select-option]').forEach(o => o.classList.remove('hidden'));
                wrap.querySelector('[data-search-select-empty]').classList.add('hidden');
                setTimeout(() => search.focus(), 50);
            }
            return;
        }

        const option = e.target.closest('[data-search-select-option]');
        if (option) {
            const wrap = option.closest('[data-search-select]');
            wrap.querySelector('[data-search-select-value]').value = option.dataset.value;
            const label = wrap.querySelector('[data-search-select-label]');
            label.textContent = option.dataset.label;
            label.classList.remove('text-slate-400');
            wrap.querySelector('[data-search-select-menu]').classList.add('hidden');
            wrap.querySelectorAll('[data-search-select-option]').forEach(o => {
                const selected = o.dataset.value === option.dataset.value;
                o.classList.toggle('bg-blue-50', selected);
                o.classList.toggle('font-bold', selected);
                o.classList.toggle('text-blue-700', selected);
            });
        }
    });

    document.addEventListener('input', (e) => {
        const search = e.target.closest('[data-search-select-search]');
        if (!search) return;
        const wrap = search.closest('[data-search-select]');
        const term = search.value.toLowerCase().trim();
        let visible = 0;
        wrap.querySelectorAll('[data-search-select-option]').forEach(o => {
            const match = o.dataset.label.toLowerCase().includes(term);
            o.classList.toggle('hidden', !match);
            if (match) visible++;
        });
        wrap.querySelector('[data-search-select-empty]').classList.toggle('hidden', visible > 0);
    });

    /* ---------------- Invoice actions (delegated, drawer-safe) ---------------- */
    // Helper: reload the current drawer content.
    function reloadInvoiceDrawer() {
        const drawerBody = document.querySelector('[data-drawer-body]');
        const currentUrl = drawerBody?.dataset?.drawerUrl;
        if (currentUrl && window.openDrawer) {
            const title = document.querySelector('[data-drawer-title]')?.textContent || '';
            window.openDrawer(currentUrl, title);
        }
    }

    document.addEventListener('click', async (e) => {
        // Verify a payment proof.
        const verifyBtn = e.target.closest('[data-verify-payment]');
        if (verifyBtn) {
            window.confirmAction({
                title: 'Verify Payment?',
                message: 'The amount will be added to the invoice paid total.',
                confirmLabel: 'Verify',
                onConfirm: async () => {
                    try {
                        const res = await fetch(verifyBtn.dataset.url, {
                            method: 'POST',
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': window.csrfToken, 'Accept': 'application/json' }
                        });
                        const data = await res.json().catch(() => ({}));
                        if (res.ok) { window.notify.success(data.message || 'Verified!'); reloadInvoiceDrawer(); }
                        else window.notify.error(data.message || 'Failed.');
                    } catch (err) { window.notify.error('Network error.'); }
                },
            });
            return;
        }

        // Reject a payment proof.
        const rejectBtn = e.target.closest('[data-reject-payment]');
        if (rejectBtn) {
            window.confirmAction({
                title: 'Reject Payment?',
                message: 'This proof will be marked as rejected.',
                confirmLabel: 'Reject',
                confirmClass: 'btn-danger',
                onConfirm: async () => {
                    try {
                        const res = await fetch(rejectBtn.dataset.url, {
                            method: 'POST',
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': window.csrfToken, 'Accept': 'application/json' }
                        });
                        const data = await res.json().catch(() => ({}));
                        if (res.ok) { window.notify.success(data.message || 'Rejected!'); reloadInvoiceDrawer(); }
                        else window.notify.error(data.message || 'Failed.');
                    } catch (err) { window.notify.error('Network error.'); }
                },
            });
            return;
        }

        // Email invoice to client.
        const emailBtn = e.target.closest('[data-invoice-email]');
        if (emailBtn) {
            window.confirmAction({
                title: 'Email Invoice?',
                message: 'The invoice will be emailed to the client.',
                confirmLabel: 'Send Email',
                onConfirm: async () => {
                    try {
                        const res = await fetch(emailBtn.dataset.url, {
                            method: 'POST',
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': window.csrfToken, 'Accept': 'application/json' }
                        });
                        const data = await res.json().catch(() => ({}));
                        window.notify[res.ok ? 'success' : 'error'](data.message || (res.ok ? 'Sent!' : 'Failed.'));
                    } catch (err) { window.notify.error('Network error.'); }
                },
            });
            return;
        }

        // Mark invoice as paid.
        const paidBtn = e.target.closest('[data-invoice-mark-paid]');
        if (paidBtn) {
            window.confirmAction({
                title: 'Mark as Paid?',
                message: 'The full invoice amount will be marked as paid.',
                confirmLabel: 'Mark Paid',
                onConfirm: async () => {
                    try {
                        const res = await fetch(paidBtn.dataset.url, {
                            method: 'POST',
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': window.csrfToken, 'Accept': 'application/json' }
                        });
                        const data = await res.json().catch(() => ({}));
                        if (res.ok) {
                            window.notify.success(data.message || 'Done!');
                            window.closeDrawer();
                            window.reloadAdminTable('invoices-table');
                        } else window.notify.error(data.message || 'Failed.');
                    } catch (err) { window.notify.error('Network error.'); }
                },
            });
        }
    });

    // Manual payment form (AJAX).
    document.addEventListener('submit', async (e) => {
        const form = e.target.closest('[data-manual-payment]');
        if (!form) return;
        e.preventDefault();
        const btn = form.querySelector('[type="submit"]');
        btn.disabled = true;
        try {
            const res = await fetch(form.dataset.url, {
                method: 'POST',
                body: new FormData(form),
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            const data = await res.json().catch(() => ({}));
            if (res.ok) { window.notify.success(data.message || 'Recorded!'); reloadInvoiceDrawer(); }
            else if (res.status === 422 && data.errors) {
                window.notify.error(Object.values(data.errors)[0]?.[0] || 'Please check your input.');
            } else window.notify.error(data.message || 'Failed.');
        } catch (err) { window.notify.error('Network error.'); }
        finally { btn.disabled = false; }
    });

    /* ---------------- Review actions (delegated, drawer-safe) ---------------- */
    // Helper: refresh the reviews table + stat cards from a JSON response.
    function refreshReviews(data) {
        if (window.reloadAdminTable) window.reloadAdminTable('reviews-table');
        if (data && data.stats) {
            Object.entries(data.stats).forEach(([key, val]) => {
                const el = document.querySelector(`#review-stats [data-stat="${key}"]`);
                if (el) el.textContent = key === 'avg' ? val + ' ★' : val;
            });
        }
    }

    document.addEventListener('click', async (e) => {
        // Approve a review.
        const approveBtn = e.target.closest('[data-approve-review]');
        if (approveBtn) {
            window.confirmAction({
                title: 'Approve Review?',
                message: 'This review will be published on the website.',
                confirmLabel: 'Approve',
                onConfirm: async () => {
                    try {
                        const res = await fetch(approveBtn.dataset.url, {
                            method: 'POST',
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': window.csrfToken, 'Accept': 'application/json' }
                        });
                        const data = await res.json().catch(() => ({}));
                        if (res.ok) { window.notify.success(data.message || 'Approved!'); refreshReviews(data); window.closeDrawer(); }
                        else window.notify.error(data.message || 'Failed.');
                    } catch (err) { window.notify.error('Network error.'); }
                },
            });
            return;
        }

        // Reject a review.
        const rejectBtn = e.target.closest('[data-reject-review]');
        if (rejectBtn) {
            window.confirmAction({
                title: 'Reject Review?',
                message: 'This review will be marked as rejected.',
                confirmLabel: 'Reject',
                confirmClass: 'btn-danger',
                onConfirm: async () => {
                    try {
                        const res = await fetch(rejectBtn.dataset.url, {
                            method: 'POST',
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': window.csrfToken, 'Accept': 'application/json' }
                        });
                        const data = await res.json().catch(() => ({}));
                        if (res.ok) { window.notify.success(data.message || 'Rejected.'); refreshReviews(data); window.closeDrawer(); }
                        else window.notify.error(data.message || 'Failed.');
                    } catch (err) { window.notify.error('Network error.'); }
                },
            });
            return;
        }
    });

    /* ---------------- Review-request project picker (delegated, drawer-safe) ---------------- */
    // Paint a project card selected / unselected (state classes come from the
    // card's data-* attributes so Tailwind compiles them from Blade).
    const paintPick = (btn, on) => {
        const onCls = (btn.dataset.pickOn || '').split(' ').filter(Boolean);
        const offCls = (btn.dataset.pickOff || '').split(' ').filter(Boolean);
        btn.classList.remove(...(on ? offCls : onCls));
        btn.classList.add(...(on ? onCls : offCls));
        const circle = btn.querySelector('[data-pick-circle]');
        if (circle) {
            const cOn = (btn.dataset.circleOn || '').split(' ').filter(Boolean);
            const cOff = (btn.dataset.circleOff || '').split(' ').filter(Boolean);
            circle.classList.remove(...(on ? cOff : cOn));
            circle.classList.add(...(on ? cOn : cOff));
        }
        const check = btn.querySelector('[data-pick-check]');
        if (check) {
            check.classList.toggle('opacity-100', on);
            check.classList.toggle('scale-100', on);
            check.classList.toggle('opacity-0', !on);
            check.classList.toggle('scale-75', !on);
        }
        btn.setAttribute('aria-pressed', on ? 'true' : 'false');
    };
    const clearPickError = (form) => {
        form.querySelector('[data-project-error]')?.classList.add('hidden');
        const list = form.querySelector('[data-project-list]');
        if (list) list.classList.remove(...(list.dataset.errorRing || '').split(' ').filter(Boolean));
    };
    // Select a project card.
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-project-pick]');
        if (!btn) return;
        const form = btn.closest('form[data-project-picker]');
        if (!form) return;
        form.querySelector('[data-project-value]').value = btn.dataset.projectPick;
        form.querySelectorAll('[data-project-pick]').forEach((b) => paintPick(b, b === btn));
        clearPickError(form);
    });
    // Debounced AJAX search of delivered projects.
    let projectSearchT;
    const runProjectSearch = async (input) => {
        const form = input.closest('form[data-project-picker]');
        const list = form?.querySelector('[data-project-list]');
        if (!list) return;
        const url = new URL(input.dataset.searchUrl, window.location.origin);
        url.searchParams.set('search', input.value.trim());
        url.searchParams.set('partial', 'list');
        list.innerHTML = '<div class="p-6 text-center text-sm text-slate-400 dark:text-slate-500">Searching…</div>';
        try {
            const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            list.innerHTML = await res.text();
            // Re-apply the current selection if it's still in the results.
            const val = form.querySelector('[data-project-value]')?.value;
            list.querySelectorAll('[data-project-pick]').forEach((b) => paintPick(b, !!val && b.dataset.projectPick === val));
            const count = form.querySelector('[data-project-count]');
            if (count) count.textContent = list.querySelectorAll('[data-project-pick]').length;
        } catch (err) {
            list.innerHTML = '<div class="p-6 text-center text-sm text-red-500">Search failed. Try again.</div>';
        }
    };
    document.addEventListener('input', (e) => {
        const input = e.target.closest('[data-project-search]');
        if (!input) return;
        input.closest('form[data-project-picker]')
            ?.querySelector('[data-project-search-clear]')
            ?.classList.toggle('hidden', !input.value);
        clearTimeout(projectSearchT);
        projectSearchT = setTimeout(() => runProjectSearch(input), 300);
    });
    // Clear search button.
    document.addEventListener('click', (e) => {
        if (!e.target.closest('[data-project-search-clear]')) return;
        const form = e.target.closest('form[data-project-picker]');
        const input = form?.querySelector('[data-project-search]');
        if (!input) return;
        input.value = '';
        e.target.closest('[data-project-search-clear]').classList.add('hidden');
        runProjectSearch(input);
        input.focus();
    });
    // Frontend "required" validation — capture phase so it runs before the
    // generic data-ajax-form submit handler.
    document.addEventListener('submit', (e) => {
        const form = e.target.closest?.('form[data-project-picker]');
        if (!form) return;
        if (form.querySelector('[data-project-value]')?.value) return; // valid
        e.preventDefault();
        e.stopPropagation();
        form.querySelector('[data-project-error]')?.classList.remove('hidden');
        const list = form.querySelector('[data-project-list]');
        if (list) list.classList.add(...(list.dataset.errorRing || '').split(' ').filter(Boolean));
        list?.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
        if (window.notify) window.notify.error('Please select a project first.');
    }, true);

    /* ---------------- Review edit form widgets (delegated, drawer-safe) ---------------- */
    // Star rating picker.
    const rateLabels = { 1: 'Poor', 2: 'Fair', 3: 'Good', 4: 'Very Good', 5: 'Excellent' };
    const paintRateStars = (wrap, n) => {
        wrap.querySelectorAll('[data-rate-star]').forEach((s) => {
            const svg = s.querySelector('svg');
            const active = parseInt(s.dataset.rateStar, 10) <= n;
            svg.classList.toggle('text-amber-400', active);
            svg.classList.toggle('text-slate-300', !active);
            svg.classList.toggle('dark:text-slate-600', !active);
        });
        const label = wrap.closest('form')?.querySelector('[data-rating-label]');
        if (label) label.textContent = `${rateLabels[n]} — ${n} star${n > 1 ? 's' : ''}`;
    };
    document.addEventListener('click', (e) => {
        const star = e.target.closest('[data-rate-star]');
        if (!star) return;
        const wrap = star.closest('[data-star-picker]');
        if (!wrap) return;
        const n = parseInt(star.dataset.rateStar, 10);
        const form = star.closest('form');
        if (form) form.querySelector('[data-rating-value]').value = n;
        paintRateStars(wrap, n);
    });
    // Status segmented pills.
    document.addEventListener('click', (e) => {
        const pill = e.target.closest('[data-status-pill]');
        if (!pill) return;
        const group = pill.closest('[data-status-pills]');
        const form = pill.closest('form');
        if (form) form.querySelector('[data-status-value]').value = pill.dataset.statusPill;
        group.querySelectorAll('[data-status-pill]').forEach((p) => {
            const on = p === pill;
            const onCls = (p.dataset.pillOn || '').split(' ').filter(Boolean);
            const offCls = (p.dataset.pillOff || '').split(' ').filter(Boolean);
            p.classList.remove(...(on ? offCls : onCls));
            p.classList.add(...(on ? onCls : offCls));
        });
    });
    // Review character counter.
    document.addEventListener('input', (e) => {
        const ta = e.target.closest('[data-review-text]');
        if (!ta) return;
        const count = ta.closest('form')?.querySelector('[data-review-count]');
        if (count) count.textContent = ta.value.length;
    });

    /* ---------------- Project updates (delegated, drawer-safe) ---------------- */
    // Toggle add-update form with icon swap.
    document.addEventListener('click', (e) => {
        const toggle = e.target.closest('[data-toggle-update-form]');
        if (!toggle) return;
        const form = document.getElementById('add-update-form');
        if (!form) return;
        const isHidden = form.classList.toggle('hidden');
        const plusIcon = toggle.querySelector('[data-toggle-icon-plus]');
        const minusIcon = toggle.querySelector('[data-toggle-icon-minus]');
        const label = toggle.querySelector('[data-toggle-label]');
        if (plusIcon) plusIcon.classList.toggle('hidden', !isHidden);
        if (minusIcon) minusIcon.classList.toggle('hidden', isHidden);
        if (label) label.textContent = isHidden ? 'Add Update' : 'Close';
    });

    // Submit add-update form via AJAX (no refresh).
    document.addEventListener('submit', async (e) => {
        const form = e.target.closest('#add-update-form');
        if (!form) return;
        e.preventDefault();

        const titleInput = form.querySelector('[name="title"]');
        if (!titleInput.value.trim()) {
            window.notify.error('Please enter an update title.');
            titleInput.focus();
            return;
        }

        const btn = form.querySelector('[type="submit"]');
        btn.disabled = true;
        const originalText = btn.textContent;
        btn.textContent = 'Adding...';

        try {
            const res = await fetch(form.dataset.updateUrl, {
                method: 'POST',
                body: new FormData(form),
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            const data = await res.json().catch(() => ({}));
            if (res.ok) {
                window.notify.success(data.message || 'Update added!');
                // Reload just the drawer content (no page refresh).
                reloadInvoiceDrawer();
            } else if (res.status === 422 && data.errors) {
                const firstError = Object.values(data.errors)[0]?.[0];
                window.notify.error(firstError || 'Please check your input.');
            } else {
                window.notify.error(data.message || 'Failed to add update.');
            }
        } catch (err) {
            window.notify.error('Network error.');
        } finally {
            btn.disabled = false;
            btn.textContent = originalText;
        }
    });

    // Delete a project update.
    document.addEventListener('click', async (e) => {
        const delBtn = e.target.closest('[data-delete-update]');
        if (!delBtn) return;
        window.confirmAction({
            title: 'Delete Update?',
            message: 'This update will be permanently removed.',
            confirmLabel: 'Delete',
            confirmClass: 'btn-danger',
            onConfirm: async () => {
                try {
                    const res = await fetch(delBtn.dataset.deleteUpdate, {
                        method: 'DELETE',
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': window.csrfToken, 'Accept': 'application/json' }
                    });
                    const data = await res.json().catch(() => ({}));
                    if (res.ok) {
                        window.notify.success(data.message || 'Deleted!');
                        delBtn.closest('.group')?.remove();
                    } else {
                        window.notify.error(data.message || 'Failed.');
                    }
                } catch (err) {
                    window.notify.error('Network error.');
                }
            },
        });
    });

    // Close on Escape.
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            document.querySelectorAll('[data-search-select-menu]').forEach(m => m.classList.add('hidden'));
        }
    });

    /* ---------------- Progress slider (project form) ---------------- */
    function progressColor(p) {
        p = parseInt(p, 10);
        if (p >= 75) return '#16a34a'; // green
        if (p >= 50) return '#1d4ed8'; // blue
        if (p >= 25) return '#f59e0b'; // amber
        return '#dc2626'; // red
    }
    function paintSliderFill(slider) {
        const pct = parseInt(slider.value, 10);
        const color = progressColor(pct);
        slider.style.background = `linear-gradient(to right, ${color} 0%, ${color} ${pct}%, #e2e8f0 ${pct}%, #e2e8f0 100%)`;
        slider.style.color = color;
    }
    function paintProgressBar(bar, pct) {
        pct = parseInt(pct, 10);
        bar.style.width = pct + '%';
        bar.style.backgroundColor = progressColor(pct);
    }
    document.addEventListener('input', (e) => {
        const slider = e.target.closest('[data-progress-slider]');
        if (!slider) return;
        paintSliderFill(slider);
        const label = slider.parentElement.querySelector('[data-progress-value]');
        if (label) {
            label.textContent = slider.value + '%';
            label.style.color = progressColor(slider.value);
        }
    });
    // Paint listing progress bars + slider fills on load.
    function paintAllProgress() {
        document.querySelectorAll('[data-progress-bar]').forEach(bar => {
            paintProgressBar(bar, bar.dataset.progressBar);
        });
        document.querySelectorAll('[data-progress-slider]').forEach(paintSliderFill);
    }
    document.addEventListener('DOMContentLoaded', paintAllProgress);
    // Paint when drawer content loads (AJAX).
    document.addEventListener('drawerOpened', () => setTimeout(paintAllProgress, 100));
    // Paint after table rows load (AJAX).
    document.addEventListener('tableRowsLoaded', () => setTimeout(paintAllProgress, 100));
    // Re-paint after table AJAX reloads.
    const _origReload = window.reloadAdminTable;
    window.reloadAdminTable = function (id) {
        _origReload(id);
        setTimeout(paintAllProgress, 500);
    };
    document.addEventListener('click', (e) => {
        const addBtn = e.target.closest('[data-add-milestone]');
        if (addBtn) {
            const list = document.getElementById('milestones-list');
            if (!list) return;
            let idx = parseInt(list.dataset.milestoneIndex || '0', 10);
            const div = document.createElement('div');
            div.className = 'milestone-row';
            div.innerHTML =
                '<input type="text" name="milestones[' + idx + '][title]" placeholder="Milestone title" class="ui-control milestone-title">' +
                '<input type="number" name="milestones[' + idx + '][amount]" placeholder="$ Amount" step="0.01" min="0" class="ui-control milestone-amount">' +
                '<button type="button" data-remove-milestone class="btn btn-secondary milestone-remove" title="Remove">×</button>';
            list.appendChild(div);
            list.dataset.milestoneIndex = String(idx + 1);
            return;
        }
        const removeBtn = e.target.closest('[data-remove-milestone]');
        if (removeBtn) {
            removeBtn.closest('.milestone-row')?.remove();
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

    /* ---------------- Generic confirm-action modal ---------------- */
    let pendingAction = null;
    window.confirmAction = function (opts) {
        // opts: { title, message, confirmLabel, confirmClass, onConfirm }
        pendingAction = opts;
        document.getElementById('confirm-action-title').textContent = opts.title || 'Are you sure?';
        document.getElementById('confirm-action-message').textContent = opts.message || '';
        const btn = document.getElementById('confirm-action-btn');
        btn.textContent = opts.confirmLabel || 'Confirm';
        btn.className = 'btn ' + (opts.confirmClass || 'btn-primary');
        document.getElementById('confirm-action-modal').classList.remove('hidden');
    };
    window.closeConfirmAction = function () {
        pendingAction = null;
        document.getElementById('confirm-action-modal').classList.add('hidden');
    };
    window.doConfirmAction = async function () {
        const action = pendingAction;
        window.closeConfirmAction();
        if (action && typeof action.onConfirm === 'function') {
            await action.onConfirm();
        }
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
            document.dispatchEvent(new CustomEvent('rowDeleted', { detail: data }));
        } catch (err) {
            window.notify.error(err.message);
        }
    };
})();

// Bell polling — refresh unread count every 30s without a page reload.
(function () {
    async function poll() {
        try {
            const res = await fetch('/admin/notifications/unread-count', { headers: { 'Accept': 'application/json' } });
            if (!res.ok) return;
            const { count } = await res.json();
            const el = document.querySelector('.bell-dot');
            if (count > 0) {
                if (el) {
                    el.textContent = count > 9 ? '9+' : count;
                } else {
                    const btn = document.querySelector('[aria-label="Notifications"]');
                    if (btn) {
                        const span = document.createElement('span');
                        span.className = 'bell-dot absolute -top-1.5 -right-1.5 min-w-[1.25rem] h-5 px-1 rounded-full bg-red-500 text-white text-[0.65rem] font-bold flex items-center justify-center border-2 border-white';
                        span.textContent = count > 9 ? '9+' : count;
                        btn.appendChild(span);
                    }
                }
            } else if (el) {
                el.remove();
            }
        } catch (e) { /* silent */ }
    }
    setInterval(poll, 30000);
})();

// Global notification helper (used by the topbar bell dropdown's
// "Mark all read" button) — branded confirmation, never native confirm().
window.markAllNotificationsRead = function () {
    window.confirmAction({
        title: 'Mark all as read?',
        message: 'All notifications will be marked as read.',
        confirmLabel: 'Mark all read',
        onConfirm: () => {
            fetch('/admin/notifications/read-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            }).then(() => {
                document.querySelector('.bell-dot')?.remove();
                document.getElementById('topbar-notifications-list')?.querySelectorAll('a').forEach((a) => {
                    a.querySelector('.bg-\\[\\#1d4ed8\\]')?.classList.replace('bg-[#1d4ed8]', 'bg-slate-300');
                });
                window.notify?.success('All notifications marked as read.');
            });
        },
    });
};

// Toggle service active/inactive from the listing.
window.toggleServiceStatus = function (url, btn) {
    btn.disabled = true;
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
    })
        .then((res) => res.json())
        .then((data) => {
            const active = !!data.status;
            btn.classList.toggle('badge-green', active);
            btn.classList.toggle('badge-red', !active);
            btn.textContent = active ? 'Active' : 'Inactive';
            btn.title = active ? 'Click to deactivate' : 'Click to activate';
            window.notify?.success(data.message);
        })
        .catch(() => window.notify?.error('Failed to update status.'))
        .finally(() => { btn.disabled = false; });
};

// Auto-generate slug from title/name (services, categories, blog, etc.).
// Only fills the slug if the user hasn't manually edited it.
function slugify(text) {
    return text.toLowerCase().trim()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/[\s_]+/g, '-')
        .replace(/-+/g, '-')
        .replace(/^-|-$/g, '');
}
document.addEventListener('input', function (e) {
    if (e.target.name !== 'title' && e.target.name !== 'name') return;
    const form = e.target.closest('form');
    if (!form) return;
    const slugInput = form.querySelector('[name="slug"]');
    if (!slugInput || slugInput.dataset.manual === '1') return;
    slugInput.value = slugify(e.target.value);
});
document.addEventListener('input', function (e) {
    if (e.target.name === 'slug') e.target.dataset.manual = '1';
});

// Toggle service category active/inactive from the listing.
window.toggleCategoryStatus = function (url, btn) {
    btn.disabled = true;
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
    })
        .then((res) => res.json())
        .then((data) => {
            const active = !!data.status;
            btn.classList.toggle('badge-green', active);
            btn.classList.toggle('badge-red', !active);
            btn.textContent = active ? 'Active' : 'Inactive';
            btn.title = active ? 'Click to deactivate' : 'Click to activate';
            window.notify?.success(data.message);
        })
        .catch(() => window.notify?.error('Failed to update status.'))
        .finally(() => { btn.disabled = false; });
};

// Toggle blog category active/inactive from the listing.
window.toggleBlogCatStatus = function (url, btn) {
    btn.disabled = true;
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
    })
        .then((res) => res.json())
        .then((data) => {
            const active = !!data.status;
            btn.classList.toggle('badge-green', active);
            btn.classList.toggle('badge-red', !active);
            btn.textContent = active ? 'Active' : 'Inactive';
            btn.title = active ? 'Click to deactivate' : 'Click to activate';
            window.notify?.success(data.message);
        })
        .catch(() => window.notify?.error('Failed to update status.'))
        .finally(() => { btn.disabled = false; });
};

// Toggle blog post active/inactive from the listing.
window.togglePostStatus = function (url, btn) {
    btn.disabled = true;
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
    })
        .then((res) => res.json())
        .then((data) => {
            const active = !!data.status;
            btn.classList.toggle('badge-green', active);
            btn.classList.toggle('badge-red', !active);
            btn.textContent = active ? 'Active' : 'Inactive';
            btn.title = active ? 'Click to deactivate' : 'Click to activate';
            window.notify?.success(data.message);
        })
        .catch(() => window.notify?.error('Failed to update status.'))
        .finally(() => { btn.disabled = false; });
};

// Toggle FAQ active/inactive from the listing.
window.toggleFaqStatus = function (url, btn) {
    btn.disabled = true;
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
    })
        .then((res) => res.json())
        .then((data) => {
            const active = !!data.status;
            btn.classList.toggle('badge-green', active);
            btn.classList.toggle('badge-red', !active);
            btn.textContent = active ? 'Active' : 'Inactive';
            btn.title = active ? 'Click to deactivate' : 'Click to activate';
            window.notify?.success(data.message);
        })
        .catch(() => window.notify?.error('Failed to update status.'))
        .finally(() => { btn.disabled = false; });
};

// Toggle testimonial active/inactive from the listing.
window.toggleTestimonialStatus = function (url, btn) {
    btn.disabled = true;
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
    })
        .then((res) => res.json())
        .then((data) => {
            const active = !!data.status;
            btn.classList.toggle('badge-green', active);
            btn.classList.toggle('badge-red', !active);
            btn.textContent = active ? 'Active' : 'Inactive';
            btn.title = active ? 'Click to deactivate' : 'Click to activate';
            window.notify?.success(data.message);
        })
        .catch(() => window.notify?.error('Failed to update status.'))
        .finally(() => { btn.disabled = false; });
};

// Toggle project active/inactive from the listing.
window.toggleProjectStatus = function (url, btn) {
    btn.disabled = true;
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
    })
        .then((res) => res.json())
        .then((data) => {
            const active = !!data.status;
            btn.classList.toggle('badge-green', active);
            btn.classList.toggle('badge-red', !active);
            btn.textContent = active ? 'Active' : 'Inactive';
            btn.title = active ? 'Click to deactivate' : 'Click to activate';
            window.notify?.success(data.message);
        })
        .catch(() => window.notify?.error('Failed to update status.'))
        .finally(() => { btn.disabled = false; });
};

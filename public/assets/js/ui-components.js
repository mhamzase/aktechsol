/* ==========================================================================
   AK Tech Sol — shared UI components JS (x-ui.*).
   Vanilla JS, zero dependencies. Works for static pages AND content
   injected later (AJAX drawers) via MutationObserver.
   Exposes: window.uiInit(scope) — initialize components inside `scope`.
   ========================================================================== */
(function () {
    'use strict';

    const EYE = '<svg class="w-[1.05rem] h-[1.05rem]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>';
    const EYE_OFF = '<svg class="w-[1.05rem] h-[1.05rem]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>';

    /* ---------------- Password show/hide (delegated) ---------------- */
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-ui-password-toggle]');
        if (!btn) return;
        const input = btn.closest('.ui-input-wrap')?.querySelector('input');
        if (!input) return;
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.innerHTML = show ? EYE_OFF : EYE;
        btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
    });

    /* ---------------- Multi-select ---------------- */
    function initMultiSelect(root) {
        if (root.dataset.uiInit) return;
        root.dataset.uiInit = '1';

        const name = root.dataset.name;
        const options = JSON.parse(root.dataset.options || '[]');
        let selected = JSON.parse(root.dataset.selected || '[]').map(String);
        const placeholder = root.dataset.placeholder || 'Select…';
        const searchable = root.dataset.searchable !== '0';

        const btn = root.querySelector('.ui-ms-btn');
        const drop = root.querySelector('.ui-ms-drop');
        const list = root.querySelector('.ui-ms-list');
        const search = root.querySelector('.ui-ms-search');
        const hidden = root.querySelector('.ui-ms-hidden');

        function syncHidden() {
            hidden.innerHTML = '';
            selected.forEach((v) => {
                const i = document.createElement('input');
                i.type = 'hidden'; i.name = name; i.value = v;
                hidden.appendChild(i);
            });
        }

        function renderTags() {
            btn.querySelectorAll('.ui-ms-tag, .ui-ms-placeholder').forEach((n) => n.remove());
            if (!selected.length) {
                const p = document.createElement('span');
                p.className = 'ui-ms-placeholder'; p.textContent = placeholder;
                btn.prepend(p);
            } else {
                const frag = document.createDocumentFragment();
                selected.forEach((v) => {
                    const opt = options.find((o) => String(o.value) === v);
                    const tag = document.createElement('span');
                    tag.className = 'ui-ms-tag';
                    tag.innerHTML = `<span></span><button type="button" aria-label="Remove"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg></button>`;
                    tag.firstElementChild.textContent = opt ? opt.label : v;
                    tag.querySelector('button').addEventListener('click', (ev) => {
                        ev.stopPropagation();
                        selected = selected.filter((s) => s !== v);
                        renderAll();
                    });
                    frag.appendChild(tag);
                });
                btn.prepend(frag);
            }
        }

        function renderList(filter = '') {
            list.innerHTML = '';
            const q = filter.trim().toLowerCase();
            const visible = options.filter((o) => !q || o.label.toLowerCase().includes(q));
            if (!visible.length) {
                list.innerHTML = '<div class="ui-ms-empty">No options found.</div>';
                return;
            }
            visible.forEach((o) => {
                const val = String(o.value);
                const row = document.createElement('div');
                row.className = 'ui-ms-opt' + (selected.includes(val) ? ' ui-selected' : '');
                row.setAttribute('role', 'option');
                const cb = document.createElement('span');
                cb.innerHTML = selected.includes(val)
                    ? '<svg class="w-4 h-4 text-[#1d4ed8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>'
                    : '<span class="inline-block w-4 h-4 rounded border-2 border-slate-300"></span>';
                const lab = document.createElement('span'); lab.textContent = o.label;
                row.append(cb, lab);
                row.addEventListener('click', () => {
                    selected = selected.includes(val)
                        ? selected.filter((s) => s !== val)
                        : [...selected, val];
                    renderAll(search.value);
                });
                list.appendChild(row);
            });
        }

        function renderAll(filter = '') { renderTags(); renderList(filter); syncHidden(); }

        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const open = !drop.classList.contains('hidden');
            closeAllMultiSelects();
            if (!open) { drop.classList.remove('hidden'); search?.focus(); }
        });
        search?.addEventListener('input', () => renderList(search.value));
        search?.addEventListener('click', (e) => e.stopPropagation());
        root._close = () => drop.classList.add('hidden');

        renderAll();
    }

    function closeAllMultiSelects() {
        document.querySelectorAll('.ui-ms[data-ui-init]').forEach((r) => r._close && r._close());
    }
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.ui-ms')) closeAllMultiSelects();
        if (!e.target.closest('.ui-date')) closeAllCalendars();
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') { closeAllMultiSelects(); closeAllCalendars(); }
    });

    /* ---------------- Date picker (custom calendar) ---------------- */
    const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    const DOW = ['Su','Mo','Tu','We','Th','Fr','Sa'];

    function fmtDisplay(y, m, d) {
        const mon = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'][m - 1];
        return `${d} ${mon} ${y}`;
    }
    function toISO(y, m, d) {
        return `${y}-${String(m).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
    }
    function parseISO(v) {
        const m = /^(\d{4})-(\d{2})-(\d{2})$/.exec(v || '');
        return m ? { y: +m[1], m: +m[2], d: +m[3] } : null;
    }

    function initDatePicker(root) {
        if (root.dataset.uiInit) return;
        root.dataset.uiInit = '1';

        const display = root.querySelector('.ui-date-display');
        const hidden = root.querySelector('.ui-date-hidden');
        const cal = root.querySelector('.ui-cal');
        const min = root.dataset.min || null;
        const max = root.dataset.max || null;

        let sel = parseISO(hidden.value);
        const today = new Date();
        let vy = sel ? sel.y : today.getFullYear();
        let vm = sel ? sel.m : today.getMonth() + 1;

        function inRange(iso) {
            return (!min || iso >= min) && (!max || iso <= max);
        }

        function render() {
            const title = cal.querySelector('.ui-cal-title');
            title.innerHTML = '';
            const mSel = document.createElement('select');
            mSel.setAttribute('aria-label', 'Month');
            MONTHS.forEach((name, i) => {
                const o = document.createElement('option');
                o.value = i + 1; o.textContent = name.slice(0, 3);
                if (i + 1 === vm) o.selected = true;
                mSel.appendChild(o);
            });
            mSel.addEventListener('change', () => { vm = +mSel.value; render(); });
            const ySel = document.createElement('select');
            ySel.setAttribute('aria-label', 'Year');
            const cy = today.getFullYear();
            for (let y = cy - 100; y <= cy + 20; y++) {
                const o = document.createElement('option');
                o.value = y; o.textContent = y;
                if (y === vy) o.selected = true;
                ySel.appendChild(o);
            }
            ySel.addEventListener('change', () => { vy = +ySel.value; render(); });
            title.append(mSel, ySel);

            const grid = cal.querySelector('.ui-cal-grid');
            grid.innerHTML = '';
            DOW.forEach((d) => {
                const el = document.createElement('div');
                el.className = 'ui-cal-dow'; el.textContent = d;
                grid.appendChild(el);
            });

            const first = new Date(vy, vm - 1, 1).getDay();
            const days = new Date(vy, vm, 0).getDate();
            const prevDays = new Date(vy, vm - 1, 0).getDate();
            const cells = [];
            for (let i = first - 1; i >= 0; i--) cells.push({ d: prevDays - i, out: true, m: vm - 1, y: vm === 1 ? vy - 1 : vy });
            for (let d = 1; d <= days; d++) cells.push({ d, out: false, m: vm, y: vy });
            while (cells.length % 7) {
                const n = cells.length - first - days + 1;
                cells.push({ d: n, out: true, m: vm + 1 > 12 ? 1 : vm + 1, y: vm === 12 ? vy + 1 : vy });
            }
            const tISO = toISO(today.getFullYear(), today.getMonth() + 1, today.getDate());
            cells.forEach((c) => {
                const b = document.createElement('button');
                b.type = 'button';
                const iso = toISO(c.y, c.m, c.d);
                b.className = 'ui-cal-day' + (c.out ? ' ui-out' : '') + (iso === tISO ? ' ui-today' : '')
                    + (sel && iso === toISO(sel.y, sel.m, sel.d) ? ' ui-selected' : '');
                b.textContent = c.d;
                if (!inRange(iso)) b.disabled = true;
                b.addEventListener('click', () => {
                    sel = { y: c.y, m: c.m, d: c.d };
                    hidden.value = iso;
                    display.value = fmtDisplay(c.y, c.m, c.d);
                    hidden.dispatchEvent(new Event('change', { bubbles: true }));
                    closeCal();
                });
                grid.appendChild(b);
            });
        }

        function openCal() {
            closeAllCalendars();
            render();
            cal.classList.remove('hidden');
        }
        function closeCal() { cal.classList.add('hidden'); }
        root._closeCal = closeCal;

        display.addEventListener('click', (e) => {
            e.stopPropagation();
            cal.classList.contains('hidden') ? openCal() : closeCal();
        });
        cal.addEventListener('click', (e) => e.stopPropagation());
        cal.querySelector('.ui-cal-prev').addEventListener('click', () => {
            vm--; if (vm < 1) { vm = 12; vy--; } render();
        });
        cal.querySelector('.ui-cal-next').addEventListener('click', () => {
            vm++; if (vm > 12) { vm = 1; vy++; } render();
        });
        cal.querySelector('.ui-cal-today').addEventListener('click', () => {
            const iso = toISO(today.getFullYear(), today.getMonth() + 1, today.getDate());
            if (!inRange(iso)) return;
            sel = { y: today.getFullYear(), m: today.getMonth() + 1, d: today.getDate() };
            hidden.value = iso;
            display.value = fmtDisplay(sel.y, sel.m, sel.d);
            hidden.dispatchEvent(new Event('change', { bubbles: true }));
            closeCal();
        });
        cal.querySelector('.ui-cal-clear').addEventListener('click', () => {
            sel = null; hidden.value = ''; display.value = '';
            hidden.dispatchEvent(new Event('change', { bubbles: true }));
            closeCal();
        });

        // Range linking: this picker constrains another (bound once).
        const linkMin = root.dataset.linkMin; // id of the "end" picker
        const linkMax = root.dataset.linkMax; // id of the "start" picker
        if (!root.dataset.linkBound && (linkMin || linkMax)) {
            root.dataset.linkBound = '1';
            if (linkMin) hidden.addEventListener('change', () => {
                const other = document.getElementById(linkMin);
                if (other) { other.dataset.min = hidden.value || ''; other.dataset.uiInit = ''; initDatePicker(other); }
            });
            if (linkMax) hidden.addEventListener('change', () => {
                const other = document.getElementById(linkMax);
                if (other) { other.dataset.max = hidden.value || ''; other.dataset.uiInit = ''; initDatePicker(other); }
            });
        }
    }

    function closeAllCalendars() {
        document.querySelectorAll('.ui-date[data-ui-init]').forEach((r) => r._closeCal && r._closeCal());
    }

    /* ---------------- Rich text (Quill, local) ---------------- */
    function initRichText(root) {
        if (root.dataset.uiInit || typeof Quill === 'undefined') return;
        root.dataset.uiInit = '1';
        const editor = root.querySelector('[data-quill-editor]');
        const input = root.querySelector('[data-quill-input]');
        const quill = new Quill(editor, {
            theme: 'snow',
            placeholder: root.dataset.placeholder || 'Write something…',
            modules: {
                toolbar: [
                    [{ header: [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    [{ align: [] }],
                    ['link'],
                    ['clean'],
                ],
            },
        });
        if (input.value) quill.root.innerHTML = input.value;
        quill.on('text-change', () => {
            input.value = quill.root.innerHTML === '<p><br></p>' ? '' : quill.root.innerHTML;
        });
        const form = root.closest('form');
        if (form && !form.dataset.quillBound) {
            form.dataset.quillBound = '1';
            form.addEventListener('submit', () => {
                form.querySelectorAll('.ui-rich[data-ui-init]').forEach((r) => {
                    const q = Quill.find(r.querySelector('[data-quill-editor]'));
                    const inp = r.querySelector('[data-quill-input]');
                    if (q && inp) inp.value = q.root.innerHTML === '<p><br></p>' ? '' : q.root.innerHTML;
                });
            });
        }
    }

    /* ---------------- File input preview ---------------- */
    function initFile(root) {
        if (root.dataset.uiInit) return;
        root.dataset.uiInit = '1';
        const input = root.querySelector('input[type="file"]');
        const preview = root.querySelector('.ui-file-preview');
        input.addEventListener('change', () => {
            preview.innerHTML = '';
            Array.from(input.files || []).slice(0, 6).forEach((f) => {
                if (f.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(f); img.alt = f.name;
                    preview.appendChild(img);
                } else {
                    const chip = document.createElement('span');
                    chip.className = 'ui-file-chip'; chip.textContent = f.name;
                    preview.appendChild(chip);
                }
            });
        });
    }

    /* ---------------- Public init ---------------- */
    window.uiInit = function (scope) {
        scope = scope || document;
        scope.querySelectorAll('.ui-ms').forEach(initMultiSelect);
        scope.querySelectorAll('.ui-date').forEach(initDatePicker);
        scope.querySelectorAll('.ui-rich').forEach(initRichText);
        scope.querySelectorAll('.ui-file').forEach(initFile);
    };

    document.addEventListener('DOMContentLoaded', () => window.uiInit(document));

    // Auto-init components injected later (AJAX drawers, dynamic forms).
    new MutationObserver((mutations) => {
        mutations.forEach((m) => {
            m.addedNodes.forEach((n) => {
                if (n.nodeType !== 1) return;
                if (n.matches && (n.matches('.ui-ms,.ui-date,.ui-rich,.ui-file') || n.querySelector('.ui-ms,.ui-date,.ui-rich,.ui-file'))) {
                    window.uiInit(n);
                }
            });
        });
    }).observe(document.body, { childList: true, subtree: true });
})();

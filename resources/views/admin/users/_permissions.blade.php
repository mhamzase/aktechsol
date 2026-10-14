{{-- Drawer: user-wise permission checklist. Submitted via AJAX (data-ajax-form). --}}
<form data-ajax-form method="POST" action="{{ $action }}" novalidate>
    @csrf
    @method('PUT')

    <div class="space-y-4">
        {{-- User card --}}
        <div class="flex items-center gap-3 rounded-2xl bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 px-4 py-3.5 dark:from-blue-500/10 dark:to-indigo-500/10 dark:border-blue-500/20">
            <span class="w-11 h-11 rounded-full bg-gradient-to-br from-[#1669d9] to-[#0d3a7a] text-white font-bold flex items-center justify-center shrink-0 text-lg shadow-md">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </span>
            <div class="min-w-0">
                <p class="font-bold text-slate-800 dark:text-slate-100 truncate">{{ $user->name }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $user->email }}</p>
            </div>
            <span class="ml-auto badge badge-blue shrink-0">Team Member</span>
        </div>

        <div class="rounded-xl bg-slate-50 border border-slate-200 px-4 py-3 dark:bg-white/5 dark:border-white/10">
            <p class="text-xs leading-relaxed text-slate-500 dark:text-slate-400">
                Tick the modules this member may manage. Permissions are stored
                <b class="text-slate-700 dark:text-slate-200">directly on this user</b> —
                the Team Member role itself stays permission-free. Each chip shows which sidebar tab the permission unlocks.
            </p>
        </div>

        <div class="flex items-center justify-between">
            <p class="text-sm font-bold text-slate-700 dark:text-slate-200">
                <span data-perm-total>0</span> of {{ count($sidebarMap) }} permissions granted
            </p>
            <button type="button" data-perm-toggle class="text-xs font-bold text-[#1669d9] hover:underline dark:text-blue-400">Select all</button>
        </div>

        @foreach ($groups as $group => $perms)
            <div class="rounded-2xl border border-slate-200 dark:border-white/10 overflow-hidden bg-white dark:bg-white/[0.02]" data-perm-group>
                {{-- Group header --}}
                <div class="flex items-center gap-3 px-4 py-3 bg-slate-50/80 dark:bg-white/5 border-b border-slate-100 dark:border-white/10">
                    <span class="w-8 h-8 rounded-lg bg-[#1669d9]/10 text-[#1669d9] dark:text-blue-400 flex items-center justify-center shrink-0">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icons[$group] ?? '' }}"/></svg>
                    </span>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-slate-800 dark:text-slate-100">{{ $group }}</p>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500" data-perm-count></p>
                    </div>
                    <button type="button" data-perm-group-all class="ml-auto text-[11px] font-bold text-[#1669d9] hover:underline dark:text-blue-400 shrink-0">All</button>
                    <button type="button" data-perm-group-toggle class="shrink-0 w-7 h-7 rounded-lg hover:bg-slate-200/70 dark:hover:bg-white/10 flex items-center justify-center text-slate-400" aria-label="Collapse">
                        <svg class="transition-transform" data-perm-chevron width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                </div>
                {{-- Permission rows --}}
                <div class="divide-y divide-slate-100 dark:divide-white/5" data-perm-list>
                    @foreach ($perms as $perm)
                        <label class="perm-row flex items-center gap-3 px-4 py-2.5 cursor-pointer hover:bg-blue-50/50 dark:hover:bg-white/5 transition-colors">
                            <input type="checkbox" name="permissions[]" value="{{ $perm }}"
                                   @checked(in_array($perm, $direct))
                                   class="perm-check w-[18px] h-[18px] rounded-md accent-[#1669d9] shrink-0 cursor-pointer">
                            <span class="min-w-0 flex-1">
                                <span class="block text-sm font-semibold text-slate-700 dark:text-slate-200 truncate">{{ ucfirst($perm) }}</span>
                            </span>
                            <span class="shrink-0 inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wide text-slate-500 bg-slate-100 border border-slate-200 rounded-full px-2 py-0.5 dark:text-slate-400 dark:bg-white/5 dark:border-white/10">
                                <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
                                {{ $sidebarMap[$perm] ?? '—' }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach

        <x-admin.drawer-footer save-label="Save Permissions" />
    </div>
</form>

<script>
(function () {
    const form = document.currentScript.closest('form');
    if (!form || form.dataset.permInit) return;
    form.dataset.permInit = '1';

    const boxes = () => [...form.querySelectorAll('.perm-check')];
    const totalEl = form.querySelector('[data-perm-total]');
    const master = form.querySelector('[data-perm-toggle]');

    const refresh = () => {
        const all = boxes();
        const on = all.filter((b) => b.checked).length;
        totalEl.textContent = on;
        master.textContent = on === all.length ? 'Deselect all' : 'Select all';
        form.querySelectorAll('[data-perm-group]').forEach((g) => {
            const gb = [...g.querySelectorAll('.perm-check')];
            const gon = gb.filter((b) => b.checked).length;
            g.querySelector('[data-perm-count]').textContent = gon + ' / ' + gb.length + ' enabled';
            const allBtn = g.querySelector('[data-perm-group-all]');
            if (allBtn) allBtn.textContent = gon === gb.length ? 'None' : 'All';
        });
    };
    form.addEventListener('change', refresh);
    refresh();

    // Collapse / expand groups.
    form.querySelectorAll('[data-perm-group-toggle]').forEach((btn) => btn.addEventListener('click', () => {
        const list = btn.closest('[data-perm-group]').querySelector('[data-perm-list]');
        const chev = btn.querySelector('[data-perm-chevron]');
        list.classList.toggle('hidden');
        chev.style.transform = list.classList.contains('hidden') ? 'rotate(-90deg)' : '';
    }));

    // Per-group All / None.
    form.querySelectorAll('[data-perm-group-all]').forEach((btn) => btn.addEventListener('click', () => {
        const gb = [...btn.closest('[data-perm-group]').querySelectorAll('.perm-check')];
        const target = gb.some((b) => !b.checked);
        gb.forEach((b) => { b.checked = target; });
        refresh();
    }));

    // Master Select all / Deselect all.
    master.addEventListener('click', () => {
        const all = boxes();
        const target = all.some((b) => !b.checked);
        all.forEach((b) => { b.checked = target; });
        refresh();
    });
})();
</script>

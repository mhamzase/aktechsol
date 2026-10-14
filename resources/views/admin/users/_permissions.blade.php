{{-- Drawer: user-wise permission checklist. Submitted via AJAX (data-ajax-form). --}}
<form data-ajax-form method="POST" action="{{ $action }}" novalidate>
    @csrf
    @method('PUT')

    <div class="space-y-4">
        <div class="flex items-center gap-3 rounded-xl bg-blue-50 border border-blue-100 px-4 py-3 dark:bg-blue-500/10 dark:border-blue-500/20">
            <span class="w-9 h-9 rounded-full bg-gradient-to-br from-[#1669d9] to-[#0d3a7a] text-white text-sm font-bold flex items-center justify-center shrink-0">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </span>
            <div class="min-w-0">
                <p class="font-semibold text-slate-800 dark:text-slate-100 truncate">{{ $user->name }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $user->email }}</p>
            </div>
            <span class="ml-auto badge badge-blue shrink-0">Team Member</span>
        </div>

        <p class="text-xs text-slate-500 dark:text-slate-400">
            Tick the modules this member may manage. Stored <b>directly on this user</b> — the Team Member role itself stays permission-free.
        </p>

        <div class="flex justify-end">
            <button type="button" data-perm-toggle class="text-xs font-semibold text-[#1669d9] hover:underline">Select all</button>
        </div>

        @foreach ($groups as $group => $perms)
            <div class="rounded-xl border border-slate-200 dark:border-white/10 overflow-hidden" data-perm-group>
                <button type="button" data-perm-group-toggle
                        class="w-full flex items-center gap-2 px-4 py-2.5 bg-slate-50 dark:bg-white/5 hover:bg-slate-100 dark:hover:bg-white/10 transition-colors">
                    <span class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $group }}</span>
                    <span class="text-xs text-slate-400" data-perm-count></span>
                    <svg class="w-4 h-4 ml-auto text-slate-400 transition-transform" data-perm-chevron fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="p-3 grid sm:grid-cols-2 gap-1" data-perm-list>
                    @foreach ($perms as $perm)
                        <x-ui.checkbox name="permissions[]" :value="$perm"
                                       :label="ucfirst($perm)"
                                       :checked="in_array($perm, $direct)" />
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

    const boxes = () => [...form.querySelectorAll('input[name="permissions[]"]')];

    // Per-group selected counts.
    const refreshCounts = () => form.querySelectorAll('[data-perm-group]').forEach((g) => {
        const all = g.querySelectorAll('input[name="permissions[]"]');
        const on = [...all].filter((b) => b.checked).length;
        g.querySelector('[data-perm-count]').textContent = on + '/' + all.length + ' selected';
    });
    form.addEventListener('change', refreshCounts);
    refreshCounts();

    // Collapse / expand groups.
    form.querySelectorAll('[data-perm-group-toggle]').forEach((btn) => btn.addEventListener('click', () => {
        const list = btn.closest('[data-perm-group]').querySelector('[data-perm-list]');
        const chev = btn.querySelector('[data-perm-chevron]');
        list.classList.toggle('hidden');
        chev.style.transform = list.classList.contains('hidden') ? 'rotate(-90deg)' : '';
    }));

    // Select-all toggle.
    const master = form.querySelector('[data-perm-toggle]');
    master.addEventListener('click', () => {
        const all = boxes();
        const target = all.some((b) => !b.checked);
        all.forEach((b) => { b.checked = target; });
        master.textContent = target ? 'Deselect all' : 'Select all';
        refreshCounts();
    });
})();
</script>

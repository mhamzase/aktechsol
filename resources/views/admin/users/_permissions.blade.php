{{-- Drawer: user-wise permission checklist. Submitted via AJAX (data-ajax-form).
     Interactivity (counts, toggles, select-all) is delegated from admin.js
     via [data-perm-form] — inline scripts never run in AJAX drawers. --}}
<form data-ajax-form data-perm-form method="POST" action="{{ $action }}" novalidate>
    @csrf
    @method('PUT')

    <div class="space-y-4">
        {{-- User card --}}
        <div class="flex items-center gap-3 rounded-2xl bg-gradient-to-r from-[#1669d9] to-[#0d3a7a] px-4 py-3.5 shadow-md">
            <span class="w-11 h-11 rounded-full bg-white/20 border-2 border-white/40 text-white font-bold flex items-center justify-center shrink-0 text-lg">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </span>
            <div class="min-w-0">
                <p class="font-bold text-white truncate">{{ $user->name }}</p>
                <p class="text-xs text-blue-100/90 truncate">{{ $user->email }}</p>
            </div>
            <span class="ml-auto shrink-0 text-[11px] font-bold uppercase tracking-wide bg-white text-[#1669d9] rounded-full px-2.5 py-1">Team Member</span>
        </div>

        <div class="rounded-xl bg-amber-50 border border-amber-200 px-4 py-3 dark:bg-amber-500/10 dark:border-amber-500/25">
            <p class="text-xs leading-relaxed text-amber-800 dark:text-amber-200">
                Tick the modules this member may manage. Permissions are stored
                <b>directly on this user</b> — the Team Member role itself stays permission-free.
                Each chip shows which sidebar tab the permission unlocks.
            </p>
        </div>

        <div class="flex items-center justify-between px-1">
            <p class="text-sm font-extrabold text-slate-900 dark:text-white">
                <span data-perm-total class="text-[#1669d9] dark:text-blue-400">0</span>
                <span class="text-slate-500 dark:text-slate-400 font-semibold">of {{ count($sidebarMap) }} permissions granted</span>
            </p>
            <button type="button" data-perm-toggle class="text-xs font-extrabold uppercase tracking-wide text-[#1669d9] hover:underline dark:text-blue-400">Select all</button>
        </div>

        @foreach ($groups as $group => $perms)
            <div class="rounded-2xl border-2 border-slate-200 dark:border-white/15 overflow-hidden bg-white dark:bg-white/[0.03] shadow-sm" data-perm-group>
                {{-- Group header --}}
                <div class="flex items-center gap-3 px-4 py-3 bg-slate-100 dark:bg-white/10 border-b-2 border-slate-200 dark:border-white/15">
                    <span class="w-9 h-9 rounded-xl bg-[#1669d9] text-white flex items-center justify-center shrink-0 shadow">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icons[$group] ?? '' }}"/></svg>
                    </span>
                    <div class="min-w-0">
                        <p class="text-[15px] font-extrabold text-slate-900 dark:text-white leading-tight">{{ $group }}</p>
                        <p class="text-[11px] font-bold text-[#1669d9] dark:text-blue-400" data-perm-count></p>
                    </div>
                    <button type="button" data-perm-group-all class="ml-auto text-[11px] font-extrabold uppercase tracking-wide text-slate-500 hover:text-[#1669d9] dark:text-slate-400 dark:hover:text-blue-400 shrink-0 px-2 py-1 rounded-lg hover:bg-slate-200 dark:hover:bg-white/10">All</button>
                    <button type="button" data-perm-group-toggle class="shrink-0 w-8 h-8 rounded-lg hover:bg-slate-200 dark:hover:bg-white/10 flex items-center justify-center text-slate-500 dark:text-slate-300" aria-label="Collapse">
                        <svg class="transition-transform" data-perm-chevron width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                </div>
                {{-- Permission rows --}}
                <div class="divide-y divide-slate-100 dark:divide-white/10" data-perm-list>
                    @foreach ($perms as $perm)
                        <label class="flex items-center gap-3 px-4 py-3 cursor-pointer hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors">
                            <input type="checkbox" name="permissions[]" value="{{ $perm }}"
                                   @checked(in_array($perm, $direct))
                                   class="perm-check w-5 h-5 rounded-md accent-[#1669d9] shrink-0 cursor-pointer">
                            <span class="min-w-0 flex-1">
                                <span class="block text-sm font-bold text-slate-900 dark:text-white truncate">{{ ucfirst($perm) }}</span>
                                <span class="block text-[11px] text-slate-500 dark:text-slate-400 truncate">Unlocks sidebar tab</span>
                            </span>
                            <span class="shrink-0 inline-flex items-center gap-1.5 text-[10px] font-extrabold uppercase tracking-wider text-[#1669d9] bg-blue-50 border border-blue-200 rounded-full px-2.5 py-1 dark:text-blue-300 dark:bg-blue-500/15 dark:border-blue-500/30">
                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
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

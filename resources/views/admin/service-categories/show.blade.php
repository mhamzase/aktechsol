{{-- Service category details drawer --}}
<div class="drawer-content p-6">
    <div class="flex items-start gap-3 mb-5">
        <span class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#1d4ed8] to-[#12307e] text-white flex items-center justify-center font-bold text-lg shrink-0">
            {{ strtoupper(substr($category->name, 0, 1)) }}
        </span>
        <div class="min-w-0 flex-1">
            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">{{ $category->name }}</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400">/{{ $category->slug }}</p>
        </div>
        <span class="shrink-0 text-xs font-bold px-3 py-1 rounded-full {{ $category->status ? 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400' }}">
            {{ $category->status ? 'Active' : 'Inactive' }}
        </span>
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
            <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Sort Order</p>
            <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $category->sort_order }}</p>
        </div>
        <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
            <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Services</p>
            <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $category->services_count }}</p>
        </div>
    </div>
</div>

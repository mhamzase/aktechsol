{{-- Services section tabs: Services | Categories --}}
@php
    $serviceTab = $serviceTab ?? 'services';
@endphp
<div class="flex gap-1 mb-6 border-b border-slate-200 dark:border-slate-700">
    <a href="{{ route('admin.services.index') }}"
       class="px-4 py-2.5 text-sm font-semibold -mb-px border-b-2 transition
              {{ $serviceTab === 'services'
                 ? 'border-[#1d4ed8] text-[#1d4ed8] dark:text-blue-400'
                 : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200' }}">
        Services
    </a>
    <a href="{{ route('admin.service-categories.index') }}"
       class="px-4 py-2.5 text-sm font-semibold -mb-px border-b-2 transition
              {{ $serviceTab === 'categories'
                 ? 'border-[#1d4ed8] text-[#1d4ed8] dark:text-blue-400'
                 : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200' }}">
        Categories
    </a>
</div>

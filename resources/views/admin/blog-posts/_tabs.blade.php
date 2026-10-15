{{-- Blog section tabs: Posts | Categories --}}
@php
    $blogTab = $blogTab ?? 'posts';
@endphp
<div class="flex gap-1 mb-6 border-b border-slate-200 dark:border-slate-700">
    <a href="{{ route('admin.blog-posts.index') }}"
       class="px-4 py-2.5 text-sm font-semibold -mb-px border-b-2 transition
              {{ $blogTab === 'posts'
                 ? 'border-[#1d4ed8] text-[#1d4ed8] dark:text-blue-400'
                 : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200' }}">
        Posts
    </a>
    <a href="{{ route('admin.blog-categories.index') }}"
       class="px-4 py-2.5 text-sm font-semibold -mb-px border-b-2 transition
              {{ $blogTab === 'categories'
                 ? 'border-[#1d4ed8] text-[#1d4ed8] dark:text-blue-400'
                 : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200' }}">
        Categories
    </a>
</div>

{{-- Drawer form: create / edit blog category. AJAX submit (data-ajax-form), no page refresh. --}}
<form data-ajax-form data-reload-table="{{ $tableId }}" method="POST" action="{{ $action }}" novalidate>
    @csrf
    @if ($method === 'PUT') @method('PUT') @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div class="sm:col-span-2"><x-ui.input name="name" label="Name" :value="$blogCategory->name" required placeholder="Technology" /></div>
        <x-ui.input name="slug" label="Slug" :value="$blogCategory->slug" required placeholder="technology" hint="Auto-generated from name." />
        <x-ui.input name="sort_order" type="number" label="Sort Order" :value="$blogCategory->sort_order ?? 0" />
        <div class="sm:col-span-2">
            <input type="hidden" name="status" value="0">
            <x-ui.toggle name="status" label="Active" :checked="old('status', $blogCategory->status ?? true)" hint="Show this category on the website." />
        </div>
    </div>

    <x-admin.drawer-footer :save-label="$formTitle" />
</form>

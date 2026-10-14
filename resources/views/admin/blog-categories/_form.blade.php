{{-- Drawer form: create / edit blog category. AJAX submit (data-ajax-form), no page refresh. --}}
<form data-ajax-form data-reload-table="{{ $tableId }}" method="POST" action="{{ $action }}">
    @csrf
    @if ($method === 'PUT') @method('PUT') @endif

    <x-ui.input name="name" label="Name" :value="$blogCategory->name" required />
    <x-ui.input name="slug" label="Slug" :value="$blogCategory->slug" required />
    <x-ui.input name="sort_order" type="number" label="Sort Order" :value="$blogCategory->sort_order ?? 0" />

    <input type="hidden" name="status" value="0">
    <x-ui.toggle name="status" label="Active" :checked="old('status', $blogCategory->status ?? true)" />

    <button type="submit" class="btn btn-primary w-full mt-2">{{ $formTitle }}</button>
</form>

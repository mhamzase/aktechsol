{{-- Drawer form: create / edit FAQ. AJAX submit (data-ajax-form), no page refresh. --}}
<form data-ajax-form data-reload-table="{{ $tableId }}" method="POST" action="{{ $action }}" novalidate>
    @csrf
    @if ($method === 'PUT') @method('PUT') @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div class="sm:col-span-2"><x-ui.input name="question" label="Question" :value="$faq->question" required placeholder="How do I get started?" /></div>
        <div class="sm:col-span-2"><x-ui.rich-text name="answer" label="Answer" :value="$faq->answer" required /></div>
        <x-ui.input name="sort_order" type="number" label="Sort Order" :value="$faq->sort_order ?? 0" />
        <div class="flex items-end">
            <input type="hidden" name="status" value="0">
            <x-ui.toggle name="status" label="Active" :checked="old('status', $faq->status ?? true)" hint="Show on website." />
        </div>
    </div>

    <x-admin.drawer-footer :save-label="$formTitle" />
</form>

{{-- Drawer form: create / edit FAQ. AJAX submit (data-ajax-form), no page refresh. --}}
<form data-ajax-form data-reload-table="{{ $tableId }}" method="POST" action="{{ $action }}">
    @csrf
    @if ($method === 'PUT') @method('PUT') @endif

    <x-ui.input name="question" label="Question" :value="$faq->question" required />
    <x-ui.textarea name="answer" label="Answer" :value="$faq->answer" rows="5" required />
    <x-ui.input name="sort_order" type="number" label="Sort Order" :value="$faq->sort_order ?? 0" />

    <input type="hidden" name="status" value="0">
    <x-ui.toggle name="status" label="Active" :checked="old('status', $faq->status ?? true)" />

    <button type="submit" class="btn btn-primary w-full mt-2">{{ $formTitle }}</button>
</form>

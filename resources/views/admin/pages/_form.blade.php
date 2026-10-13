{{-- Drawer form: edit a legal page with the Quill rich text editor. --}}
<form data-ajax-form method="POST" action="{{ $action }}">
    @csrf @method('PUT')
    <div class="space-y-4">
        {{-- Quill editor (local). Hidden input carries the HTML to the server. --}}
        <div>
            <label class="form-label">{{ $title }}</label>
            <div id="quill-editor" style="min-height: 320px;">{!! $content !!}</div>
            <input type="hidden" name="content" id="quill-content">
        </div>
        <button type="submit" class="btn btn-primary w-full">Save {{ $title }}</button>
    </div>
</form>

<script>
// (Re)initialise Quill each time the drawer form loads.
(function () {
    const container = document.getElementById('quill-editor');
    if (!container || typeof Quill === 'undefined') return;
    const quill = new Quill(container, {
        theme: 'snow',
        modules: { toolbar: [['bold', 'italic', 'underline'], [{ header: [2, 3, false] }],
            [{ list: 'ordered' }, { list: 'bullet' }], ['link'], ['clean']] },
    });
    container.closest('form').addEventListener('submit', () => {
        document.getElementBy
...[truncated 351 chars]
{{-- Drawer form: create / edit testimonial. AJAX submit (data-ajax-form), no page refresh. --}}
<form data-ajax-form data-reload-table="{{ $tableId }}" method="POST" action="{{ $action }}" enctype="multipart/form-data">
    @csrf
    @if ($method === 'PUT') @method('PUT') @endif

    <x-ui.input name="client_name" label="Client Name" :value="$testimonial->client_name" required />
    <div class="grid grid-cols-2 gap-3">
        <x-ui.input name="client_position" label="Client Position" :value="$testimonial->client_position" />
        <x-ui.input name="company" label="Company" :value="$testimonial->company" />
    </div>
    <x-ui.textarea name="content" label="Testimonial Content" :value="$testimonial->content" rows="4" required />
    <div class="grid grid-cols-2 gap-3">
        <x-ui.select name="rating" label="Rating"
                     :options="['5' => '5 ★', '4' => '4 ★', '3' => '3 ★', '2' => '2 ★', '1' => '1 ★']"
                     :value="$testimonial->rating ?? 5" required />
        <x-ui.input name="sort_order" type="number" label="Sort Order" :value="$testimonial->sort_order ?? 0" />
    </div>

    <input type="hidden" name="status" value="0">
    <x-ui.toggle name="status" label="Active" :checked="old('status', $testimonial->status ?? true)" />

    @if (!empty($photoUrl))
        <div class="mb-3">
            <p class="ui-label">Current Photo</p>
            <img src="{{ $photoUrl }}" class="h-20 w-20 object-cover rounded-full border border-slate-200" alt="">
        </div>
    @endif
    <x-ui.file name="photo" label="Client Photo" accept="image/*" hint="Max 2MB." />

    <button type="submit" class="btn btn-primary w-full mt-2">{{ $formTitle }}</button>
</form>

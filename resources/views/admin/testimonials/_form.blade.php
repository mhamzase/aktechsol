{{-- Drawer form: create / edit testimonial. AJAX submit (data-ajax-form), no page refresh. --}}
<form data-ajax-form data-reload-table="{{ $tableId }}" method="POST" action="{{ $action }}" enctype="multipart/form-data" novalidate>
    @csrf
    @if ($method === 'PUT') @method('PUT') @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div class="sm:col-span-2"><x-ui.input name="client_name" label="Client Name" :value="$testimonial->client_name" required placeholder="John Doe" /></div>
        <x-ui.input name="client_position" label="Client Position" :value="$testimonial->client_position" placeholder="CEO" />
        <x-ui.input name="company" label="Company" :value="$testimonial->company" placeholder="Acme Inc." />
        <div class="sm:col-span-2"><x-ui.textarea name="content" label="Testimonial Content" :value="$testimonial->content" rows="4" required placeholder="What did the client say?" /></div>
        <x-ui.select name="rating" label="Rating"
                     :options="['5' => '5 ★', '4' => '4 ★', '3' => '3 ★', '2' => '2 ★', '1' => '1 ★']"
                     :value="$testimonial->rating ?? 5" required />
        <x-ui.input name="sort_order" type="number" label="Sort Order" :value="$testimonial->sort_order ?? 0" />
        <div class="sm:col-span-2">
            <input type="hidden" name="status" value="0">
            <x-ui.toggle name="status" label="Active" :checked="old('status', $testimonial->status ?? true)" hint="Show on website." />
        </div>
        <div class="sm:col-span-2">
            @if (!empty($photoUrl))
                <div class="mb-3">
                    <p class="ui-label">Current Photo</p>
                    <img src="{{ $photoUrl }}" class="h-20 w-20 object-cover rounded-full border border-slate-200 dark:border-slate-700" alt="">
                </div>
            @endif
            <x-ui.file name="photo" label="Client Photo" accept="image/*" hint="Square image works best. Max 2MB." />
        </div>
    </div>

    <x-admin.drawer-footer :save-label="$formTitle" />
</form>

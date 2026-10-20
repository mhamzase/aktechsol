{{-- Drawer form: create / edit pricing package. AJAX submit (data-ajax-form), no page refresh. --}}
<form data-ajax-form data-reload-table="{{ $tableId }}" method="POST" action="{{ $action }}" novalidate>
    @csrf
    @if ($method === 'PUT') @method('PUT') @endif

    {{-- Package details --}}
    <div class="mb-5">
        <h3 class="section-heading">
            <span class="sec-icon sec-blue">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            </span>
            Package Details
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div class="sm:col-span-2"><x-ui.input name="name" label="Package Name" :value="$package->name" required placeholder="Growth" /></div>
            <x-ui.input name="price" type="number" label="Price (PKR)" :value="$package->price" required placeholder="35000" />
            <x-ui.input name="price_label" label="Price Label" :value="$package->price_label" placeholder="Starting from" hint="Shown above the price." />
            <x-ui.input name="turnaround" label="Turnaround" :value="$package->turnaround" placeholder="14 days" />
            <x-ui.input name="sort_order" type="number" label="Sort Order" :value="$package->sort_order ?? 0" />
            <div class="sm:col-span-2"><x-ui.textarea name="description" label="Description" :value="$package->description" rows="2" placeholder="Short line shown under the package name…" /></div>
            <div class="sm:col-span-2">
                <x-ui.textarea name="features_text" label="Features" :value="$package->features ? implode("\n", $package->features) : ''" rows="6" placeholder="10-page website&#10;Blog setup&#10;Advanced SEO" hint="One feature per line — each becomes a ✓ item on the pricing card." />
            </div>
        </div>
    </div>

    {{-- Display --}}
    <div class="mb-2">
        <h3 class="section-heading">
            <span class="sec-icon sec-green">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </span>
            Display
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <input type="hidden" name="is_active" value="0">
                <x-ui.toggle name="is_active" label="Active" :checked="old('is_active', $package->is_active ?? true)" hint="Show this package on the website." />
            </div>
            <div>
                <input type="hidden" name="is_popular" value="0">
                <x-ui.toggle name="is_popular" label="Most Popular" :checked="old('is_popular', $package->is_popular ?? false)" hint="Highlights the card on pricing." />
            </div>
        </div>
    </div>

    <x-admin.drawer-footer :save-label="$formTitle" />
</form>

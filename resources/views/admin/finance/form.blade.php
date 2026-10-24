{{-- Drawer form: manual Finance entry (revenue / expense / withdrawal).
     Project revenue entries never reach this form; they are read-only. --}}
@php
    $isEdit = $entry !== null;
    $titleLabel = $type === 'withdrawal' ? 'Title (optional)' : 'Title';
    $categories = $type === 'expense'
        ? \App\Models\FinanceEntry::EXPENSE_CATEGORIES
        : ($type === 'revenue' ? \App\Models\FinanceEntry::REVENUE_CATEGORIES : []);
    $categoryOptions = array_combine($categories, $categories);
    $defaultCurrency = 'PKR';
    try {
        $defaultCurrency = app(\App\Settings\SiteSettings::class)->default_currency ?? 'PKR';
    } catch (\Throwable $e) {
        // Settings unavailable: fall back to PKR.
    }
@endphp

<form data-ajax-form data-reload-table="{{ $type }}-table" method="POST" enctype="multipart/form-data"
      action="{{ $isEdit ? route('admin.finance.update', [$type, $entry]) : route('admin.finance.store', $type) }}" novalidate>
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div class="grid md:grid-cols-12 gap-4">
        <div class="{{ $categories ? 'md:col-span-7' : 'md:col-span-12' }}">
            <x-ui.input name="title" :label="$titleLabel" :value="$entry->title ?? ''"
                        :required="$type !== 'withdrawal'"
                        placeholder="{{ $type === 'expense' ? 'e.g. Office rent for October' : ($type === 'revenue' ? 'e.g. Consultation fee' : 'e.g. Owner withdrawal') }}" />
        </div>
        @if ($categories)
            <div class="md:col-span-5">
                <x-ui.select name="category" label="Category" :value="$entry->category ?? ''"
                             :options="$categoryOptions" :required="$type === 'expense'"
                             placeholder="Select category" />
            </div>
        @endif

        <div class="md:col-span-4">
            <x-ui.input name="amount" label="Amount" type="number" step="any" min="0"
                        :value="$entry->amount ?? ''" required placeholder="0" />
        </div>
        <div class="md:col-span-4">
            <x-ui.select name="currency" label="Currency"
                         :value="$entry->currency ?? $defaultCurrency"
                         :options="\App\Support\Money::options()" required />
        </div>
        <div class="md:col-span-4">
            <x-ui.date name="entry_date" label="Date" :value="isset($entry) ? $entry->entry_date->format('Y-m-d') : now()->format('Y-m-d')" required />
        </div>

        <div class="md:col-span-12">
            <x-ui.textarea name="note" label="Note (optional)" rows="2" :value="$entry->note ?? ''"
                           placeholder="Any detail worth remembering" />
        </div>

        <div class="md:col-span-12">
            <x-ui.file name="attachment" label="Receipt / Proof (optional)" accept="image/*"
                       hint="An image of the receipt or transfer proof." />
            @if ($isEdit && $entry->getFirstMediaUrl('attachment'))
                <p class="text-xs text-slate-500 mt-2">Current file:
                    <a href="{{ $entry->getFirstMediaUrl('attachment') }}" target="_blank" rel="noopener" class="text-blue-600 font-semibold hover:underline">View</a>
                    (uploading a new one replaces it)
                </p>
            @endif
        </div>
    </div>

    @if ($type === 'withdrawal')
        <p class="text-xs text-slate-500 mt-4">A withdrawal cannot be more than the available balance in the chosen currency: total revenue minus expenses minus earlier withdrawals.</p>
    @endif

    <x-admin.drawer-footer :save-label="$isEdit ? 'Save Changes' : 'Add ' . ucfirst($type)" />
</form>

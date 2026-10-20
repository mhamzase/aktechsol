{{-- Invoice create/edit drawer form --}}
<div class="drawer-content">
    <form data-ajax-form data-reload-table="invoices-table" method="POST"
          action="{{ $invoice->exists ? route('admin.invoices.update', $invoice) : route('admin.invoices.store') }}"
          class="px-6 pb-6 pt-2 space-y-4 max-w-4xl mx-auto">
        @csrf
        @if($invoice->exists) @method('PUT') @endif

        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <div class="md:col-span-12">
                <x-ui.search-select name="client_project_id" label="Client Project" :value="$invoice->client_project_id"
                                    placeholder="— Standalone invoice —"
                                    :options="$projects->mapWithKeys(fn($p) => [$p->id => $p->title . ' (' . $p->client_name . ')'])->prepend('— Standalone invoice —', '')->toArray()"
                                    hint="Optional. Links the invoice to a project." />
            </div>
            <div class="md:col-span-12">
                <x-ui.input name="title" label="Invoice Title" :value="$invoice->title" required placeholder="Website development — Milestone 2" />
            </div>
            <div class="md:col-span-4">
                <x-ui.input name="amount" type="number" step="0.01" min="0.01" label="Amount" :value="$invoice->amount" required placeholder="1500.00" />
            </div>
            <div class="md:col-span-2">
                <x-ui.select name="currency" label="Currency" :value="$invoice->currency ?? 'PKR'" required
                             :options="['PKR' => 'PKR (Rs)', 'USD' => 'USD ($)']"
                             hint="A linked invoice follows its project's currency." />
            </div>
            <div class="md:col-span-6">
                <x-ui.date name="due_date" label="Due Date" :value="$invoice->due_date?->format('Y-m-d')" />
            </div>
            <div class="md:col-span-6">
                <x-ui.select name="status" label="Status" :value="$invoice->status ?? 'unpaid'" required
                             :options="['unpaid' => 'Unpaid', 'partially_paid' => 'Partially Paid', 'paid' => 'Paid', 'overdue' => 'Overdue', 'cancelled' => 'Cancelled']" />
            </div>
            <div class="md:col-span-12">
                <x-ui.textarea name="notes" label="Notes" rows="3" :value="$invoice->notes" placeholder="Payment instructions, bank details, etc." />
            </div>
        </div>

        <x-admin.drawer-footer :save-label="$invoice->exists ? 'Update Invoice' : 'Create Invoice'" />
    </form>
</div>

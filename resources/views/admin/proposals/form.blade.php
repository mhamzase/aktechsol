{{-- Proposal create/edit drawer form --}}
<div class="drawer-content">
    <form data-ajax-form data-reload-table="proposals-table" method="POST"
          action="{{ $proposal->exists ? route('admin.proposals.update', $proposal) : route('admin.proposals.store') }}"
          class="px-6 pb-6 pt-2 space-y-4">
        @csrf
        @if($proposal->exists) @method('PUT') @endif

        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <div class="md:col-span-12">
                <x-ui.input name="title" label="Proposal Title" :value="$proposal->title" required placeholder="Website Development Proposal" />
            </div>
            <div class="md:col-span-6">
                <x-ui.input name="client_name" label="Client Name" :value="$proposal->client_name" required placeholder="John Doe" />
            </div>
            <div class="md:col-span-6">
                <x-ui.input name="client_email" type="email" label="Client Email" :value="$proposal->client_email" required placeholder="john@example.com" />
            </div>
            <div class="md:col-span-6">
                <x-ui.select name="quote_request_id" label="Linked Quote (optional)" :value="$proposal->quote_request_id"
                             :options="$quotes->pluck('subject', 'id')->prepend('— None —', '')->toArray()" />
            </div>
            <div class="md:col-span-4">
                <x-ui.input name="amount" type="number" step="0.01" min="0" label="Amount" :value="$proposal->amount" required placeholder="5000" />
            </div>
            <div class="md:col-span-2">
                <x-ui.select name="currency" label="Currency" :value="$proposal->currency ?? 'PKR'" required
                             :options="['PKR' => 'PKR', 'USD' => 'USD']"
                             hint="The whole project runs in this currency." />
            </div>
            <div class="md:col-span-6">
                <x-ui.input name="timeline" label="Timeline" :value="$proposal->timeline" placeholder="4-6 weeks" />
            </div>
            <div class="md:col-span-6">
                <x-ui.date name="valid_until" label="Valid Until" :value="$proposal->valid_until?->format('Y-m-d')" hint="Expiry date" />
            </div>
            <div class="md:col-span-12">
                <x-ui.rich-text name="scope" label="Project Scope" :value="$proposal->scope" required
                                placeholder="Detailed scope of work…" />
            </div>
        </div>

        {{-- Milestones --}}
        <div>
            <p class="text-sm font-semibold text-slate-700 mb-2">Payment Milestones (optional)</p>
            <div id="milestones-list" class="space-y-2" data-milestone-index="{{ count($proposal->milestones ?? []) }}">
                @foreach(($proposal->milestones ?? []) as $i => $m)
                <div class="milestone-row">
                    <input type="text" name="milestones[{{ $i }}][title]" value="{{ $m['title'] ?? '' }}" placeholder="Milestone title" class="ui-control milestone-title">
                    <input type="number" name="milestones[{{ $i }}][amount]" value="{{ $m['amount'] ?? '' }}" placeholder="Amount" step="0.01" min="0" class="ui-control milestone-amount">
                    <button type="button" data-remove-milestone class="btn btn-secondary milestone-remove" title="Remove">×</button>
                </div>
                @endforeach
            </div>
            <button type="button" data-add-milestone class="btn btn-secondary mt-2 text-sm">+ Add Milestone</button>
        </div>

        <x-admin.drawer-footer :save-label="$proposal->exists ? 'Update Proposal' : 'Create Proposal'" />
    </form>
</div>

{{-- Row partial for the Finance AJAX tables (one shape per tab). --}}
@forelse ($entries as $entry)
    <tr>
        <td data-label="Date" class="whitespace-nowrap text-slate-500">{{ $entry->entry_date->format('d M Y') }}</td>
        <td data-label="Title">
            <p class="font-medium text-slate-800">{{ $entry->title }}</p>
            @if ($entry->note)
                <p class="text-xs text-slate-400 mt-0.5">{{ \Illuminate\Support\Str::limit($entry->note, 70) }}</p>
            @endif
        </td>
        @if ($type === 'revenue')
            <td data-label="Source">
                @if ($entry->isProject())
                    <span class="badge badge-blue">Project</span>
                @else
                    <span class="badge badge-gray">{{ $entry->category ?? 'Manual' }}</span>
                @endif
            </td>
        @endif
        @if ($type === 'expense')
            <td data-label="Category">
                <span class="badge badge-purple">{{ $entry->category ?? 'Other' }}</span>
            </td>
        @endif
        <td data-label="Amount" class="whitespace-nowrap">
            <span class="font-bold text-slate-800">{{ \App\Support\Money::format($entry->amount, $entry->currency) }}</span>
        </td>
        <td data-label="PKR Value" class="whitespace-nowrap text-slate-500">
            @if ($entry->amount_pkr !== null)
                ≈ {{ \App\Support\Money::format($entry->amount_pkr, 'PKR') }}
            @else
                <span class="text-xs font-semibold text-amber-600">Rate not set</span>
            @endif
        </td>
        @if ($type === 'withdrawal')
            <td data-label="Attachment">
                @if ($entry->getFirstMediaUrl('attachment'))
                    <a href="{{ $entry->getFirstMediaUrl('attachment') }}" target="_blank" rel="noopener" class="text-blue-600 text-xs font-semibold hover:underline">View proof</a>
                @else
                    <span class="text-slate-300">—</span>
                @endif
            </td>
        @endif
        <td data-label="Actions" class="no-label whitespace-nowrap">
            @if ($entry->isProject())
                {{-- Project revenue is managed from the project itself. --}}
                <x-admin.row-actions
                    :viewUrl="route('admin.client-projects.show', $entry->revenueShare->client_project_id)"
                    viewTitle="Project Details"
                    tableId="{{ $type }}-table" />
            @else
                <x-admin.row-actions
                    :editUrl="route('admin.finance.edit', [$type, $entry])"
                    editTitle="Edit {{ ucfirst($type) }}"
                    :deleteUrl="route('admin.finance.destroy', [$type, $entry])"
                    tableId="{{ $type }}-table" />
            @endif
        </td>
    </tr>
@empty
    <tr><td colspan="6" class="text-center py-10 text-slate-400 text-sm">No {{ $type }} entries found.</td></tr>
@endforelse

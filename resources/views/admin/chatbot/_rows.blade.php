{{-- Row partial for the chatbot AJAX table. --}}
@forelse ($queries as $query)
    <tr>
        <td data-label="Query">
            <p class="font-medium text-slate-800 line-clamp-2">{{ $query->query }}</p>
            @if($query->answer)
            <p class="text-xs text-slate-400 mt-1 line-clamp-1">{{ strip_tags($query->answer) }}</p>
            @endif
        </td>
        <td data-label="Status">
            @if($query->answer)
                <span class="badge badge-green">Answered</span>
            @else
                <span class="badge badge-amber">Unanswered</span>
            @endif
        </td>
        <td data-label="Asked" class="text-slate-500 whitespace-nowrap">{{ $query->created_at->format('M d, Y') }}</td>
        <td data-label="Actions" class="no-label whitespace-nowrap">
            <x-admin.row-actions
                :viewUrl="route('admin.chatbot.show', $query)"
                :deleteUrl="route('admin.chatbot.destroy', $query)"
                tableId="chatbot-table" />
        </td>
    </tr>
@empty
    <tr><td colspan="4" class="text-center py-10 text-slate-400 text-sm">No queries found.</td></tr>
@endforelse

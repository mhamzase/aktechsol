{{-- Row partial for the job applications AJAX table. --}}
@php
    $statusBadges = ['new' => 'badge-amber', 'reviewed' => 'badge-blue', 'shortlisted' => 'badge-green', 'rejected' => 'badge-red'];
@endphp
@forelse ($applications as $application)
    <tr>
        <td data-label="Candidate">
            <p class="font-medium text-slate-800">{{ $application->name }}</p>
            <p class="text-xs text-slate-400">{{ $application->email }}</p>
        </td>
        <td data-label="Job" class="text-slate-500">{{ $application->job?->title ?? '—' }}</td>
        <td data-label="Portfolio">
            @if($application->portfolio_url)
                <a href="{{ $application->portfolio_url }}" target="_blank" rel="noopener" class="text-[#1d4ed8] font-semibold text-sm hover:underline">View</a>
            @else
                <span class="text-slate-300">—</span>
            @endif
        </td>
        <td data-label="Status"><span class="badge {{ $statusBadges[$application->status] ?? 'badge-gray' }}">{{ ucfirst($application->status) }}</span></td>
        <td data-label="Applied" class="text-slate-500 whitespace-nowrap">{{ $application->created_at->format('M d, Y') }}</td>
        <td data-label="Actions" class="no-label whitespace-nowrap">
            <x-admin.row-actions
                :viewUrl="route('admin.job-applications.show', $application)"
                :deleteUrl="route('admin.job-applications.destroy', $application)"
                tableId="job-applications-table" />
        </td>
    </tr>
@empty
    <tr><td colspan="6" class="text-center py-10 text-slate-400 text-sm">No applications found.</td></tr>
@endforelse

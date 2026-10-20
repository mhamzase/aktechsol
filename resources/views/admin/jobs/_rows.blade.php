{{-- Row partial for the jobs AJAX table. Every <td> carries data-label for the mobile card view. --}}
@forelse ($jobs as $job)
    <tr>
        <td data-label="Title">
            <p class="font-medium text-slate-800 dark:text-slate-100">{{ $job->title }}</p>
            <p class="text-xs text-slate-400">/careers/{{ $job->slug }}</p>
        </td>
        <td data-label="Type"><span class="badge {{ $job->type === 'internship' ? 'badge-purple' : 'badge-blue' }}">{{ $job->type === 'internship' ? 'Internship' : 'Job' }}</span></td>
        <td data-label="Location" class="text-slate-500 capitalize">{{ $job->location_type }}</td>
        <td data-label="Applications" class="text-slate-500">{{ $job->applications_count }}</td>
        <td data-label="Status"><span class="badge {{ $job->is_open ? 'badge-green' : 'badge-red' }}">{{ $job->is_open ? 'Open' : 'Closed' }}</span></td>
        <td data-label="Actions" class="no-label whitespace-nowrap">
            <x-admin.row-actions
                :editUrl="route('admin.jobs.edit', $job)"
                editTitle="Edit Job"
                :deleteUrl="route('admin.jobs.destroy', $job)"
                tableId="jobs-table" />
        </td>
    </tr>
@empty
    <tr><td colspan="6" class="text-center py-10 text-slate-400 text-sm">No jobs found.</td></tr>
@endforelse

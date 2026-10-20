{{-- Row partial for the packages AJAX table. Every <td> carries data-label for the mobile card view. --}}
@forelse ($packages as $package)
    <tr>
        <td data-label="Package">
            <p class="font-medium text-slate-800 dark:text-slate-100">{{ $package->name }}</p>
            @if($package->description)<p class="text-xs text-slate-400 truncate max-w-[220px]">{{ $package->description }}</p>@endif
        </td>
        <td data-label="Price" class="text-slate-500 whitespace-nowrap">
            @if($package->price_label)<span class="text-xs text-slate-400">{{ $package->price_label }}</span><br>@endif
            <span class="font-semibold text-slate-700 dark:text-slate-200">PKR {{ number_format($package->price) }}</span>
        </td>
        <td data-label="Turnaround" class="text-slate-500">{{ $package->turnaround ?? '—' }}</td>
        <td data-label="Popular">
            @if($package->is_popular)<span class="badge badge-amber">Popular</span>@else<span class="text-slate-300">—</span>@endif
        </td>
        <td data-label="Status"><span class="badge {{ $package->is_active ? 'badge-green' : 'badge-red' }}">{{ $package->is_active ? 'Active' : 'Inactive' }}</span></td>
        <td data-label="Actions" class="no-label whitespace-nowrap">
            <x-admin.row-actions
                :editUrl="route('admin.packages.edit', $package)"
                editTitle="Edit Package"
                :deleteUrl="route('admin.packages.destroy', $package)"
                tableId="packages-table" />
        </td>
    </tr>
@empty
    <tr><td colspan="6" class="text-center py-10 text-slate-400 text-sm">No packages found.</td></tr>
@endforelse

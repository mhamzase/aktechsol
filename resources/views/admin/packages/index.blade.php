@extends('admin.layouts.app')

@section('title', 'Packages')

@section('content')
    <x-admin.page-header title="Packages" subtitle="Manage pricing packages shown on the website"
                        icon="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
        <x-slot:actions>
            <button type="button" onclick="openDrawer('{{ route('admin.packages.create') }}', 'Add Package')" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Package
            </button>
        </x-slot:actions>
    </x-admin.page-header>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-6" id="package-stats">
        <div class="bg-white rounded-2xl p-5 border border-slate-100">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total</p>
            <p class="text-2xl font-extrabold text-slate-800 mt-1" data-stat="total">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Active</p>
            <p class="text-2xl font-extrabold text-green-600 mt-1" data-stat="active">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Popular</p>
            <p class="text-2xl font-extrabold text-amber-600 mt-1" data-stat="popular">{{ $stats['popular'] }}</p>
        </div>
    </div>

    <x-admin.data-table id="packages-table"
                        :ajax-url="route('admin.packages.index')"
                        :columns="['Package', 'Price', 'Turnaround', 'Popular', 'Status', 'Actions']"
                        search-placeholder="Search packages…">
        <x-slot:filters>
            <select data-table-filter="status" class="form-control table-select !w-auto !py-1.5 text-sm">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </x-slot:filters>
    </x-admin.data-table>
@endsection

@push('scripts')
<script>
// Update stats cards when a package is saved (no page refresh).
document.addEventListener('ajaxFormSuccess', function(e) {
    const stats = e.detail?.stats;
    if (!stats) return;
    Object.entries(stats).forEach(([key, val]) => {
        const el = document.querySelector(`#package-stats [data-stat="${key}"]`);
        if (el) el.textContent = val;
    });
});
</script>
@endpush

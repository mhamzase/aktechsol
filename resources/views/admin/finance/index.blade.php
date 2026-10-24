@extends('admin.layouts.app')

@section('title', 'Finance')

@section('content')
<div class="space-y-6">
    <x-admin.page-header title="Finance" subtitle="Company money in one place: revenue, expenses and withdrawals"
                        icon="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
        <x-slot:actions>
            {{-- One Add button in the hero, like every other page. Its
                 label and target follow the active tab (admin-finance.js). --}}
            <button type="button" id="fin-add-btn" class="btn btn-primary" onclick="window.finOpenCreate()"
                    data-url-revenue="{{ route('admin.finance.create', 'revenue') }}"
                    data-url-expense="{{ route('admin.finance.create', 'expense') }}"
                    data-url-withdrawal="{{ route('admin.finance.create', 'withdrawal') }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span data-fin-add-label>Add Revenue</span>
            </button>
        </x-slot:actions>
    </x-admin.page-header>

    {{-- Tabs: the shared Settings tab-btn pattern (admin.css .tab-btn) --}}
    <div class="grid grid-cols-3 sm:flex gap-1.5 sm:overflow-x-auto py-1" id="finance-tabs" role="tablist">
        @foreach (['revenue' => 'Revenue', 'expense' => 'Expenses', 'withdrawal' => 'Withdrawals'] as $key => $label)
            <button type="button" role="tab" data-fin-tab="{{ $key }}" class="tab-btn shrink-0">{{ $label }}</button>
        @endforeach
    </div>

    @include('admin.finance._panel', ['type' => 'revenue'])
    @include('admin.finance._panel', ['type' => 'expense'])
    @include('admin.finance._panel', ['type' => 'withdrawal'])
</div>
@endsection

@push('scripts')
<script>window.FIN_SUMMARY_URL = '{{ route('admin.finance.summary') }}';</script>
<script src="{{ asset('assets/js/admin-finance.js') }}?v={{ config('app.version') }}"></script>
@endpush

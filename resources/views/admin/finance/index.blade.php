@extends('admin.layouts.app')

@section('title', 'Finance')

@section('content')
    <x-admin.page-header title="Finance" subtitle="Company money in one place: revenue, expenses and withdrawals"
                        icon="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
    </x-admin.page-header>

    {{-- Tabs (Settings-style underline tabs, switched without a refresh) --}}
    <div class="border-b border-slate-200 dark:border-white/10 mb-6">
        <div class="flex gap-1 sm:gap-2 overflow-x-auto">
            @foreach (['revenue' => 'Revenue', 'expense' => 'Expenses', 'withdrawal' => 'Withdrawals'] as $key => $label)
                <button type="button" data-fin-tab="{{ $key }}"
                        class="fin-tab-btn px-4 py-2.5 text-sm font-semibold whitespace-nowrap border-b-2 -mb-px transition-colors">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    @include('admin.finance._panel', ['type' => 'revenue'])
    @include('admin.finance._panel', ['type' => 'expense'])
    @include('admin.finance._panel', ['type' => 'withdrawal'])
@endsection

@push('scripts')
<script>window.FIN_SUMMARY_URL = '{{ route('admin.finance.summary') }}';</script>
<script src="{{ asset('assets/js/admin-finance.js') }}"></script>
@endpush

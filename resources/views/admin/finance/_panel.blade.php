{{-- One Finance tab panel: date filter, stat cards, chart, AJAX table.
     Everything is filled/refreshed by public/assets/js/admin-finance.js. --}}
@php
    $cfg = [
        'revenue' => [
            'add' => 'Add Revenue', 'search' => 'Search revenue…',
            'cols' => ['Date', 'Title', 'Source', 'Amount', 'PKR Value', 'Actions'],
            'cards' => ['total' => 'Total Revenue', 'a' => 'From Projects', 'b' => 'Manual Revenue', 'c' => 'Pending From Projects'],
        ],
        'expense' => [
            'add' => 'Add Expense', 'search' => 'Search expenses…',
            'cols' => ['Date', 'Title', 'Category', 'Amount', 'PKR Value', 'Actions'],
            'cards' => ['total' => 'Total Expenses', 'a' => 'Entries', 'b' => 'Top Category', 'c' => 'Net Balance (Overall PKR)'],
        ],
        'withdrawal' => [
            'add' => 'Add Withdrawal', 'search' => 'Search withdrawals…',
            'cols' => ['Date', 'Title', 'Amount', 'PKR Value', 'Attachment', 'Actions'],
            'cards' => ['total' => 'Total Withdrawals', 'a' => 'Available Balance', 'b' => 'Total Revenue (All Time)', 'c' => 'Total Expenses (All Time)'],
        ],
    ][$type];
@endphp

<div data-fin-panel="{{ $type }}" class="{{ $type === 'revenue' ? '' : 'hidden' }}">
    {{-- Date range filter: presets + the shared custom range picker.
         Stats and the chart follow this range; the listing below stays
         the full overall record. --}}
    <div class="admin-card p-4 sm:p-5 mb-5">
        <div class="flex flex-wrap items-center gap-2 mb-4">
            <div class="flex flex-wrap gap-2" data-fin-presets>
                <button type="button" data-fin-preset="month" class="fin-preset-btn">This Month</button>
                <button type="button" data-fin-preset="last" class="fin-preset-btn">Last Month</button>
                <button type="button" data-fin-preset="6m" class="fin-preset-btn">Last 6 Months</button>
                <button type="button" data-fin-preset="all" class="fin-preset-btn">All Time</button>
                <button type="button" data-fin-preset="custom" class="fin-preset-btn">Custom</button>
            </div>
            <span class="ml-auto text-xs font-medium text-slate-500 dark:text-slate-400" data-fin-range-label></span>
        </div>
        <div class="max-w-2xl" data-fin-custom>
            <x-ui.date-range nameStart="from" nameEnd="to" label="Custom range (stats and chart follow it)" />
        </div>
        <p data-fin-missing class="hidden mt-3 text-xs font-semibold text-amber-600"></p>
    </div>

    {{-- Stat cards (filled by JS from the summary endpoint) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        @foreach ($cfg['cards'] as $key => $label)
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700 shadow-sm" data-fin-card="{{ $key }}">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider" data-card-label>{{ $label }}</p>
                <p class="text-2xl font-extrabold text-slate-800 dark:text-white mt-1" data-card-main>…</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1" data-card-sub>Loading…</p>
            </div>
        @endforeach
    </div>

    {{-- Chart --}}
    <div class="admin-card p-5 mb-6">
        <h3 class="font-bold text-slate-800 dark:text-white">{{ ucfirst($type) }} Overview</h3>
        <div data-fin-chart class="mt-2"></div>
        <p data-fin-chart-empty class="hidden text-sm text-slate-400 py-10 text-center">No data in this range yet.</p>
    </div>

    {{-- Full listing (overall record, shared table pattern) --}}
    <x-admin.data-table id="{{ $type }}-table"
                        :ajax-url="route('admin.finance.listing', $type)"
                        :columns="$cfg['cols']"
                        :search-placeholder="$cfg['search']" />
</div>

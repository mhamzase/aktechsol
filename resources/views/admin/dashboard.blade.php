@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    @php
        $firstName = explode(' ', trim(auth()->user()->name ?? 'Admin'))[0];
        $todayLine = now()->format('l, d F Y');
    @endphp
    <x-admin.page-header title="Welcome back, {{ $firstName }}"
                        subtitle="Here's what's happening at your agency today · {{ $todayLine }}."
                        icon="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
        <x-slot:meta>
            <div class="hero-chip">
                <p class="hc-value">{{ $stats['quotes_new'] }}</p>
                <p class="hc-label">New Quotes</p>
            </div>
            <div class="hero-chip">
                <p class="hc-value">{{ $stats['chats_unanswered'] }}</p>
                <p class="hc-label">Unanswered</p>
            </div>
        </x-slot:meta>
    </x-admin.page-header>

    {{-- Stat cards --}}
    @php
        // Revenue is kept SEPARATE per currency (never mixed): the card
        // shows one amount per active currency, trends computed per currency.
        $revNowByCur = is_array($stats['revenue_month'] ?? null) ? $stats['revenue_month'] : ['PKR' => (float) ($stats['revenue_month'] ?? 0), 'USD' => 0];
        $revLastByCur = is_array($trends['revenue_last_month'] ?? null) ? $trends['revenue_last_month'] : ['PKR' => (float) ($trends['revenue_last_month'] ?? 0), 'USD' => 0];
        $revParts = [];
        $revTrendParts = [];
        $revDir = 'flat';
        foreach (\App\Support\Money::codes() as $cur) {
            $now = (float) ($revNowByCur[$cur] ?? 0);
            $last = (float) ($revLastByCur[$cur] ?? 0);
            if ($now > 0) {
                $revParts[] = \App\Support\Money::format($now, $cur);
            }
            if ($now > 0 || $last > 0) {
                if ($last > 0) {
                    $pct = abs(round(($now - $last) / $last * 100));
                    $revTrendParts[] = $cur . ' ' . $pct . '%';
                    if (count($revTrendParts) === 1) {
                        $revDir = $now >= $last ? 'up' : 'down';
                    }
                } else {
                    $revTrendParts[] = $cur . ' first revenue';
                    if (count($revTrendParts) === 1) {
                        $revDir = 'up';
                    }
                }
            }
        }
        $revValue = $revParts ? implode(' · ', $revParts) : \App\Support\Money::format(0, 'PKR');

        // Chart series: one per currency that actually has revenue in the
        // window (PKR always, as the baseline), each with its own scale.
        $revPalette = ['#f59e0b', '#10b981', '#8b5cf6', '#06b6d4', '#ef4444', '#ec4899', '#84cc16', '#f97316', '#64748b'];
        $revChartSeries = [];
        foreach (\App\Support\Money::codes() as $cur) {
            $data = ($revenueByMonth[$cur] ?? collect())->values()->all();
            if ($cur !== 'PKR' && collect($data)->sum() <= 0) {
                continue;
            }
            $revChartSeries[] = [
                'code' => $cur,
                'name' => 'Revenue (' . $cur . ')',
                'data' => $data,
                'color' => $revPalette[count($revChartSeries) % count($revPalette)],
                'symbol' => \App\Support\Money::symbol($cur),
                'decimals' => \App\Support\Money::decimals($cur),
            ];
        }
        $revTrend = ['dir' => $revDir,
                     'text' => $revTrendParts ? implode(' · ', $revTrendParts) . (str_contains(implode('', $revTrendParts), '%') ? ' vs last month' : '') : 'no revenue yet'];

        $cards = [
            ['label' => 'New Quotes', 'value' => $stats['quotes_new'],
             'tile' => 'bg-amber-100 text-amber-600', 'accent' => '#f59e0b',
             'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
             'trend' => ['dir' => $trends['quotes_week'] > 0 ? 'up' : 'flat',
                         'text' => $trends['quotes_week'] > 0 ? '+' . $trends['quotes_week'] . ' this week' : 'none this week']],
            ['label' => 'Active Projects', 'value' => $stats['projects_active'],
             'tile' => 'bg-blue-100 text-blue-600', 'accent' => '#1d4ed8',
             'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
             'trend' => ['dir' => 'flat', 'text' => 'in pipeline']],
            ['label' => 'Revenue · this month', 'value' => $revValue,
             'tile' => 'bg-emerald-100 text-emerald-600', 'accent' => '#10b981',
             'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
             'trend' => $revTrend],
            ['label' => 'Unanswered Chats', 'value' => $stats['chats_unanswered'],
             'tile' => 'bg-violet-100 text-violet-600', 'accent' => '#8b5cf6',
             'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
             'trend' => ['dir' => $stats['chats_unanswered'] > 0 ? 'down' : 'up',
                         'text' => $stats['chats_unanswered'] > 0 ? 'need a reply' : 'all caught up']],
        ];
        $trendArrow = ['up' => '↑', 'down' => '↓', 'flat' => '→'];
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        @foreach ($cards as $card)
            <div class="admin-card stat-card p-5" style="--stat-accent: {{ $card['accent'] }};">
                <div class="flex items-center gap-4">
                    <div class="stat-tile {{ $card['tile'] }}">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm text-slate-500">{{ $card['label'] }}</p>
                        <p class="text-2xl font-extrabold text-slate-900 mt-0.5 truncate">{{ $card['value'] }}</p>
                    </div>
                </div>
                <span class="stat-trend {{ $card['trend']['dir'] }}">{{ $trendArrow[$card['trend']['dir']] }} {{ $card['trend']['text'] }}</span>
            </div>
        @endforeach
    </div>

    {{-- Company Finance (ledger totals, all time; PKR via entry snapshots) --}}
    @can('manage finance')
    @php
        $finParts = function (array $per): string {
            $parts = [];
            foreach (\App\Support\Money::codes() as $code) {
                $value = (float) ($per[$code] ?? 0);
                if (abs($value) > 0.001) {
                    $parts[] = \App\Support\Money::format($value, $code);
                }
            }
            return implode(' · ', $parts);
        };
        $finCards = [
            ['label' => 'Company Revenue', 'key' => 'revenue', 'tile' => 'bg-green-100 text-green-600',
             'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label' => 'Company Expenses', 'key' => 'expense', 'tile' => 'bg-red-100 text-red-600',
             'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
            ['label' => 'Withdrawals', 'key' => 'withdrawal', 'tile' => 'bg-purple-100 text-purple-600',
             'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
        ];
    @endphp
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-bold text-slate-900">Company Finance</h3>
        <a href="{{ route('admin.finance.index') }}" class="text-sm font-semibold text-blue-700 hover:underline">Open Finance</a>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        @foreach ($finCards as $fc)
            <div class="admin-card p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="stat-tile {{ $fc['tile'] }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $fc['icon'] }}"/></svg>
                    </div>
                    <p class="text-sm text-slate-500">{{ $fc['label'] }}</p>
                </div>
                <p class="text-2xl font-extrabold text-slate-900">{{ \App\Support\Money::format($finance['types'][$fc['key']]['pkr'] ?? 0, 'PKR') }}</p>
                <p class="text-xs text-slate-400 mt-1">Overall PKR · {{ $finParts($finance['types'][$fc['key']]['per_currency'] ?? []) ?: 'no entries yet' }}</p>
            </div>
        @endforeach
        <div class="admin-card p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="stat-tile bg-blue-100 text-blue-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                </div>
                <p class="text-sm text-slate-500">Net Balance</p>
            </div>
            <p class="text-2xl font-extrabold text-slate-900">{{ \App\Support\Money::format($finance['net_pkr'] ?? 0, 'PKR') }}</p>
            <p class="text-xs text-slate-400 mt-1">Overall PKR · {{ $finParts($finance['net'] ?? []) ?: 'revenue − expenses − withdrawals' }}</p>
        </div>
    </div>
    @endcan

    {{-- Charts --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 mb-6">
        <div class="admin-card p-5 xl:col-span-2">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <h3 class="font-bold text-slate-900">Quotes & Revenue</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Last 6 months</p>
                </div>
                <svg class="h-5 w-5 card-corner-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <div id="chart-quotes-revenue"
                 data-labels='@json($quotesByMonth->keys())'
                 data-quotes='@json($quotesByMonth->values())'
                 data-revenue-series='@json($revChartSeries)'></div>
        </div>
        <div class="admin-card p-5">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <h3 class="font-bold text-slate-900">Project Pipeline</h3>
                    <p class="text-xs text-slate-400 mt-0.5">By status</p>
                </div>
                <svg class="h-5 w-5 card-corner-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
            </div>
            <div id="chart-pipeline"
                 data-labels='@json($projectPipeline->keys())'
                 data-values='@json($projectPipeline->values())'></div>
        </div>
    </div>

    {{-- Needs Attention --}}
    <div class="admin-card p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-bold text-slate-900">Needs Attention</h3>
                <p class="text-xs text-slate-400 mt-0.5">Items waiting for your action</p>
            </div>
            @php $pending = $attention['quotes']->count() + $attention['chats']->count() + $attention['invoices']->count() + $attention['demos']->count() + $attention['jobs']->count(); @endphp
            <span class="badge {{ $pending ? 'badge-red' : 'badge-green' }}">{{ $pending }} pending</span>
        </div>

        @php
            $groups = [
                ['title' => 'New Quotes', 'items' => $attention['quotes'], 'route' => 'admin.quotes.index',
                 'color' => '#1d4ed8', 'tint' => '#e8f0fe',
                 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                 'line' => fn($q) => $q->name . ($q->subject ? ' — ' . $q->subject : '')],
                ['title' => 'Unanswered Chats', 'items' => $attention['chats'], 'route' => 'admin.chatbot.index',
                 'color' => '#8b5cf6', 'tint' => '#f0eafe',
                 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
                 'line' => fn($c) => \Illuminate\Support\Str::limit($c->query, 50)],
                ['title' => 'Unpaid Invoices', 'items' => $attention['invoices'], 'route' => 'admin.invoices.index',
                 'color' => '#f59e0b', 'tint' => '#fef5e3',
                 'icon' => 'M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z',
                 'line' => fn($i) => $i->invoice_no . ': ' . \App\Support\Money::format($i->balance(), $i->currency)],
                ['title' => 'New Demo Requests', 'items' => $attention['demos'], 'route' => 'admin.demo-bookings.index',
                 'color' => '#10b981', 'tint' => '#e6f9f1',
                 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                 'line' => fn($d) => $d->name . ($d->preferred_date ? ' — ' . $d->preferred_date->format('d M') : '')],
                ['title' => 'New Applications', 'items' => $attention['jobs'], 'route' => 'admin.job-applications.index',
                 'color' => '#f43f5e', 'tint' => '#ffeef1',
                 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                 'line' => fn($j) => $j->name],
            ];
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
            @foreach ($groups as $g)
                <div class="att-card" style="--att: {{ $g['color'] }};">
                    <div class="flex items-start justify-between">
                        <span class="att-icon" style="color: {{ $g['color'] }}; background: {{ $g['tint'] }};">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $g['icon'] }}"/></svg>
                        </span>
                        <span class="att-count" style="color: {{ $g['color'] }};">{{ $g['items']->count() }}<small>pending</small></span>
                    </div>
                    <p class="att-title">{{ $g['title'] }}</p>
                    <div class="att-items">
                        @forelse ($g['items']->take(3) as $item)
                            <p class="att-item"><span class="att-bullet" style="background: {{ $g['color'] }};"></span><span class="truncate">{{ $g['line']($item) }}</span></p>
                        @empty
                            <p class="att-clear">✓ All clear</p>
                        @endforelse
                        @if ($g['items']->count() > 3)
                            <p class="att-more">+{{ $g['items']->count() - 3 }} more</p>
                        @endif
                    </div>
                    <a href="{{ route($g['route']) }}" class="att-link">View all <span>→</span></a>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/admin-dashboard.js') }}?v={{ config('app.version') }}"></script>
@endpush

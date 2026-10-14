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
        $revNow = (float) $stats['revenue_month'];
        $revLast = (float) $trends['revenue_last_month'];
        $revTrend = $revLast > 0
            ? ['dir' => $revNow >= $revLast ? 'up' : 'down',
               'text' => abs(round(($revNow - $revLast) / $revLast * 100)) . '% vs last month']
            : ['dir' => $revNow > 0 ? 'up' : 'flat',
               'text' => $revNow > 0 ? 'first revenue this month' : 'no revenue yet'];

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
            ['label' => 'Revenue · this month', 'value' => 'PKR ' . number_format($stats['revenue_month']),
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
                 data-revenue='@json($revenueByMonth->values())'></div>
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
                ['title' => 'New Quotes', 'items' => $attention['quotes'], 'route' => 'admin.quotes.index', 'dot' => 'bg-blue-500',
                 'line' => fn($q) => $q->name . ($q->subject ? ' — ' . $q->subject : '')],
                ['title' => 'Unanswered Chats', 'items' => $attention['chats'], 'route' => 'admin.chatbot.index', 'dot' => 'bg-violet-500',
                 'line' => fn($c) => \Illuminate\Support\Str::limit($c->query, 50)],
                ['title' => 'Unpaid Invoices', 'items' => $attention['invoices'], 'route' => 'admin.invoices.index', 'dot' => 'bg-amber-500',
                 'line' => fn($i) => $i->invoice_no . ' — PKR ' . number_format($i->balance(), 0)],
                ['title' => 'New Demo Requests', 'items' => $attention['demos'], 'route' => 'admin.demo-bookings.index', 'dot' => 'bg-emerald-500',
                 'line' => fn($d) => $d->name . ($d->preferred_date ? ' — ' . $d->preferred_date->format('d M') : '')],
                ['title' => 'New Applications', 'items' => $attention['jobs'], 'route' => 'admin.job-applications.index', 'dot' => 'bg-rose-500',
                 'line' => fn($j) => $j->name],
            ];
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
            @foreach ($groups as $g)
                <div class="rounded-xl border border-slate-100 p-4 hover:border-blue-200 hover:shadow-sm transition-all">
                    <p class="flex items-center justify-between mb-2.5">
                        <span class="flex items-center gap-2 text-sm font-bold text-slate-700">
                            <span class="w-2 h-2 rounded-full {{ $g['dot'] }}"></span>{{ $g['title'] }}
                        </span>
                        <span class="badge {{ $g['items']->count() ? 'badge-amber' : 'badge-gray' }}">{{ $g['items']->count() }}</span>
                    </p>
                    <div class="space-y-1 min-h-[3.5rem]">
                        @forelse ($g['items'] as $item)
                            <p class="text-xs text-slate-500 truncate">• {{ $g['line']($item) }}</p>
                        @empty
                            <p class="text-xs text-slate-300 italic">All clear</p>
                        @endforelse
                    </div>
                    <a href="{{ route($g['route']) }}" class="text-xs font-semibold text-[#1d4ed8] hover:underline mt-2 inline-block">View all →</a>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/admin-dashboard.js') }}"></script>
@endpush

@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <x-admin.page-header title="Dashboard" subtitle="Welcome back, {{ auth()->user()->name }} — here's what's happening." />

    {{-- Stat cards --}}
    @php
        $cards = [
            ['label' => 'New Quotes', 'value' => $stats['quotes_new'], 'hint' => 'Awaiting review',
             'tile' => 'from-blue-500 to-blue-700', 'soft' => 'bg-blue-50 text-blue-600',
             'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
            ['label' => 'Active Projects', 'value' => $stats['projects_active'], 'hint' => 'In pipeline',
             'tile' => 'from-emerald-500 to-emerald-700', 'soft' => 'bg-emerald-50 text-emerald-600',
             'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
            ['label' => 'Revenue · this month', 'value' => 'PKR ' . number_format($stats['revenue_month']), 'hint' => 'Collected',
             'tile' => 'from-amber-500 to-amber-600', 'soft' => 'bg-amber-50 text-amber-600',
             'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label' => 'Unanswered Chats', 'value' => $stats['chats_unanswered'], 'hint' => 'Needs a reply',
             'tile' => 'from-violet-500 to-violet-700', 'soft' => 'bg-violet-50 text-violet-600',
             'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
        ];
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        @foreach ($cards as $card)
            <div class="admin-card stat-card p-5">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-[0.7rem] font-semibold uppercase tracking-wider text-slate-400">{{ $card['label'] }}</p>
                        <p class="text-2xl font-extrabold text-[#0f2a5c] mt-1 truncate">{{ $card['value'] }}</p>
                        <p class="text-xs text-slate-400 mt-1">{{ $card['hint'] }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br {{ $card['tile'] }} text-white flex items-center justify-center shrink-0 shadow-md">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/></svg>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 mb-6">
        <div class="admin-card p-5 xl:col-span-2">
            <div class="flex items-center justify-between mb-1">
                <h3 class="font-bold text-[#0f2a5c]">Quotes & Revenue</h3>
                <span class="badge badge-blue">Last 6 months</span>
            </div>
            <p class="text-xs text-slate-400 mb-3">Enquiries vs collected revenue</p>
            <div id="chart-quotes-revenue"
                 data-labels='@json($quotesByMonth->keys())'
                 data-quotes='@json($quotesByMonth->values())'
                 data-revenue='@json($revenueByMonth->values())'></div>
        </div>
        <div class="admin-card p-5">
            <div class="flex items-center justify-between mb-1">
                <h3 class="font-bold text-[#0f2a5c]">Project Pipeline</h3>
            </div>
            <p class="text-xs text-slate-400 mb-3">Projects by status</p>
            <div id="chart-pipeline"
                 data-labels='@json($projectPipeline->keys())'
                 data-values='@json($projectPipeline->values())'></div>
        </div>
    </div>

    {{-- Needs Attention --}}
    <div class="admin-card p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-bold text-[#0f2a5c]">Needs Attention</h3>
                <p class="text-xs text-slate-400">Items waiting for your action</p>
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

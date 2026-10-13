@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <x-admin.page-header title="Dashboard" subtitle="Welcome back, {{ auth()->user()->name }}!" />

    {{-- Stat cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @php
            $cards = [
                ['label' => 'New Quotes', 'value' => $stats['quotes_new'], 'color' => 'blue', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                ['label' => 'Active Projects', 'value' => $stats['projects_active'], 'color' => 'emerald', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                ['label' => 'Revenue (this month)', 'value' => 'PKR ' . number_format($stats['revenue_month']), 'color' => 'amber', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Unanswered Chats', 'value' => $stats['chats_unanswered'], 'color' => 'purple', 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
            ];
            $shades = ['blue' => 'bg-blue-100 text-blue-600', 'emerald' => 'bg-emerald-100 text-emerald-600', 'amber' => 'bg-amber-100 text-amber-600', 'purple' => 'bg-purple-100 text-purple-600'];
        @endphp
        @foreach ($cards as $card)
            <div class="admin-card stat-card p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl {{ $shades[$card['color']] }} flex items-center justify-center shrink-0">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xl font-extrabold text-[#0f2a5c] truncate">{{ $card['value'] }}</p>
                    <p class="text-xs text-slate-500 font-medium">{{ $card['label'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
        <div class="admin-card p-5 lg:col-span-2">
            <h3 class="font-bold text-[#0f2a5c] mb-1">Quotes & Revenue</h3>
            <p class="text-xs text-slate-400 mb-4">Last 6 months</p>
            <div class="h-64"><canvas id="chart-quotes-revenue"></canvas></div>
        </div>
        <div class="admin-card p-5">
            <h3 class="font-bold text-[#0f2a5c] mb-1">Project Pipeline</h3>
            <p class="text-xs text-slate-400 mb-4">By status</p>
            <div class="h-64"><canvas id="chart-pipeline"></canvas></div>
        </div>
    </div>

    {{-- Needs Attention widget --}}
    <div class="admin-card p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-bold text-[#0f2a5c]">Needs Attention</h3>
                <p class="text-xs text-slate-400">Items waiting for your action</p>
            </div>
            <span class="badge badge-red">{{ $attention['quotes']->count() + $attention['chats']->count() + $attention['invoices']->count() + $attention['demos']->count() + $attention['jobs']->count() }} pending</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">
            @php
                $groups = [
                    ['title' => 'New Quotes', 'items' => $attention['quotes'], 'route' => 'admin.quotes.index', 'line' => fn($q) => $q->name . ' — ' . ($q->subject ?? 'Quote')],
                    ['title' => 'Unanswered Chats', 'items' => $attention['chats'], 'route' => 'admin.chatbot.index', 'line' => fn($c) => \Illuminate\Support\Str::limit($c->query, 45)],
                    ['title' => 'Unpaid Invoices', 'items' => $attention['invoices'], 'route' => 'admin.invoices.index', 'line' => fn($i) => $i->invoice_no . ' — PKR ' . number_format($i->balance(), 0)],
                    ['title' => 'New Demo Requests', 'items' => $attention['demos'], 'route' => 'admin.demo-bookings.index', 'line' => fn($d) => $d->name . ($d->preferred_date ? ' — ' . $d->preferred_date->format('d M') : '')],
                    ['title' => 'New Applications', 'items' => $attention['jobs'], 'route' => 'admin.job-applications.index', 'line' => fn($j) => $j->name],
                ];
            @endphp
            @foreach ($groups as $g)
                <div class="rounded-xl border border-slate-100 bg-slate-50/60 p-3">
                    <p class="text-xs font-bold text-slate-600 mb-2 flex items-center justify-between">
                        {{ $g['title'] }}
                        <span class="badge {{ $g['items']->count() ? 'badge-amber' : 'badge-gray' }}">{{ $g['items']->count() }}</span>
                    </p>
                    @forelse ($g['items'] as $item)
                        <p class="text-xs text-slate-500 truncate py-0.5">• {{ $g['line']($item) }}</p>
                    @empty
                        <p class="text-xs text-slate-300">All clear</p>
                    @endforelse
                    <a href="{{ route($g['route']) }}" class="text-xs font-semibold text-[#1d4ed8] hover:underline mt-2 inline-block">View all →</a>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const sora = { family: 'Sora', size: 11, color: '#64748b' };

    // Quotes vs revenue — bars + line
    new Chart(document.getElementById('chart-quotes-revenue'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($quotesByMonth->keys()) !!},
            datasets: [
                { label: 'Quotes', data: {!! json_encode($quotesByMonth->values()) !!},
                  backgroundColor: '#1d4ed8', borderRadius: 5, barPercentage: 0.55 },
                { label: 'Revenue (PKR)', data: {!! json_encode($revenueByMonth->values()) !!},
                  type: 'line', borderColor: '#f59e0b', backgroundColor: '#f59e0b',
                  tension: 0.4, yAxisID: 'y1' },
            ],
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { labels: sora } },
            scales: {
                x: { ticks: sora, grid: { display: false } },
                y: { ticks: sora, grid: { color: '#eef2fb' } },
                y1: { position: 'right', ticks: sora, grid: { display: false } },
            },
        },
    });

    // Project pipeline — doughnut
    const pipe = @json($projectPipeline);
    new Chart(document.getElementById('chart-pipeline'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(pipe).map(s => s.replace('_', ' ')),
            datasets: [{ data: Object.values(pipe),
                backgroundColor: ['#93c5fd', '#1d4ed8', '#f59e0b', '#8b5cf6', '#10b981'],
                borderWidth: 2, borderColor: '#fff' }],
        },
        options: { responsive: true, maintainAspectRatio: false, cutout: '62%',
            plugins: { legend: { position: 'bottom', labels: sora } } },
    });
});
</script>
@endpush

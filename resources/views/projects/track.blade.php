@extends('layouts.app')

@section('title', $project->title . ' — Project Tracking')

@section('content')
<div class="min-h-screen bg-slate-50 py-10 px-4">
    <div class="max-w-3xl mx-auto space-y-6">

        {{-- Hero --}}
        <div class="rounded-3xl p-8 text-white" style="background: linear-gradient(135deg, #1d4ed8 0%, #12307e 100%);">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-200/70">Project Tracking</p>
            <h1 class="text-2xl md:text-3xl font-extrabold mt-2">{{ $project->title }}</h1>
            <p class="text-blue-200/80 text-sm mt-1.5">Hi {{ $project->client_name }} — follow your project's progress live.</p>

            @php
                $projColors = [
                    'received' => 'bg-slate-500/25 text-slate-100 border border-slate-300/40',
                    'in_progress' => 'bg-blue-400/30 text-blue-100 border border-blue-300/40',
                    'review' => 'bg-amber-500/25 text-amber-100 border border-amber-300/40',
                    'revision' => 'bg-orange-500/25 text-orange-100 border border-orange-300/40',
                    'delivered' => 'bg-green-500/25 text-green-100 border border-green-300/40',
                ];
            @endphp
            <div class="mt-6">
                <div class="flex items-center justify-between text-sm font-bold mb-2">
                    <span class="inline-block px-3 py-1 rounded-full text-xs {{ $projColors[$project->status] ?? $projColors['received'] }}">{{ $project->statusLabel() }}</span>
                    <span>{{ $project->progress }}% complete</span>
                </div>
                @php
                    $pColor = $project->progress >= 75 ? '#4ade80' : ($project->progress >= 50 ? '#60a5fa' : ($project->progress >= 25 ? '#fbbf24' : '#f87171'));
                @endphp
                <div class="h-3 bg-white/15 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500" style="width: {{ $project->progress }}%; background-color: {{ $pColor }};"></div>
                </div>
            </div>

            @if($project->deadline)
            <p class="text-xs text-blue-200/70 mt-4">Expected delivery: <strong class="text-white">{{ $project->deadline->format('M d, Y') }}</strong></p>
            @endif
        </div>

        {{-- Pipeline --}}
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-100">
            <h2 class="text-lg font-extrabold text-slate-900 mb-5">Project Pipeline</h2>
            @php
                $stages = ['received' => 'Received', 'in_progress' => 'In Progress', 'review' => 'Review', 'revision' => 'Revision', 'delivered' => 'Delivered'];
                $order = array_keys($stages);
                $currentIdx = array_search($project->status, $order);
                $currentIdx = $currentIdx === false ? 0 : $currentIdx;
            @endphp
            <div class="flex items-center">
                @foreach($stages as $key => $label)
                <div class="flex-1 text-center relative">
                    @if(!$loop->first)
                    <div class="absolute top-4 right-1/2 w-full h-0.5 {{ $loop->index <= $currentIdx ? 'bg-blue-600' : 'bg-slate-200' }}"></div>
                    @endif
                    <div class="relative z-10 w-8 h-8 mx-auto rounded-full flex items-center justify-center text-xs font-extrabold
                                {{ $loop->index < $currentIdx ? 'bg-blue-600 text-white' : ($loop->index === $currentIdx ? 'bg-blue-600 text-white ring-4 ring-blue-100' : 'bg-slate-200 text-slate-500') }}">
                        @if($loop->index < $currentIdx)
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        @else
                        {{ $loop->index + 1 }}
                        @endif
                    </div>
                    <p class="text-[0.7rem] font-bold mt-2 {{ $loop->index <= $currentIdx ? 'text-blue-700' : 'text-slate-400' }}">{{ $label }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Updates timeline --}}
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-100">
            <h2 class="text-lg font-extrabold text-slate-900 mb-6">Latest Updates</h2>
            @forelse($project->visibleUpdates as $update)
            <div class="flex gap-4 group">
                <div class="flex flex-col items-center">
                    <div class="w-3.5 h-3.5 rounded-full bg-blue-600 mt-1 shrink-0 {{ $loop->first ? 'ring-4 ring-blue-100' : '' }}"></div>
                    @if(!$loop->last)<div class="w-0.5 flex-1 bg-slate-200 my-1"></div>@endif
                </div>
                <div class="flex-1 pb-7">
                    <p class="font-bold text-slate-900">{{ $update->title }}</p>
                    @if($update->note)
                    <p class="text-sm text-slate-500 mt-1.5 leading-relaxed">{{ $update->note }}</p>
                    @endif
                    <p class="text-xs text-slate-400 mt-2">{{ $update->created_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-8">
                <p class="text-slate-400 text-sm">No updates yet — check back soon!</p>
            </div>
            @endforelse
        </div>

        {{-- Project invoices --}}
        @if($project->invoices->isNotEmpty())
        @php
            $invBadge = [
                'unpaid' => 'bg-red-100 text-red-700',
                'partially_paid' => 'bg-amber-100 text-amber-700',
                'paid' => 'bg-green-100 text-green-700',
                'overdue' => 'bg-orange-100 text-orange-700',
                'cancelled' => 'bg-slate-100 text-slate-500',
            ];
        @endphp
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-100">
            <h2 class="text-lg font-extrabold text-slate-900 mb-5">Invoices</h2>
            <div class="space-y-3">
                @foreach($project->invoices as $invoice)
                <a href="{{ route('invoices.show', $invoice->token) }}"
                   class="flex items-center justify-between gap-3 p-4 rounded-2xl border border-slate-100 hover:border-blue-300 hover:shadow-md transition">
                    <div class="min-w-0">
                        <p class="font-bold text-slate-900 text-sm truncate">{{ $invoice->invoice_no }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ $invoice->title }}</p>
                        <p class="text-xs font-bold text-slate-700 mt-1">${{ number_format($invoice->amount, 2) }}
                            @if($invoice->balance() > 0)<span class="text-slate-400 font-medium">· Balance ${{ number_format($invoice->balance(), 2) }}</span>@endif
                        </p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <span class="px-2.5 py-1 rounded-full text-[0.7rem] font-bold {{ $invBadge[$invoice->status] ?? $invBadge['unpaid'] }}">{{ $invoice->statusLabel() }}</span>
                        <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Footer note --}}
        <p class="text-center text-xs text-slate-400">
            Questions about your project? Reply to our emails and we'll get back to you.
        </p>
    </div>
</div>
@endsection

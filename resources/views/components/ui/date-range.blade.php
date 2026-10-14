{{-- x-ui.date-range — two linked x-ui.date pickers (start / end).
     Choosing a start constrains the end (and vice versa) via ui-components.js.
     Props: nameStart, nameEnd, label, valueStart, valueEnd, required, hint, error. --}}
@props([
    'nameStart' => 'start_date',
    'nameEnd' => 'end_date',
    'label' => null,
    'valueStart' => null,
    'valueEnd' => null,
    'required' => false,
    'hint' => null,
    'error' => null,
    'errorKey' => null,
    'id' => null,
])
@php
    $id = $id ?? 'ui-range-' . uniqid();
    $startId = $id . '-start';
    $endId = $id . '-end';
    $error = $error ?? $errors->first($errorKey ?? $nameStart);
    $fmt = function ($iso) {
        if ($iso && preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $iso, $m)) {
            return $m[3] . ' ' . ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'][(int) $m[2] - 1] . ' ' . $m[1];
        }
        return '';
    };
    $displayStart = $fmt(old($nameStart, $valueStart));
    $displayEnd = $fmt(old($nameEnd, $valueEnd));
@endphp
<div class="ui-field">
    @if ($label)
        <span class="ui-label">{{ $label }}@if ($required)<span class="ui-required">*</span>@endif</span>
    @endif
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div class="ui-date" id="{{ $startId }}" data-link-min="{{ $endId }}">
            <div class="ui-input-wrap">
                <span class="ui-icon-left">
                    <svg class="w-[1.05rem] h-[1.05rem]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </span>
                <input type="text" readonly class="ui-control ui-date-display ui-has-icon-left {{ $error ? 'ui-control-error' : '' }}" placeholder="Start date" value="{{ $displayStart }}">
            </div>
            <input type="hidden" name="{{ $nameStart }}" class="ui-date-hidden" value="{{ old($nameStart, $valueStart) }}">
            <div class="ui-cal hidden">
                <div class="ui-cal-head">
                    <div class="ui-cal-title"></div>
                    <div class="ui-cal-nav">
                        <button type="button" class="ui-cal-prev" aria-label="Previous month"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></button>
                        <button type="button" class="ui-cal-next" aria-label="Next month"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>
                    </div>
                </div>
                <div class="ui-cal-grid"></div>
                <div class="ui-cal-foot">
                    <button type="button" class="ui-cal-today">Today</button>
                    <button type="button" class="ui-cal-clear ui-clear">Clear</button>
                </div>
            </div>
        </div>
        <div class="ui-date" id="{{ $endId }}" data-link-max="{{ $startId }}">
            <div class="ui-input-wrap">
                <span class="ui-icon-left">
                    <svg class="w-[1.05rem] h-[1.05rem]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </span>
                <input type="text" readonly class="ui-control ui-date-display ui-has-icon-left {{ $error ? 'ui-control-error' : '' }}" placeholder="End date" value="{{ $displayEnd }}">
            </div>
            <input type="hidden" name="{{ $nameEnd }}" class="ui-date-hidden" value="{{ old($nameEnd, $valueEnd) }}">
            <div class="ui-cal hidden">
                <div class="ui-cal-head">
                    <div class="ui-cal-title"></div>
                    <div class="ui-cal-nav">
                        <button type="button" class="ui-cal-prev" aria-label="Previous month"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></button>
                        <button type="button" class="ui-cal-next" aria-label="Next month"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>
                    </div>
                </div>
                <div class="ui-cal-grid"></div>
                <div class="ui-cal-foot">
                    <button type="button" class="ui-cal-today">Today</button>
                    <button type="button" class="ui-cal-clear ui-clear">Clear</button>
                </div>
            </div>
        </div>
    </div>
    @if ($error)
        <p class="ui-error">{{ $error }}</p>
    @elseif ($hint)
        <p class="ui-hint">{{ $hint }}</p>
    @endif
</div>

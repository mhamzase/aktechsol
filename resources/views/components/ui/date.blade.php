{{-- x-ui.date — custom calendar date picker (NOT a native date input).
     Readonly display field ("14 Oct 2026") + hidden Y-m-d value for the form.
     Props: name, label, value (Y-m-d), placeholder, required, hint, error,
            min (Y-m-d), max (Y-m-d), id. --}}
@props([
    'name',
    'label' => null,
    'value' => null,
    'placeholder' => 'Pick a date',
    'required' => false,
    'hint' => null,
    'error' => null,
    'errorKey' => null,
    'min' => null,
    'max' => null,
    'id' => null,
])
@php
    $id = $id ?? 'ui-' . str_replace(['[', ']'], ['-', ''], $name) . '-' . uniqid();
    $error = $error ?? $errors->first($errorKey ?? $name);
    $iso = old($name, $value);
    $display = '';
    if ($iso && preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $iso, $m)) {
        $display = $m[3] . ' ' . ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'][(int) $m[2] - 1] . ' ' . $m[1];
    }
@endphp
<div class="ui-field">
    @if ($label)
        <label for="{{ $id }}-display" class="ui-label">{{ $label }}@if ($required)<span class="ui-required">*</span>@endif</label>
    @endif
    <div class="ui-date" id="{{ $id }}"
         @if ($min) data-min="{{ $min }}" @endif
         @if ($max) data-max="{{ $max }}" @endif>
        <div class="ui-input-wrap">
            <span class="ui-icon-left">
                <svg class="w-[1.05rem] h-[1.05rem]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </span>
            <input type="text" id="{{ $id }}-display" readonly
                   class="ui-control ui-date-display ui-has-icon-left {{ $error ? 'ui-control-error' : '' }}"
                   placeholder="{{ $placeholder }}" value="{{ $display }}"
                   {{ $attributes }}>
        </div>
        <input type="hidden" name="{{ $name }}" class="ui-date-hidden" value="{{ $iso }}">
        <div class="ui-cal hidden">
            <div class="ui-cal-head">
                <div class="ui-cal-title"></div>
                <div class="ui-cal-nav">
                    <button type="button" class="ui-cal-prev" aria-label="Previous month">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button type="button" class="ui-cal-next" aria-label="Next month">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>
            <div class="ui-cal-grid"></div>
            <div class="ui-cal-foot">
                <button type="button" class="ui-cal-today">Today</button>
                <button type="button" class="ui-cal-clear ui-clear">Clear</button>
            </div>
        </div>
    </div>
    @if ($error)
        <p class="ui-error">{{ $error }}</p>
    @elseif ($hint)
        <p class="ui-hint">{{ $hint }}</p>
    @endif
</div>

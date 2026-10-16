{{-- x-ui.search-select — searchable single select (custom dropdown).
     Props: name, label, options ([value => label]), value, placeholder,
            required, hint, error, id. --}}
@props([
    'name',
    'label' => null,
    'options' => [],
    'value' => null,
    'placeholder' => 'Search…',
    'required' => false,
    'hint' => null,
    'error' => null,
    'errorKey' => null,
    'id' => null,
])
@php
    $id = $id ?? 'ui-' . str_replace(['[', ']'], ['-', ''], $name) . '-' . uniqid();
    $error = $error ?? $errors->first($errorKey ?? $name);
    $current = old($name, $value);
    $norm = [];
    foreach ($options as $k => $v) {
        if (is_array($v)) { $norm[$v['value']] = $v['label']; }
        else { $norm[$k] = $v; }
    }
    $currentLabel = $norm[$current] ?? null;
@endphp
<div class="ui-field" data-search-select>
    @if ($label)
        <span class="ui-label">{{ $label }}@if ($required)<span class="ui-required">*</span>@endif</span>
    @endif
    <div class="relative">
        <input type="hidden" name="{{ $name }}" value="{{ $current }}" data-search-select-value>
        <button type="button" data-search-select-toggle
                class="ui-control w-full text-left flex items-center justify-between gap-2 {{ $error ? 'ui-control-error' : '' }}">
            <span data-search-select-label class="{{ $currentLabel ? '' : 'text-slate-400' }}">{{ $currentLabel ?? $placeholder }}</span>
            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div data-search-select-menu class="hidden absolute z-50 mt-1 w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-xl overflow-hidden">
            <div class="p-2 border-b border-slate-100 dark:border-slate-700">
                <input type="text" data-search-select-search placeholder="Type to search…"
                       class="w-full px-3 py-2 text-sm bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            <div class="max-h-52 overflow-y-auto py-1" data-search-select-list>
                @foreach($norm as $val => $label)
                <button type="button" data-search-select-option data-value="{{ $val }}" data-label="{{ $label }}"
                        class="w-full text-left px-4 py-2.5 text-sm hover:bg-blue-50 dark:hover:bg-slate-700 transition {{ (string)$val === (string)$current ? 'bg-blue-50 dark:bg-slate-700 font-bold text-blue-700 dark:text-blue-300' : 'text-slate-700 dark:text-slate-200' }}">
                    {{ $label }}
                </button>
                @endforeach
                <p data-search-select-empty class="hidden px-4 py-3 text-sm text-slate-400 text-center">No matches found.</p>
            </div>
        </div>
    </div>
    @if ($error)
        <p class="ui-error">{{ $error }}</p>
    @elseif ($hint)
        <p class="ui-hint">{{ $hint }}</p>
    @endif
</div>

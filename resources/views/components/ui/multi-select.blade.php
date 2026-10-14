{{-- x-ui.multi-select — searchable dropdown with removable tags.
     Submits as hidden inputs (name[] per selected value).
     Props: name (e.g. "tags[]"), label, options ([value => label]),
            selected ([values]), placeholder, searchable, required, hint, error. --}}
@props([
    'name',
    'label' => null,
    'options' => [],
    'selected' => [],
    'placeholder' => 'Select…',
    'searchable' => true,
    'required' => false,
    'hint' => null,
    'error' => null,
    'errorKey' => null,
])
@php
    $error = $error ?? $errors->first($errorKey ?? rtrim($name, '[]'));
    // Normalize to [{value, label}].
    $norm = [];
    foreach ($options as $k => $v) {
        $norm[] = is_array($v) ? $v : ['value' => $k, 'label' => $v];
    }
    $current = old(rtrim($name, '[]'), $selected);
    $current = is_array($current) ? array_values($current) : [$current];
@endphp
<div class="ui-field">
    @if ($label)
        <span class="ui-label">{{ $label }}@if ($required)<span class="ui-required">*</span>@endif</span>
    @endif
    <div class="ui-ms" data-name="{{ $name }}"
         data-options='@json($norm)'
         data-selected='@json($current)'
         data-placeholder="{{ $placeholder }}"
         data-searchable="{{ $searchable ? '1' : '0' }}">
        <div class="ui-ms-btn {{ $error ? 'ui-control-error' : '' }}" tabindex="0" role="combobox" aria-expanded="false"></div>
        <svg class="ui-ms-chev w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        <div class="ui-ms-drop hidden">
            @if ($searchable)
                <input type="text" class="ui-ms-search" placeholder="Search…" autocomplete="off">
            @endif
            <div class="ui-ms-list" role="listbox"></div>
        </div>
        <div class="ui-ms-hidden"></div>
    </div>
    @if ($error)
        <p class="ui-error">{{ $error }}</p>
    @elseif ($hint)
        <p class="ui-hint">{{ $hint }}</p>
    @endif
</div>

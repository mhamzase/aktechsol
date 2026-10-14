{{-- x-ui.select — themed single select.
     Props: name, label, options ([value => label] or [{value,label}]), value,
            placeholder (empty option text, null = no empty option),
            required, hint, error, disabled, id. --}}
@props([
    'name',
    'label' => null,
    'options' => [],
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'hint' => null,
    'error' => null,
    'errorKey' => null,
    'disabled' => false,
    'id' => null,
])
@php
    $id = $id ?? 'ui-' . str_replace(['[', ']'], ['-', ''], $name) . '-' . uniqid();
    $error = $error ?? $errors->first($errorKey ?? $name);
    $current = old($name, $value);
    // Normalize options to [value => label].
    $norm = [];
    foreach ($options as $k => $v) {
        if (is_array($v)) { $norm[$v['value']] = $v['label']; }
        else { $norm[$k] = $v; }
    }
@endphp
<div class="ui-field">
    @if ($label)
        <label for="{{ $id }}" class="ui-label">{{ $label }}@if ($required)<span class="ui-required">*</span>@endif</label>
    @endif
    <select name="{{ $name }}" id="{{ $id }}"
            @if ($required) required @endif
            @if ($disabled) disabled @endif
            {{ $attributes->merge(['class' => 'ui-control' . ($error ? ' ui-control-error' : '')]) }}>
        @if ($placeholder !== null)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($norm as $optValue => $optLabel)
            <option value="{{ $optValue }}" @selected((string) $current === (string) $optValue)>{{ $optLabel }}</option>
        @endforeach
    </select>
    @if ($error)
        <p class="ui-error">{{ $error }}</p>
    @elseif ($hint)
        <p class="ui-hint">{{ $hint }}</p>
    @endif
</div>

{{-- x-ui.input — themed text input (text, email, number, tel, url, search).
     Props: name, label, type, value, placeholder, required, hint, error, disabled,
            icon (left svg path), prefix / suffix (plain text), id. --}}
@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'hint' => null,
    'error' => null,
    'errorKey' => null,
    'disabled' => false,
    'icon' => null,
    'prefix' => null,
    'suffix' => null,
    'id' => null,
])
@php
    $id = $id ?? 'ui-' . str_replace(['[', ']'], ['-', ''], $name) . '-' . uniqid();
    $error = $error ?? $errors->first($errorKey ?? $name);
@endphp
<div class="ui-field">
    @if ($label)
        <label for="{{ $id }}" class="ui-label">{{ $label }}@if ($required)<span class="ui-required">*</span>@endif</label>
    @endif
    <div class="ui-input-wrap">
        @if ($icon)
            <span class="ui-icon-left">
                <svg class="w-[1.05rem] h-[1.05rem]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/></svg>
            </span>
        @endif
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}"
               value="{{ old($name, $value) }}"
               placeholder="{{ $placeholder }}"
               @if ($required) required @endif
               @if ($disabled) disabled @endif
               {{ $attributes->merge(['class' => 'ui-control' . ($icon ? ' ui-has-icon-left' : '') . ($error ? ' ui-control-error' : '')]) }}>
    </div>
    @if ($error)
        <p class="ui-error">{{ $error }}</p>
    @elseif ($hint)
        <p class="ui-hint">{{ $hint }}</p>
    @endif
</div>

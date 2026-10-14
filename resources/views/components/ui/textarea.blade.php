{{-- x-ui.textarea — themed multi-line textarea.
     Props: name, label, value, rows, placeholder, required, hint, error, id. --}}
@props([
    'name',
    'label' => null,
    'value' => null,
    'rows' => 4,
    'placeholder' => null,
    'required' => false,
    'hint' => null,
    'error' => null,
    'errorKey' => null,
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
    <textarea name="{{ $name }}" id="{{ $id }}" rows="{{ $rows }}"
              placeholder="{{ $placeholder }}"
              @if ($required) required @endif
              {{ $attributes->merge(['class' => 'ui-control' . ($error ? ' ui-control-error' : '')]) }}>{{ old($name, $value) }}</textarea>
    @if ($error)
        <p class="ui-error">{{ $error }}</p>
    @elseif ($hint)
        <p class="ui-hint">{{ $hint }}</p>
    @endif
</div>

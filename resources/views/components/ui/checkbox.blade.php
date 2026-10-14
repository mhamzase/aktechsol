{{-- x-ui.checkbox — themed checkbox.
     Props: name, label, description, checked, value, required, hint, error, id. --}}
@props([
    'name',
    'label' => null,
    'description' => null,
    'checked' => false,
    'value' => '1',
    'required' => false,
    'hint' => null,
    'error' => null,
    'errorKey' => null,
    'id' => null,
])
@php
    $id = $id ?? 'ui-' . str_replace(['[', ']'], ['-', ''], $name) . '-' . uniqid();
    $error = $error ?? $errors->first($errorKey ?? $name);
    $isChecked = (bool) old($name, $checked);
@endphp
<div class="ui-field">
    <label class="ui-check" for="{{ $id }}">
        <input type="checkbox" name="{{ $name }}" id="{{ $id }}" value="{{ $value }}"
               @checked($isChecked) @if ($required) required @endif {{ $attributes }}>
        <span class="ui-box">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"/></svg>
        </span>
        <span>
            @if ($label)<span class="ui-check-label">{{ $label }}</span>@endif
            @if ($description)<span class="ui-check-desc block">{{ $description }}</span>@endif
        </span>
    </label>
    @if ($error)
        <p class="ui-error">{{ $error }}</p>
    @elseif ($hint)
        <p class="ui-hint">{{ $hint }}</p>
    @endif
</div>

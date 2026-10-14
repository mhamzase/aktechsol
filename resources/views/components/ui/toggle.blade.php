{{-- x-ui.toggle — switch for active/inactive (or any boolean).
     Props: name, label (beside the switch), checked, value, hint, error, id. --}}
@props([
    'name',
    'label' => null,
    'checked' => false,
    'value' => '1',
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
    <label class="ui-toggle" for="{{ $id }}">
        <input type="checkbox" name="{{ $name }}" id="{{ $id }}" value="{{ $value }}"
               @checked($isChecked) {{ $attributes }}>
        <span class="ui-track"></span>
        @if ($label)<span class="ui-toggle-label">{{ $label }}</span>@endif
    </label>
    @if ($error)
        <p class="ui-error">{{ $error }}</p>
    @elseif ($hint)
        <p class="ui-hint">{{ $hint }}</p>
    @endif
</div>

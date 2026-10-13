{{-- x-ui.password — password input with show/hide eye toggle.
     Props: name, label, placeholder, required, hint, error, id, autocomplete. --}}
@props([
    'name' => 'password',
    'label' => null,
    'placeholder' => null,
    'required' => false,
    'hint' => null,
    'error' => null,
    'errorKey' => null,
    'autocomplete' => 'new-password',
    'id' => null,
])
@php
    $id = $id ?? 'ui-password-' . uniqid();
    $error = $error ?? $errors->first($errorKey ?? $name);
@endphp
<div class="ui-field">
    @if ($label)
        <label for="{{ $id }}" class="ui-label">{{ $label }}@if ($required)<span class="ui-required">*</span>@endif</label>
    @endif
    <div class="ui-input-wrap">
        <input type="password" name="{{ $name }}" id="{{ $id }}"
               placeholder="{{ $placeholder }}"
               autocomplete="{{ $autocomplete }}"
               @if ($required) required @endif
               {{ $attributes->merge(['class' => 'ui-control ui-has-icon-right' . ($error ? ' ui-control-error' : '')]) }}>
        <span class="ui-icon-right">
            <button type="button" class="ui-icon-btn" data-ui-password-toggle aria-label="Show password">
                <svg class="w-[1.05rem] h-[1.05rem]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </button>
        </span>
    </div>
    @if ($error)
        <p class="ui-error">{{ $error }}</p>
    @elseif ($hint)
        <p class="ui-hint">{{ $hint }}</p>
    @endif
</div>

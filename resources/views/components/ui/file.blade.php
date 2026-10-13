{{-- x-ui.file — styled file input with live preview (images) / name chips.
     Props: name, label, accept, multiple, required, hint, error, id. --}}
@props([
    'name',
    'label' => null,
    'accept' => null,
    'multiple' => false,
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
        <span class="ui-label">{{ $label }}@if ($required)<span class="ui-required">*</span>@endif</span>
    @endif
    <div class="ui-file">
        <label for="{{ $id }}" class="ui-file-drop {{ $error ? 'ui-control-error' : '' }}">
            <span class="ui-file-icon flex justify-center">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
            </span>
            <p><strong>Click to upload</strong> or drag and drop</p>
            @if ($accept)<p class="mt-1 text-xs">{{ $accept }}</p>@endif
        </label>
        <input type="file" name="{{ $name }}{{ $multiple ? '[]' : '' }}" id="{{ $id }}"
               @if ($accept) accept="{{ $accept }}" @endif
               @if ($multiple) multiple @endif
               @if ($required) required @endif
               class="hidden" {{ $attributes }}>
        <div class="ui-file-preview"></div>
    </div>
    @if ($error)
        <p class="ui-error">{{ $error }}</p>
    @elseif ($hint)
        <p class="ui-hint">{{ $hint }}</p>
    @endif
</div>

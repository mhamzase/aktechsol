{{-- x-ui.rich-text — Quill rich-text editor (local, no CDN).
     Syncs HTML into a hidden input on typing + on form submit.
     Props: name, label, value (HTML), placeholder, required, hint, error, id. --}}
@props([
    'name',
    'label' => null,
    'value' => null,
    'placeholder' => 'Write something…',
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
    <div class="ui-rich" data-placeholder="{{ $placeholder }}">
        <div data-quill-editor></div>
        <input type="hidden" name="{{ $name }}" data-quill-input @if ($required) data-rich-required="1" @endif value="{{ old($name, $value) }}">
    </div>
    @if ($error)
        <p class="ui-error">{{ $error }}</p>
    @elseif ($hint)
        <p class="ui-hint">{{ $hint }}</p>
    @endif
</div>

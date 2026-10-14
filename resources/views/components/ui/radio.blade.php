{{-- x-ui.radio — themed radio group.
     Props: name, label, options ([value => label]), value, inline (bool),
            required, hint, error. --}}
@props([
    'name',
    'label' => null,
    'options' => [],
    'value' => null,
    'inline' => false,
    'required' => false,
    'hint' => null,
    'error' => null,
    'errorKey' => null,
])
@php
    $error = $error ?? $errors->first($errorKey ?? $name);
    $current = old($name, $value);
    $norm = [];
    foreach ($options as $k => $v) {
        $norm[] = is_array($v) ? $v : ['value' => $k, 'label' => $v];
    }
@endphp
<div class="ui-field">
    @if ($label)
        <span class="ui-label">{{ $label }}@if ($required)<span class="ui-required">*</span>@endif</span>
    @endif
    <div class="ui-check-group {{ $inline ? 'ui-inline' : '' }}">
        @foreach ($norm as $opt)
            <label class="ui-check">
                <input type="radio" name="{{ $name }}" value="{{ $opt['value'] }}"
                       @checked((string) $current === (string) $opt['value'])
                       @if ($required) required @endif {{ $attributes }}>
                <span class="ui-box">
                    <svg viewBox="0 0 8 8"><circle cx="4" cy="4" r="4" fill="currentColor"/></svg>
                </span>
                <span class="ui-check-label">{{ $opt['label'] }}</span>
            </label>
        @endforeach
    </div>
    @if ($error)
        <p class="ui-error">{{ $error }}</p>
    @elseif ($hint)
        <p class="ui-hint">{{ $hint }}</p>
    @endif
</div>

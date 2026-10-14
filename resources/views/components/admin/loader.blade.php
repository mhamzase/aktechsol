{{--
    x-admin.loader — the ONE shared blue loader for the whole admin panel.
    Use everywhere something async loads: tables, drawers, cards, buttons.
    Props: text (default 'Loading…', pass empty string for ring only),
           size: sm | md | lg (default md).
--}}
@props(['text' => 'Loading…', 'size' => 'md'])
<div {{ $attributes->merge(['class' => 'admin-loader al-' . $size]) }} role="status" aria-live="polite">
    <span class="al-ring" aria-hidden="true"></span>
    @if ($text !== '')
        <span>{{ $text }}</span>
    @endif
</div>

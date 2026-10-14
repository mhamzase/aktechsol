{{--
    x-admin.drawer-footer — THE standard footer for every drawer form.
    Right-aligned Cancel + Save buttons with a top divider.
    Props: saveLabel (default 'Save Changes'), cancelLabel (default 'Cancel').
--}}
@props(['saveLabel' => 'Save Changes', 'cancelLabel' => 'Cancel'])
<div class="flex justify-end gap-3 pt-4 mt-1 border-t border-slate-200 dark:border-white/10">
    <button type="button" class="btn btn-secondary" data-drawer-close>{{ $cancelLabel }}</button>
    <button type="submit" class="btn btn-primary">{{ $saveLabel }}</button>
</div>

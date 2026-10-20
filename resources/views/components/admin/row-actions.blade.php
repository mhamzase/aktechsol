{{--
    x-admin.row-actions — THE standard row-action menu for every admin table.
    3-dots button opening a dropdown with icon items. Same UI/UX everywhere.

    Props:
      editUrl    — (optional) drawer URL for the Edit item
      editTitle  — drawer title (default 'Edit')
      deleteUrl  — (optional) URL for the Delete item (confirmDelete flow)
      tableId    — table DOM id to reload after delete
--}}
@props([
    'editUrl' => null,
    'editTitle' => 'Edit',
    'editWide' => false,
    'viewUrl' => null,
    'viewTitle' => 'View',
    'permissionsUrl' => null,
    'permissionsTitle' => 'Permissions',
    'revenueUrl' => null,
    'revenueTitle' => 'Distribute Revenue',
    'deleteUrl' => null,
    'tableId' => null,
])
<div class="row-actions" data-row-actions>
    <button type="button" class="row-actions-btn" data-row-actions-toggle aria-label="Actions" title="Actions">
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="12" cy="5" r="1.9"/><circle cx="12" cy="12" r="1.9"/><circle cx="12" cy="19" r="1.9"/></svg>
    </button>
    <div class="row-actions-menu hidden" role="menu">
        @if ($viewUrl)
            <button type="button" role="menuitem" data-row-edit="{{ $viewUrl }}" data-drawer-title="{{ $viewTitle }}" title="{{ $viewTitle }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <span>{{ $viewTitle }}</span>
            </button>
        @endif
        @if ($editUrl)
            <button type="button" role="menuitem" data-row-edit="{{ $editUrl }}" data-drawer-title="{{ $editTitle }}" @if($editWide)data-drawer-wide="1"@endif>
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>Edit</span>
            </button>
        @endif
        @if ($permissionsUrl)
            <button type="button" role="menuitem" data-row-edit="{{ $permissionsUrl }}" data-drawer-title="{{ $permissionsTitle }}" data-drawer-wide="1">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <span>Permissions</span>
            </button>
        @endif
        @if ($revenueUrl)
            <button type="button" role="menuitem" data-row-edit="{{ $revenueUrl }}" data-drawer-title="{{ $revenueTitle }}" title="{{ $revenueTitle }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ $revenueTitle }}</span>
            </button>
        @endif
        @if ($deleteUrl)
            <button type="button" role="menuitem" class="danger" data-row-delete="{{ $deleteUrl }}" data-table="{{ $tableId }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                <span>Delete</span>
            </button>
        @endif
    </div>
</div>

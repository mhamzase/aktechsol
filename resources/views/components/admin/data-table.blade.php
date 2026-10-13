{{--
    Shared AJAX data-table — THE listing pattern for the whole admin panel.
    Identical UI/UX everywhere: toolbar (search + per-page + stats), table
    card, AJAX pagination. On mobile, rows become stacked label:value cards
    (each <td> in row partials MUST carry a data-label attribute).

    Props:
      id        — unique DOM id, used by reloadAdminTable('id')
      ajaxUrl   — index route returning JSON {rows, pagination, stats} for AJAX
      columns   — array of <th> labels
      perPage   — default rows per page
      createUrl — (optional) "Add new" drawer button URL
      createTitle — drawer title
      searchPlaceholder
--}}
@props([
    'id',
    'ajaxUrl',
    'columns' => [],
    'perPage' => 10,
    'createUrl' => null,
    'createTitle' => 'Add New',
    'searchPlaceholder' => 'Search…',
])

<div id="{{ $id }}" data-admin-table data-ajax-url="{{ $ajaxUrl }}" class="admin-card overflow-hidden">
    {{-- Toolbar: search + per-page + stats + create --}}
    <div class="flex flex-wrap items-center gap-3 px-4 py-3 border-b border-slate-100">
        <div class="relative flex-1 min-w-[12rem]">
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" data-search placeholder="{{ $searchPlaceholder }}"
                   class="form-control !pl-9" autocomplete="off">
        </div>
        <select data-per-page class="form-control !w-auto">
            @foreach ([10, 25, 50, 100] as $n)
                <option value="{{ $n }}" @selected($n == $perPage)>{{ $n }} / page</option>
            @endforeach
        </select>
        <span data-stats class="text-xs text-slate-400 ml-auto"></span>
        @if ($createUrl)
            <button class="btn btn-primary" onclick="openDrawer('{{ $createUrl }}', '{{ $createTitle }}')">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add New
            </button>
        @endif
    </div>

    {{-- Table --}}
    <div class="data-table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    @foreach ($columns as $col)
                        <th>{{ $col }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody data-rows>
                <tr><td colspan="{{ count($columns) ?: 1 }}" class="text-center py-8 text-slate-400">Loading…</td></tr>
            </tbody>
        </table>
    </div>

    {{-- Pagination (AJAX-rendered) --}}
    <div data-pagination class="px-4 py-3 border-t border-slate-100 flex items-center justify-center"></div>
</div>

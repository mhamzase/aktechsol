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
      searchPlaceholder
      Slot "filters" — (optional) extra filter inputs rendered in the toolbar.
                       Each input/select needs data-table-filter="paramName".
    NOTE: the "Add New" button lives in x-admin.page-header's actions slot,
          NOT in the table toolbar.
--}}
@props([
    'id',
    'ajaxUrl',
    'columns' => [],
    'perPage' => 10,
    'searchPlaceholder' => 'Search…',
])

<div id="{{ $id }}" data-admin-table data-ajax-url="{{ $ajaxUrl }}" class="admin-card overflow-hidden">
    {{-- Toolbar: per-page (left), search (right) — tailor-app style --}}
    <div class="flex flex-wrap items-center gap-x-4 gap-y-3 px-4 py-3 border-b border-slate-100">
        <label class="flex items-center gap-2 text-sm text-slate-500">
            <span>Show</span>
            <select data-per-page class="form-control !w-auto !py-1.5 cursor-pointer">
                @foreach ([10, 25, 50, 100] as $n)
                    <option value="{{ $n }}" @selected($n == $perPage)>{{ $n }}</option>
                @endforeach
            </select>
            <span>entries</span>
        </label>
        @isset($filters)
            {{ $filters }}
        @endisset
        <div class="relative ml-auto w-full sm:w-72">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none" aria-hidden="true">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </span>
            <input type="text" data-search placeholder="{{ $searchPlaceholder }}"
                   class="form-control !pl-9 !pr-9 !bg-slate-50 focus:!bg-white transition-colors" autocomplete="off">
            <button type="button" data-clear-search class="hidden absolute inset-y-0 right-0 flex items-center pr-3 text-slate-300 hover:text-slate-500" aria-label="Clear search">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
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
                <tr><td colspan="{{ count($columns) ?: 1 }}" class="text-center py-10"><x-admin.loader size="lg" /></td></tr>
            </tbody>
        </table>
    </div>

    {{-- Pagination (AJAX-rendered, includes the Showing X–Y of Z count) --}}
    <div data-pagination class="px-4 py-3 border-t border-slate-100"></div>
</div>

@props([
    'showing',
    'pages' => 'pageNumbers',
    'pageExpr' => 'setPage(pg)',
    'activeExpr' => 'pg === page',
    'prevClick' => 'page--',
    'nextClick' => 'page++',
    'prevDisabled' => 'page <= 1',
    'nextDisabled' => 'page >= totalPages',
    'keyPrefix' => 'dpg',
    'textExpr' => 'pg',
    'perPage' => null,
    'perPageReset' => 'page = 1',
    'perPageOptions' => [5, 10, 15, 20, 30, 50],
])
<div class="border-t border-slate-200/80 bg-slate-50/40 px-5 py-3 flex flex-col lg:flex-row items-center justify-between gap-3">
    <div class="flex flex-wrap items-center gap-x-3 gap-y-2 text-[11px] text-slate-500 font-medium">
        @if ($perPage)
        <div class="flex items-center gap-2 whitespace-nowrap">
            <span>Rows per page:</span>
            <select x-model.number="{{ $perPage }}" x-on:change="{{ $perPageReset }}" class="text-xs p-1.5 border border-slate-300 rounded-lg outline-none bg-white font-medium text-slate-700 focus:ring-2 focus:ring-dict-blue">
                @foreach ($perPageOptions as $n)
                <option :value="{{ $n }}" x-text="{{ $n }}"></option>
                @endforeach
            </select>
        </div>
        @endif
        <span class="whitespace-nowrap" x-text="{{ $showing }}"></span>
    </div>
    <div class="flex items-center gap-2">
        <div class="flex items-center gap-1">
            <button x-on:click="{{ $prevClick }}" :disabled="{{ $prevDisabled }}" title="Previous" class="w-7 h-7 flex items-center justify-center rounded-lg text-[11px] font-bold transition border border-slate-200 text-slate-600 hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed">
                <i class="fa-solid fa-chevron-left text-[9px]"></i>
            </button>
            <template x-for="pg in {{ $pages }}" :key="'{{ $keyPrefix }}' + pg">
                <button x-on:click="{{ $pageExpr }}" :class="{{ $activeExpr }} ? 'bg-dict-blue text-white border-dict-blue shadow-sm' : 'text-slate-600 hover:bg-slate-100 border-slate-200'" class="w-7 h-7 flex items-center justify-center rounded-lg text-[11px] font-bold transition border" x-text="{{ $textExpr }}"></button>
            </template>
            <button x-on:click="{{ $nextClick }}" :disabled="{{ $nextDisabled }}" title="Next" class="w-7 h-7 flex items-center justify-center rounded-lg text-[11px] font-bold transition border border-slate-200 text-slate-600 hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed">
                <i class="fa-solid fa-chevron-right text-[9px]"></i>
            </button>
        </div>
        {{ $slot }}
    </div>
</div>
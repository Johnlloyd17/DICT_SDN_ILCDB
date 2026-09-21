@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}">

        <div class="flex gap-2 items-center justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center px-3 py-1.5 text-xs font-bold text-slate-500 bg-white border border-slate-200 cursor-not-allowed rounded-lg">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-3 py-1.5 text-xs font-bold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-100 transition">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-3 py-1.5 text-xs font-bold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-100 transition">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="inline-flex items-center px-3 py-1.5 text-xs font-bold text-slate-500 bg-white border border-slate-200 cursor-not-allowed rounded-lg">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        <div class="hidden sm:flex sm:items-center sm:justify-end">
            <div>
                <span class="inline-flex rtl:flex-row-reverse flex items-center gap-1">

                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="w-7 h-7 inline-flex items-center justify-center text-[11px] font-bold text-slate-400 bg-white border border-slate-200 cursor-not-allowed rounded-lg" aria-hidden="true">
                                <i class="fa-solid fa-chevron-left text-[9px]"></i>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="w-7 h-7 inline-flex items-center justify-center text-[11px] font-bold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-100 transition" aria-label="{{ __('pagination.previous') }}">
                            <i class="fa-solid fa-chevron-left text-[9px]"></i>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="w-7 h-7 inline-flex items-center justify-center text-[11px] font-bold text-slate-400 bg-white border border-slate-200 cursor-default rounded-lg">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="w-7 h-7 inline-flex items-center justify-center text-[11px] font-bold text-white bg-dict-blue border border-dict-blue shadow-sm rounded-lg">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="w-7 h-7 inline-flex items-center justify-center text-[11px] font-bold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-100 transition" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="w-7 h-7 inline-flex items-center justify-center text-[11px] font-bold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-100 transition" aria-label="{{ __('pagination.next') }}">
                            <i class="fa-solid fa-chevron-right text-[9px]"></i>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="w-7 h-7 inline-flex items-center justify-center text-[11px] font-bold text-slate-400 bg-white border border-slate-200 cursor-not-allowed rounded-lg" aria-hidden="true">
                                <i class="fa-solid fa-chevron-right text-[9px]"></i>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
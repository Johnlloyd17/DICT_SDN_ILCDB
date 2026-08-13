@props(['paginator', 'withPerPage' => true, 'compact' => false])
<div class="{{ $compact ? '' : 'mt-4' }} flex flex-col lg:flex-row items-center justify-between gap-3">
    @if($withPerPage)
    <div class="flex items-center gap-2 text-[11px] text-slate-500 font-medium whitespace-nowrap">
        <span>Rows per page:</span>
        <form method="GET" class="flex items-center gap-2">
            @foreach(request()->except(['page', 'per_page']) as $key => $value)
                @if(is_array($value))
                    @foreach($value as $v)
                        <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                    @endforeach
                @else
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach
            <select name="per_page" onchange="this.form.submit()" class="text-xs p-1.5 border border-slate-300 rounded-lg outline-none bg-white font-medium text-slate-700 focus:ring-2 focus:ring-blue-500">
                @foreach([5, 10, 20, 30, 40, 50, 100, 150, 200] as $n)
                    <option value="{{ $n }}" @selected((int) request('per_page', 5) === $n)>{{ $n }}</option>
                @endforeach
            </select>
        </form>
    </div>
    @endif
    <div class="text-[11px] text-slate-500 font-medium">
        Showing {{ $paginator->firstItem() ?? 0 }}–{{ $paginator->lastItem() ?? 0 }} of {{ $paginator->total() }}
    </div>
    <div class="flex items-center gap-1">
        {{ $paginator->links() }}
    </div>
</div>

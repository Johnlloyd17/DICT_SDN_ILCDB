@props(['items' => []])

<nav class="text-xs text-slate-500 mb-4 flex items-center flex-wrap gap-1.5">
    <a href="{{ route('dashboard') }}" class="flex items-center gap-1.5 hover:text-blue-600 transition font-medium">
        <i class="fa-solid fa-house text-blue-500"></i> Main Overview
    </a>
    @foreach($items as $item)
        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
        @if(!empty($item['url']))
            <a href="{{ $item['url'] }}" class="flex items-center gap-1.5 hover:text-blue-600 transition font-medium">
                @if(!empty($item['icon']))
                    <i class="fa-solid {{ $item['icon'] }}"></i>
                @endif
                {{ $item['label'] }}
            </a>
        @else
            <span class="font-semibold text-slate-700 flex items-center gap-1.5">
                @if(!empty($item['icon']))
                    <i class="fa-solid {{ $item['icon'] }}"></i>
                @endif
                {{ $item['label'] }}
            </span>
        @endif
    @endforeach
</nav>

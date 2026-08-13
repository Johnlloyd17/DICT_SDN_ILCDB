@props(['label', 'value', 'icon' => 'fa-solid fa-chart-simple', 'color' => 'blue', 'subtitle' => null])

<div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200 flex items-center space-x-4">
    <div class="p-3 bg-{{ $color }}-100 text-{{ $color }}-600 rounded-lg">
        <i class="{{ $icon }} text-xl"></i>
    </div>
    <div>
        <p class="text-xs font-medium text-slate-500 uppercase">{{ $label }}</p>
        <h3 class="text-2xl font-bold text-slate-800">{{ $value }}</h3>
        @if($subtitle)
        <p class="text-[10px] text-slate-400">{{ $subtitle }}</p>
        @endif
    </div>
</div>

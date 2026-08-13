@props(['title', 'icon' => 'fa-solid fa-table-columns'])

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
        <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
            <i class="{{ $icon }} text-blue-600"></i> {{ $title }}
        </h3>
        <div class="flex items-center gap-2">
            {{ $actions ?? '' }}
        </div>
    </div>
    <div class="p-5">
        {{ $slot }}
    </div>
</div>

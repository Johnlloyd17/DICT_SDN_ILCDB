@props(['title', 'subtitle' => null, 'icon' => null, 'color' => 'blue'])

<div class="bg-gradient-to-r from-{{ $color }}-900 to-{{ $color }}-700 text-white rounded-xl p-5 shadow-sm mb-6">
    <h2 class="text-xl font-bold flex items-center gap-2">
        @if($icon)
        <i class="fa-solid {{ $icon }} text-amber-400"></i>
        @endif
        {{ $title }}
    </h2>
    @if($subtitle)
    <p class="text-sm text-blue-200 mt-1">{{ $subtitle }}</p>
    @endif
</div>

@props(['label', 'href' => '#', 'icon' => null])

<a href="{{ $href }}" class="inline-flex items-center gap-2 bg-blue-800 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-xs font-semibold transition">
    @if($icon)
    <i class="{{ $icon }}"></i>
    @endif
    {{ $label }}
</a>

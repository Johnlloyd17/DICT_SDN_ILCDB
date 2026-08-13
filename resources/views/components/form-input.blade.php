@props(['label', 'name', 'type' => 'text', 'value' => null, 'required' => false, 'placeholder' => null])

<div class="space-y-1">
    <label for="{{ $name }}" class="block text-xs font-semibold text-slate-700">
        {{ $label }} @if($required) <span class="text-red-500">*</span> @endif
    </label>
    <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}" value="{{ old($name, $value) }}"
        {{ $required ? 'required' : '' }}
        placeholder="{{ $placeholder }}"
        class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
    @error($name)
    <p class="text-[10px] text-red-500">{{ $message }}</p>
    @enderror
</div>

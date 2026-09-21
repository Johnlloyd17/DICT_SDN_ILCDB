@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'w-full px-4 py-3 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-sm font-medium']) }}>
        {{ $status }}
    </div>
@endif
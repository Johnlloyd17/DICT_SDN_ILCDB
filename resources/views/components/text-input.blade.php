@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border border-slate-300 focus:border-dict-blue focus:ring-dict-blue rounded-lg shadow-sm']) }}>
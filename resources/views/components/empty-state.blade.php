@props(['icon' => 'fa-solid fa-hourglass-half', 'title' => 'Coming Soon', 'message' => 'This module is under development.'])

<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
    <i class="{{ $icon }} text-4xl text-slate-300 mb-4"></i>
    <h3 class="text-lg font-bold text-slate-700">{{ $title }}</h3>
    <p class="text-sm text-slate-400 mt-2">{{ $message }}</p>
</div>

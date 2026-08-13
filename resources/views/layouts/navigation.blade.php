@php
    $currentRoute = request()->route()->getName();
@endphp

<nav class="border-t bg-slate-900 text-slate-300 border-slate-800" x-data="{ mobileOpen: false }">
    <div class="px-4 content-wrap sm:px-6 lg:px-8">
        {{-- Desktop Tabs --}}
        <div class="hidden space-x-1 overflow-x-auto md:flex custom-scrollbar">
            <a href="{{ route('dashboard') }}"
               class="py-3 px-4 font-semibold text-sm border-b-2 {{ $currentRoute === 'dashboard' ? 'border-blue-400 text-white' : 'border-transparent hover:text-white' }} flex items-center space-x-2 whitespace-nowrap transition">
                <i class="text-blue-400 fa-solid fa-chart-line"></i>
                <span>Main Overview</span>
            </a>
            <a href="{{ route('tmd.participants.index') }}"
               class="py-3 px-4 font-semibold text-sm border-b-2 {{ str_starts_with($currentRoute ?? '', 'tmd') ? 'border-amber-400 text-white' : 'border-transparent hover:text-white' }} flex items-center space-x-2 whitespace-nowrap transition">
                <i class="fa-solid fa-graduation-cap text-amber-400"></i>
                <span>DWIA - TMD</span>
            </a>
            <a href="{{ route('dtc.visitors.index') }}"
               class="py-3 px-4 font-semibold text-sm border-b-2 {{ str_starts_with($currentRoute ?? '', 'dtc') ? 'border-cyan-400 text-white' : 'border-transparent hover:text-white' }} flex items-center space-x-2 whitespace-nowrap transition">
                <i class="fa-solid fa-building-user text-cyan-400"></i>
                <span>DTC HUB</span>
            </a>
            <a href="{{ route('spark.trainings.index') }}"
               class="py-3 px-4 font-semibold text-sm border-b-2 {{ str_starts_with($currentRoute ?? '', 'spark') ? 'border-yellow-400 text-white' : 'border-transparent hover:text-white' }} flex items-center space-x-2 whitespace-nowrap transition">
                <i class="text-yellow-400 fa-solid fa-bolt"></i>
                <span>SPARK</span>
            </a>
            <a href="{{ route('click.devices.index') }}"
               class="py-3 px-4 font-semibold text-sm border-b-2 {{ str_starts_with($currentRoute ?? '', 'click') ? 'border-emerald-400 text-white' : 'border-transparent hover:text-white' }} flex items-center space-x-2 whitespace-nowrap transition">
                <i class="fa-solid fa-laptop-code text-emerald-400"></i>
                <span>PROJECT CLICK</span>
            </a>
            <a href="{{ route('funding.index') }}"
               class="py-3 px-4 font-semibold text-sm border-b-2 {{ str_starts_with($currentRoute ?? '', 'funding') ? 'border-purple-400 text-white' : 'border-transparent hover:text-white' }} flex items-center space-x-2 whitespace-nowrap transition">
                <i class="text-purple-400 fa-solid fa-sack-dollar"></i>
                <span>Funding Monitoring</span>
            </a>
        </div>

        {{-- Mobile Hamburger --}}
        <div class="flex items-center justify-between h-12 md:hidden">
            <span class="text-xs font-bold uppercase text-slate-400">Navigation</span>
            <button @click="mobileOpen = !mobileOpen" class="p-2 text-white">
                <i class="fa-solid" :class="mobileOpen ? 'fa-xmark' : 'fa-bars'"></i>
            </button>
        </div>

        {{-- Mobile Drawer --}}
        <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
             @click.away="mobileOpen = false"
             class="pb-3 space-y-1 md:hidden">
            <a href="{{ route('dashboard') }}" class="block py-2 px-3 rounded-lg text-sm {{ $currentRoute === 'dashboard' ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white' }}">
                <i class="mr-2 text-blue-400 fa-solid fa-chart-line"></i> Main Overview
            </a>
            <a href="{{ route('tmd.participants.index') }}" class="block py-2 px-3 rounded-lg text-sm {{ str_starts_with($currentRoute ?? '', 'tmd') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white' }}">
                <i class="mr-2 fa-solid fa-graduation-cap text-amber-400"></i> DWIA - TMD
            </a>
            <a href="{{ route('dtc.visitors.index') }}" class="block py-2 px-3 rounded-lg text-sm {{ str_starts_with($currentRoute ?? '', 'dtc') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white' }}">
                <i class="mr-2 fa-solid fa-building-user text-cyan-400"></i> DTC HUB
            </a>
            <a href="{{ route('spark.trainings.index') }}" class="block py-2 px-3 rounded-lg text-sm {{ str_starts_with($currentRoute ?? '', 'spark') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white' }}">
                <i class="mr-2 text-yellow-400 fa-solid fa-bolt"></i> SPARK
            </a>
            <a href="{{ route('click.devices.index') }}" class="block py-2 px-3 rounded-lg text-sm {{ str_starts_with($currentRoute ?? '', 'click') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white' }}">
                <i class="mr-2 fa-solid fa-laptop-code text-emerald-400"></i> PROJECT CLICK
            </a>
            <a href="{{ route('funding.index') }}" class="block py-2 px-3 rounded-lg text-sm {{ str_starts_with($currentRoute ?? '', 'funding') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white' }}">
                <i class="mr-2 text-purple-400 fa-solid fa-sack-dollar"></i> Funding Monitoring
            </a>
        </div>
    </div>
</nav>

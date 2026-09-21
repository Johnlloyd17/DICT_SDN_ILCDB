<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'DICT SDN ILCDB') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800|playfair-display:600,700|cinzel:600,700,800&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="flex flex-col min-h-screen font-sans antialiased bg-slate-50 text-slate-800">

    {{-- HEADER --}}
    <header class="bg-dict-blue text-white shadow-md sticky top-0 z-[1000]">
        <div class="px-4 content-wrap sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center bg-white/10 backdrop-blur-sm rounded-xl border border-blue-400/30 px-3 py-1.5 shadow-sm">
                        <span class="text-base leading-none">🇵🇭</span>
                        <span class="ml-2 text-sm font-black tracking-widest text-white">ILCDB</span>
                    </div>
                    <div class="hidden w-px h-8 sm:block bg-gradient-to-b from-blue-400/60 to-transparent"></div>
                    <div class="hidden sm:block">
                        <h1 class="text-[clamp(0.9rem,1rem+0.15vw,1.125rem)] font-bold leading-tight tracking-wide">DICT Provincial Portal</h1>
                        <p class="text-[clamp(0.625rem,0.6rem+0.1vw,0.75rem)] text-blue-200/80 font-medium tracking-wider uppercase">ICT Literacy & Competency Development Bureau</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="hidden sm:flex items-center bg-blue-900/60 rounded-lg px-3 py-1.5 text-xs border border-blue-700">
                        <i class="mr-2 text-yellow-400 fa-solid fa-location-dot"></i>
                        <span>Provincial Field Office: <strong>Surigao del Norte</strong></span>
                    </div>
                    @auth
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center space-x-2 text-xs focus:outline-none bg-blue-900/40 p-1.5 rounded-full border border-blue-600">
                            <span class="flex items-center justify-center font-bold rounded-full shadow w-7 h-7 bg-amber-400 text-dict-blue">
                                {{ substr(Auth::user()->name, 0, 2) }}
                            </span>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition style="display: none;" class="absolute right-0 z-50 w-48 py-1 mt-2 bg-white border shadow-xl rounded-xl border-slate-200">
                            <div class="px-4 py-2 border-b border-slate-100">
                                <p class="text-xs font-bold text-slate-800">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] text-slate-400">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-xs text-slate-700 hover:bg-slate-50">Profile</a>
                            @if (Auth::user()->is_admin)
                                <a href="{{ route('settings.database.index') }}" class="block px-4 py-2 text-xs text-slate-700 hover:bg-slate-50">Settings</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 text-xs text-left text-red-600 hover:bg-red-50">Logout</button>
                            </form>
                        </div>
                    </div>
                    @endauth
                </div>
            </div>
        </div>

        {{-- NAVIGATION TABS --}}
        @include('layouts.navigation')
    </header>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 w-full px-4 py-6 content-wrap sm:px-6 lg:px-8">
        {{ $slot }}
    </main>

    {{-- TOAST NOTIFICATION --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3500); console.log(@js(session('success')))" x-transition
         class="fixed z-50 flex items-center px-4 py-3 space-x-3 text-xs text-white border shadow-2xl bottom-5 right-5 bg-slate-900 rounded-xl border-emerald-700">
        <i class="text-lg fa-solid fa-circle-check text-emerald-400"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3500); console.error(@js(session('error')))" x-transition
         class="fixed z-50 flex items-center px-4 py-3 space-x-3 text-xs text-white border border-red-700 shadow-2xl bottom-5 right-5 bg-slate-900 rounded-xl">
        <i class="text-lg text-red-400 fa-solid fa-circle-exclamation"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- BULK DELETE CONFIRMATION MODAL (shared) --}}
    <div x-data="bulkDeleteModal()" x-on:keydown.escape.window="cancel()" x-show="open" style="display: none;"
         x-transition.opacity class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
             class="bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl max-w-md w-full overflow-hidden border border-slate-200">
            <div class="bg-red-600 text-white px-6 py-4 flex items-center justify-between">
                <h3 class="font-bold flex items-center gap-2">
                    <i class="fa-solid fa-trash-can text-red-200"></i>
                    <span x-text="title"></span>
                </h3>
                <button x-on:click="cancel()" class="text-white/60 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <div class="p-6 text-xs max-h-[50vh] overflow-y-auto">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-red-50 text-red-600 flex items-center justify-center shrink-0"><i class="fa-solid fa-triangle-exclamation text-lg"></i></div>
                    <div>
                        <p class="font-bold text-slate-800" x-text="description ? description : ('Delete ' + count + ' selected record(s)?')"></p>
                        <p class="text-slate-500 mt-0.5">This action cannot be undone.</p>
                    </div>
                </div>
                <div x-show="warning" x-cloak class="bg-amber-50 border border-amber-200 text-amber-800 rounded-lg px-3 py-2 mb-3 font-semibold" x-text="warning"></div>
                <template x-if="previewLines.length">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Records to be deleted</p>
                        <ul class="space-y-1">
                            <template x-for="(line, i) in previewLines" :key="i">
                                <li class="flex items-center gap-2 text-slate-700">
                                    <i class="fa-solid fa-file-circle-xmark text-red-400"></i>
                                    <span x-text="line"></span>
                                </li>
                            </template>
                        </ul>
                    </div>
                </template>
                <template x-if="!previewLines.length && labels.length">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Records to be deleted</p>
                        <ul class="space-y-1">
                            <template x-for="(lab, i) in labels.slice(0, 6)" :key="i">
                                <li class="flex items-center gap-2 text-slate-700">
                                    <i class="fa-solid fa-file-circle-xmark text-red-400"></i>
                                    <span class="truncate" x-text="lab"></span>
                                </li>
                            </template>
                        </ul>
                        <p x-show="labels.length > 6" class="mt-2 text-slate-400 font-medium" x-text="'+ ' + (labels.length - 6) + ' more…'"></p>
                    </div>
                </template>
                <div class="flex justify-end gap-3 pt-5">
                    <button type="button" x-on:click="cancel()" class="bg-slate-200 text-slate-700 font-semibold rounded-lg hover:bg-slate-300 px-4 py-2 text-xs">Cancel</button>
                    <button type="button" x-on:click="proceed()" :disabled="busy" class="bg-red-600 hover:bg-red-500 text-white font-bold rounded-lg shadow px-4 py-2 text-xs disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fa-solid fa-trash mr-1" :class="busy && 'fa-spinner fa-spin'"></i> Delete <span x-text="count"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.bulkDeleteModal = function () {
            return {
                open: false,
                busy: false,
                title: 'Delete Records',
                count: 0,
                labels: [],
                description: '',
                previewLines: [],
                warning: '',
                confirmAction: null,
                init() {
                    window.addEventListener('confirm-bulk-delete', (e) => {
                        this.title = e.detail.title || 'Delete Records';
                        this.count = e.detail.count || 0;
                        this.labels = (e.detail.labels || []).slice(0, 20);
                        this.description = e.detail.description || '';
                        this.previewLines = (e.detail.previewLines || []).slice(0, 20);
                        this.warning = e.detail.warning || '';
                        this.confirmAction = e.detail.onConfirm || null;
                        this.open = true;
                    });
                },
                cancel() {
                    this.open = false;
                    this.confirmAction = null;
                    this.busy = false;
                    this.description = '';
                    this.previewLines = [];
                },
                async proceed() {
                    if (!this.confirmAction || this.busy) return;
                    this.busy = true;
                    try {
                        await this.confirmAction();
                        this.open = false;
                    } catch (e) {
                        // errors are already flashed by the caller
                    } finally {
                        this.confirmAction = null;
                        this.busy = false;
                    }
                },
            };
        };
    </script>

    @stack('scripts')
</body>
</html>

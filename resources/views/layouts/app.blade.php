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

    @stack('scripts')
</body>
</html>

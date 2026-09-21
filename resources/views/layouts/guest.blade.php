<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'DICT SDN ILCDB') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen font-sans antialiased text-slate-800">
        {{-- FULL-BLEED BACKGROUND PHOTO, FROSTED BLUR + PALE WHITE WASH (SDN-Monitoring style) --}}
        <div class="fixed inset-0 -z-10 overflow-hidden bg-cover bg-center blur-[18px] scale-[1.10]"
             style="background-image: url('{{ asset('../Assets/img/background.jpeg') }}')"></div>
        <div class="fixed inset-0 -z-10 bg-white/55"></div>

        <div class="flex flex-col items-center min-h-screen px-4 py-6 sm:py-8">
            {{-- CENTERED CARD + FOOTER AS ONE UNIT --}}
            <main class="flex flex-col items-center flex-1 justify-center w-full max-w-xs py-4">
                <div class="w-full p-6 rounded-xl border shadow-xl shadow-dict-blue/20 bg-white border-slate-200 border-t-4 border-t-dict-gold sm:p-6">
                    <img src="{{ asset('../Assets/img/logo.png') }}" alt="DICT Logo"
                         class="w-auto h-24 mx-auto mb-1.5">

                    <h1 class="text-lg font-bold text-center text-slate-900">
                        DICT Provincial Portal
                    </h1>
                    <div class="w-10 h-0.5 mx-auto mt-2 mb-4 rounded-full bg-dict-gold"></div>

                    {{ $slot }}
                </div>

                {{-- FOOTER --}}
                <footer class="mt-4 text-center text-xs font-medium text-slate-700">
                    DICT Provincial Field Office — Surigao del Norte
                </footer>
            </main>
        </div>
    </body>
</html>
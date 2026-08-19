<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PTSP Online') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gradient-to-br from-slate-100 via-emerald-50 to-white px-4 py-8 sm:px-6 lg:px-8">
            <div class="mx-auto flex w-full max-w-md flex-col items-center">
                <a href="/" class="mb-6 inline-flex items-center justify-center rounded-2xl border border-emerald-100 bg-white p-4 shadow-sm">
                    <img src="{{ asset('build/assets/gandem.png') }}" alt="Logo" class="h-16 w-16 rounded-xl object-cover">
                </a>

                <div class="w-full overflow-hidden rounded-3xl border border-slate-200 bg-white/90 p-5 shadow-xl shadow-emerald-100/60 backdrop-blur-sm sm:p-7">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>

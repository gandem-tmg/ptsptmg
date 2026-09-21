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
        <div class="relative flex min-h-screen items-center justify-center overflow-hidden bg-gradient-to-br from-slate-50 via-emerald-50/50 to-white px-4 py-6">
            <!-- Aksen dekoratif halus — biar tidak terasa kosong/polos tanpa menambah tinggi konten -->
            <div class="pointer-events-none absolute -left-24 -top-24 h-72 w-72 rounded-full bg-emerald-200/30 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-teal-200/30 blur-3xl"></div>

            <div class="relative w-full max-w-[400px]">
                <a href="/" class="mb-4 inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 transition hover:text-emerald-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                    Kembali ke Beranda
                </a>

                <div class="overflow-hidden rounded-3xl border border-slate-200/70 bg-white/95 shadow-xl shadow-emerald-900/5 backdrop-blur-sm">
                    <div class="px-6 py-7 sm:px-8">
                        <a href="/" class="mx-auto mb-5 flex h-12 w-12 items-center justify-center rounded-2xl border border-emerald-100 bg-emerald-50/70">
                            <img src="{{ asset('build/assets/gandem.png') }}" alt="Logo" class="h-8 w-8 rounded-lg object-cover">
                        </a>

                        {{ $slot }}
                    </div>
                </div>

                <p class="mt-4 text-center text-[11px] text-slate-400">
                    &copy; {{ date('Y') }} PTSP Online &middot; Kantor Kementerian Agama Kab. Temanggung
                </p>
            </div>
        </div>
    </body>
</html>

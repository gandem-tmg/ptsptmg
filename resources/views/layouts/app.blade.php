<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PTSP Online') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('build/assets/favicon.ico') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        {{-- flex flex-col + min-h-[100dvh]: wrapper ini jadi "sticky footer"
             layout. bottom-nav (item terakhir) otomatis nempel di bawah
             viewport lewat position:sticky, bukan position:fixed — pola ini
             tidak kena bug klasik mobile Safari/Chrome di mana elemen fixed
             baru muncul penuh SETELAH user scroll sekali (dynamic toolbar
             bikin fixed-positioning telat recalculate). Konten (div dengan
             flex-1) meregang mengisi sisa tinggi supaya nav tetap di bawah
             viewport walau isi halaman pendek. --}}
        <div x-data="{ sidebarOpen: false, sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' }"
             x-init="$watch('sidebarCollapsed', value => localStorage.setItem('sidebarCollapsed', value))"
             class="flex min-h-screen min-h-[100dvh] flex-col">
            @include('layouts.navigation')

            <div class="flex flex-1 flex-col transition-all duration-200" :class="sidebarCollapsed ? 'lg:pl-20' : 'lg:pl-72'">
                <!-- Topbar -->
                <header class="sticky top-0 z-30 flex h-14 shrink-0 items-center gap-3 border-b border-emerald-100 bg-white/90 px-4 backdrop-blur-md sm:px-6 lg:px-8">
                    <button @click="sidebarOpen = true" class="-ml-2 shrink-0 rounded-lg p-2 text-slate-600 hover:bg-slate-100 lg:hidden">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>

                    <!-- Logo + nama aplikasi: cuma tampil di mobile, di desktop sudah ada di sidebar -->
                    <a href="{{ route('dashboard') }}" class="flex min-w-0 shrink-0 items-center gap-2 lg:hidden">
                        <img src="{{ asset('logo.png') }}" alt="Logo PTSP" class="h-8 w-8 shrink-0 rounded-lg object-cover">
                        <span class="min-w-0 leading-tight">
                            <span class="block max-w-[120px] truncate text-sm font-bold text-slate-900 sm:max-w-none">PTSP Online</span>
                        </span>
                    </a>
                    <span class="h-6 w-px shrink-0 bg-slate-200 lg:hidden"></span>

                    <div class="min-w-0 flex-1">
                        @isset($header)
                            {{ $header }}
                        @endisset
                    </div>

                    {{-- Notifikasi "belum isi survei" sudah dicabut: sejak
                         pengisian SKM dialihkan ke link eksternal, aplikasi
                         ini tidak pernah tahu lagi kalau pemohon sudah
                         mengisi survei-nya, jadi badge-nya bakal numpuk
                         terus & gak akurat kalau tetap ditampilkan. --}}
                </header>

                <main class="internal-workspace flex-1 px-4 py-6 pb-10 sm:px-6 lg:px-8 lg:pb-8">
                    <div class="mx-auto max-w-7xl">
                        {{ $slot }}
                    </div>
                </main>
            </div>

            @include('layouts.bottom-nav')
        </div>

        @stack('scripts')
    </body>
</html>

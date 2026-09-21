<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PTSP Online') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        {{-- flex flex-col: sama seperti layouts/app.blade.php — bottom nav
             (sticky, item terakhir) jadi nempel natural di bawah viewport
             lewat flow dokumen, bukan position:fixed yang kena bug mobile
             Safari/Chrome (baru muncul penuh setelah discroll sekali). --}}
        <div x-data="{ menuOpen: false }" class="flex min-h-screen min-h-[100dvh] flex-col bg-gradient-to-br from-slate-50 via-emerald-50 to-white text-slate-800">

            <!-- Top bar -->
            <nav class="sticky top-0 z-40 shrink-0 border-b border-slate-200 bg-white/85 backdrop-blur-md">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 items-center justify-between gap-4">
                        <!-- Kiri: logo + identitas portal -->
                        <a href="{{ route('layanan.index') }}" class="flex min-w-0 items-center gap-2.5 sm:gap-3">
                            <img src="{{ asset('logo.png') }}" alt="Logo" class="h-9 w-9 shrink-0 rounded-lg object-cover sm:h-10 sm:w-10">
                            <span class="min-w-0 leading-tight">
                                <span class="block truncate text-sm font-bold text-slate-900">PTSP Online</span>
                                <span class="block max-w-[170px] truncate text-[11px] text-slate-500 sm:hidden">Kementerian Agama Kab. Temanggung</span>
                                <span class="hidden truncate text-xs text-slate-500 sm:block">Kantor Kementerian Agama Kab. Temanggung</span>
                            </span>
                        </a>

                        <!-- Tengah: menu utama -->
                        <div class="hidden items-center gap-1 sm:flex">
                            <a href="{{ route('layanan.index') }}" class="nav-link">Beranda</a>
                            <a href="{{ route('guest.searchTicket') }}" class="nav-link">Status Permohonan</a>
                            <a href="{{ route('transparansi.index') }}" class="nav-link">Transparansi Layanan Publik</a>
                            <a href="{{ config('skm.external_url') }}" target="_blank" rel="noopener" class="nav-link">Survei Kepuasan</a>
                            <a href="https://wa.me/8112744431?text=Halo%20admin,%20ada%20yang%20ingin%20saya%20tanyakan" target="_blank" class="nav-link">Kontak Kami</a>
                        </div>

                        <!-- Kanan: aksi akun -->
                        <div class="hidden items-center gap-3 sm:flex">
                            @auth
                                <a href="{{ route('dashboard') }}" class="primary-btn">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="secondary-btn">Masuk</a>
                                <a href="{{ route('register') }}" class="primary-btn">Daftar</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Konten -->
            <main class="flex-1 pb-6 sm:pb-0">
                {{ $slot }}
            </main>

            <!-- Bottom navigation (mobile only) -->
            <nav class="sticky inset-x-0 bottom-0 z-40 shrink-0 border-t border-slate-200 bg-white/95 backdrop-blur-md sm:hidden" style="padding-bottom: env(safe-area-inset-bottom);">
                <div class="grid grid-cols-4">
                    <a href="{{ route('layanan.index') }}" class="flex flex-col items-center gap-1 py-2.5 text-[11px] font-medium {{ request()->routeIs('layanan.*') ? 'text-emerald-600' : 'text-slate-500' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                        Beranda
                    </a>
                    <a href="{{ route('guest.searchTicket') }}" class="flex flex-col items-center gap-1 py-2.5 text-[11px] font-medium {{ request()->routeIs('guest.searchTicket') || request()->routeIs('guest.showTicket') ? 'text-emerald-600' : 'text-slate-500' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Status
                    </a>
                    <a href="https://wa.me/8112744431?text=Halo%20admin,%20ada%20yang%20ingin%20saya%20tanyakan" target="_blank" class="flex flex-col items-center gap-1 py-2.5 text-[11px] font-medium text-slate-500">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                        Kontak
                    </a>
                    <button type="button" @click="menuOpen = true" class="flex flex-col items-center gap-1 py-2.5 text-[11px] font-medium text-slate-500">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        Menu
                    </button>
                </div>
            </nav>

            <!-- Drawer menu (mobile only) — berisi semua menu beranda publik -->
            <div x-show="menuOpen" x-cloak class="relative z-50 sm:hidden" role="dialog" aria-modal="true">
                <div x-show="menuOpen" x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60" @click="menuOpen = false"></div>

                <div class="fixed inset-x-0 bottom-0 flex max-h-[85vh] flex-col">
                    <div x-show="menuOpen" x-transition:enter="transition ease-in-out duration-200 transform" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" x-transition:leave="transition ease-in-out duration-150 transform" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full" class="flex max-h-[85vh] flex-col overflow-hidden rounded-t-3xl bg-white shadow-2xl" style="padding-bottom: env(safe-area-inset-bottom);">
                        <div class="flex shrink-0 items-center justify-between border-b border-slate-100 px-5 py-4">
                            <a href="{{ route('layanan.index') }}" class="flex items-center gap-2.5" @click="menuOpen = false">
                                <img src="{{ asset('logo.png') }}" alt="Logo" class="h-8 w-8 rounded-lg object-cover">
                                <span class="font-semibold text-slate-900">PTSP Online</span>
                            </a>
                            <button @click="menuOpen = false" class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>

                        <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
                            <a href="{{ route('layanan.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium {{ request()->routeIs('layanan.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-700 hover:bg-slate-50' }}">
                                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                                Beranda
                            </a>
                            <a href="{{ route('guest.searchTicket') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium {{ request()->routeIs('guest.searchTicket') || request()->routeIs('guest.showTicket') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-700 hover:bg-slate-50' }}">
                                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                Status Permohonan
                            </a>
                            <a href="{{ route('transparansi.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium {{ request()->routeIs('transparansi.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-700 hover:bg-slate-50' }}">
                                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                                Transparansi Layanan Publik
                            </a>
                            <a href="{{ config('skm.external_url') }}" target="_blank" rel="noopener" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">
                                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" /></svg>
                                Survei Kepuasan
                            </a>
                            <a href="https://wa.me/8112744431?text=Halo%20admin,%20ada%20yang%20ingin%20saya%20tanyakan" target="_blank" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">
                                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                                Kontak Kami
                            </a>
                        </nav>

                        <div class="shrink-0 border-t border-slate-100 p-4">
                            @auth
                                <a href="{{ route('dashboard') }}" class="primary-btn w-full">Dashboard Saya</a>
                                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                                    @csrf
                                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold text-rose-600 hover:bg-rose-50">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                        Keluar
                                    </button>
                                </form>
                            @else
                                <div class="flex gap-3">
                                    <a href="{{ route('login') }}" class="secondary-btn w-full">Masuk</a>
                                    <a href="{{ route('register') }}" class="primary-btn w-full">Daftar</a>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

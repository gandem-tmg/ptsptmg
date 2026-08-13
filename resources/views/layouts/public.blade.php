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
    <body class="font-sans antialiased" x-data="{ open: false }">
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-emerald-50 to-white text-slate-800">
            <nav class="sticky top-0 z-50 border-b border-slate-200 bg-white/85 backdrop-blur-md">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 items-center justify-between">
                        <div class="flex items-center gap-4">
                            <a href="/" class="flex items-center gap-3">
                                <img src="{{ asset('logo.png') }}" alt="Logo" class="h-10 w-auto rounded-lg">
                            </a>

                            <div class="hidden items-center gap-1 sm:flex">
                                <a href="{{ route('guest.permohonan.biodata') }}" class="nav-link">
                                    Permohonan Baru
                                </a>
                                <a href="{{ route('guest.searchTicket') }}" class="nav-link">
                                    Cari Tiket
                                </a>
                                <a href="https://wa.me/8112744431?text=Halo%20admin,%20ada%20yang%20ingin%20saya%20tanyakan" target="_blank" class="nav-link">
                                    WA Layanan
                                </a>
                            </div>
                        </div>

                        <div class="hidden items-center gap-3 sm:flex">
                            <a href="{{ route('login') }}" class="secondary-btn">
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="primary-btn">
                                Register
                            </a>
                        </div>

                        <div class="-mr-2 flex items-center sm:hidden">
                            <button @click="open = ! open" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white p-2.5 text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t border-slate-200 bg-white sm:hidden">
                    <div class="space-y-1 px-4 py-3">
                        <a href="{{ route('guest.permohonan.biodata') }}" class="block rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                            Permohonan Baru
                        </a>
                        <a href="{{ route('guest.searchTicket') }}" class="block rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                            Cari Tiket
                        </a>
                        <a href="https://wa.me/8112744431?text=Halo%20admin,%20ada%20yang%20ingin%20saya%20tanyakan" target="_blank" class="block rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                            WA Layanan
                        </a>
                        <div class="mt-3 grid grid-cols-2 gap-2 pt-2">
                            <a href="{{ route('login') }}" class="secondary-btn w-full">Login</a>
                            <a href="{{ route('register') }}" class="primary-btn w-full">Register</a>
                        </div>
                    </div>
                </div>
            </nav>

            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>

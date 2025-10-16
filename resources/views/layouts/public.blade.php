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
    <body class="font-sans text-gray-100 antialiased" style="background-color: #408150;" x-data="{ open: false }">
        <!-- Navigation -->
        <nav class="bg-primary border-b border-primary-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="/">
                                <img src="{{ asset('logo.png') }}" alt="Logo" class="block h-9 w-auto">
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <a href="{{ route('guest.permohonan.biodata') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-white hover:text-primary-100 hover:border-primary-300 focus:outline-none focus:text-primary-100 focus:border-primary-300 transition duration-150 ease-in-out">
                                Permohonan Baru
                            </a>
                            <a href="{{ route('guest.searchTicket') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-white hover:text-primary-100 hover:border-primary-300 focus:outline-none focus:text-primary-100 focus:border-primary-300 transition duration-150 ease-in-out">
                                Cari Tiket
                            </a>
                            <a href="https://wa.me/8112744431?text=Halo%20admin,%20ada%20yang%20ingin%20saya%20tanyakan" target="_blank" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-white hover:text-primary-100 hover:border-primary-300 focus:outline-none focus:text-primary-100 focus:border-primary-300 transition duration-150 ease-in-out">
                                WA Layanan
                            </a>
                        </div>
                    </div>

                    <!-- Right Side -->
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <a href="{{ route('login') }}" class="text-white hover:text-primary-100 px-3 py-2 rounded-md text-sm font-medium">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="bg-white text-primary hover:bg-primary-100 px-3 py-2 rounded-md text-sm font-medium ml-3">
                            Register
                        </a>
                    </div>

                    <!-- Hamburger -->
                    <div class="-me-2 flex items-center sm:hidden">
                        <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-primary-100 hover:bg-primary-700 focus:outline-none focus:bg-primary-700 focus:text-primary-100 transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('guest.permohonan.biodata') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-white hover:text-primary-100 hover:bg-primary-700 hover:border-primary-300 focus:outline-none focus:text-primary-100 focus:bg-primary-700 focus:border-primary-300 transition duration-150 ease-in-out">
                        Permohonan Baru
                    </a>
                    <a href="{{ route('guest.searchTicket') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-white hover:text-primary-100 hover:bg-primary-700 hover:border-primary-300 focus:outline-none focus:text-primary-100 focus:bg-primary-700 focus:border-primary-300 transition duration-150 ease-in-out">
                        Cari Tiket
                    </a>
                    <a href="https://wa.me/8112744431?text=Halo%20admin,%20ada%20yang%20ingin%20saya%20tanyakan" target="_blank" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-white hover:text-primary-100 hover:bg-primary-700 hover:border-primary-300 focus:outline-none focus:text-primary-100 focus:bg-primary-700 focus:border-primary-300 transition duration-150 ease-in-out">
                        WA Layanan
                    </a>
                    <a href="{{ route('login') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-white hover:text-primary-100 hover:bg-primary-700 hover:border-primary-300 focus:outline-none focus:text-primary-100 focus:bg-primary-700 focus:border-primary-300 transition duration-150 ease-in-out">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-white hover:text-primary-100 hover:bg-primary-700 hover:border-primary-300 focus:outline-none focus:text-primary-100 focus:bg-primary-700 focus:border-primary-300 transition duration-150 ease-in-out">
                        Register
                    </a>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </body>
</html>

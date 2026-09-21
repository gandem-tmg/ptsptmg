<!-- Sidebar desktop, selalu tampil, bisa dilipat jadi ikon saja -->
<aside :class="sidebarCollapsed ? 'lg:w-20' : 'lg:w-72'"
       class="hidden lg:fixed lg:inset-y-0 lg:left-0 lg:z-40 lg:flex lg:flex-col lg:border-r lg:border-slate-200 lg:bg-white lg:transition-all lg:duration-200">
    <div :class="{ 'sidebar-is-collapsed': sidebarCollapsed }" class="flex h-full flex-col">

        <div class="flex h-16 shrink-0 items-center justify-between gap-2 border-b border-slate-100 px-4">
            <a href="{{ route('dashboard') }}" class="flex min-w-0 items-center gap-3">
                <img src="{{ asset('logo.png') }}" alt="Logo PTSP" class="h-9 w-9 shrink-0 rounded-lg object-cover">
                <span class="sidebar-label truncate font-semibold text-slate-900">PTSP Online</span>
            </a>
            <button @click="sidebarCollapsed = !sidebarCollapsed" class="shrink-0 rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                <svg x-show="!sidebarCollapsed" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" /></svg>
                <svg x-show="sidebarCollapsed" x-cloak class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7" /></svg>
            </button>
        </div>

        <nav class="flex-1 space-y-1 overflow-y-auto overflow-x-hidden px-3 py-6">
            @include('layouts.navigation-links')
        </nav>

        <!-- Profile + Logout: SELALU langsung terlihat, bukan disembunyikan di dropdown -->
        <div class="border-t border-slate-100 p-3">
            <div class="sidebar-footer-row flex items-center gap-2 rounded-xl p-1.5 hover:bg-slate-50">
                <a href="{{ route('profile.edit') }}" title="{{ Auth::user()->name }}" class="flex min-w-0 flex-1 items-center gap-3 rounded-lg p-1">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-sm font-bold text-emerald-700">
                        {{ Str::upper(Str::substr(Auth::user()->name, 0, 1)) }}
                    </span>
                    <span class="sidebar-label min-w-0 flex-1 text-left">
                        <span class="block truncate text-sm font-medium text-slate-900">{{ Auth::user()->name }}</span>
                        <span class="block truncate text-xs text-slate-500">{{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</span>
                    </span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                    @csrf
                    <button type="submit" title="Keluar" class="rounded-lg p-2 text-slate-400 transition hover:bg-rose-50 hover:text-rose-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>

<!-- Drawer mobile: selalu full label, tidak terpengaruh status collapse desktop -->
<div x-show="sidebarOpen" x-cloak class="relative z-50 lg:hidden" role="dialog" aria-modal="true">
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60" @click="sidebarOpen = false"></div>

    <div class="fixed inset-0 flex">
        <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-200 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-150 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="relative flex w-72 max-w-[80%] flex-1 flex-col bg-white">
            <div class="flex h-16 shrink-0 items-center justify-between border-b border-slate-100 px-6">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <img src="{{ asset('logo.png') }}" alt="Logo PTSP" class="h-9 w-auto rounded-lg">
                    <span class="font-semibold text-slate-900">PTSP Online</span>
                </a>
                <button @click="sidebarOpen = false" class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-6">
                @include('layouts.navigation-links')
            </nav>
            <!-- Profile + Logout: selalu langsung terlihat di mobile juga -->
            <div class="border-t border-slate-100 p-4">
                <div class="mb-2 flex items-center gap-3 px-2">
                    <a href="{{ route('profile.edit') }}" class="flex min-w-0 flex-1 items-center gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-sm font-bold text-emerald-700">
                            {{ Str::upper(Str::substr(Auth::user()->name, 0, 1)) }}
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block truncate text-sm font-medium text-slate-900">{{ Auth::user()->name }}</span>
                            <span class="block truncate text-xs text-slate-500">{{ Auth::user()->email }}</span>
                        </span>
                    </a>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-rose-600 hover:bg-rose-50">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

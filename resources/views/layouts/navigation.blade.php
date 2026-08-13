<nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/85 shadow-sm shadow-slate-200/50 backdrop-blur-md">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('logo.png') }}" alt="Logo PTSP" class="block h-10 w-auto rounded-lg">
                    </a>
                </div>

                <div class="hidden items-center gap-1 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link href="https://wa.me/8112744431?text=Halo%20admin,%20ada%20yang%20ingin%20saya%20tanyakan" target="_blank">
                        WA Layanan
                    </x-nav-link>

                    @if(Auth::user()->role === 'admin')
                        <x-nav-link :href="route('statistics.index')" :active="request()->routeIs('statistics.*')">
                            {{ __('Statistik') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                            {{ __('Users') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.layanan.index')" :active="request()->routeIs('admin.layanan.*')">
                            {{ __('Layanan') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.persyaratan.index')" :active="request()->routeIs('admin.persyaratan.*')">
                            {{ __('Persyaratan') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.permohonan.index')" :active="request()->routeIs('admin.permohonan.*')">
                            {{ __('Permohonan') }}
                        </x-nav-link>
                    @elseif(Auth::user()->role === 'petugas')
                        <x-nav-link :href="route('statistics.index')" :active="request()->routeIs('statistics.*')">
                            {{ __('Statistik') }}
                        </x-nav-link>
                        <x-nav-link :href="route('petugas.layanan.index')" :active="request()->routeIs('petugas.layanan.*')">
                            {{ __('Layanan') }}
                        </x-nav-link>
                        <x-nav-link :href="route('petugas.persyaratan.index')" :active="request()->routeIs('petugas.persyaratan.*')">
                            {{ __('Persyaratan') }}
                        </x-nav-link>
                        <x-nav-link :href="route('petugas.permohonan.index')" :active="request()->routeIs('petugas.permohonan.*')">
                            {{ __('Permohonan') }}
                        </x-nav-link>
                    @elseif(Auth::user()->role === 'pemohon')
                        <x-nav-link :href="route('pemohon.permohonan.index')" :active="request()->routeIs('pemohon.permohonan.*')">
                            {{ __('Permohonan') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-emerald-50 hover:text-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-100 text-xs font-bold text-emerald-700">{{ Str::upper(Str::substr(Auth::user()->name, 0, 1)) }}</span>
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
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
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="https://wa.me/8112744431?text=Halo%20admin,%20ada%20yang%20ingin%20saya%20tanyakan" target="_blank">
                WA Layanan
            </x-responsive-nav-link>

            @if(Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('statistics.index')" :active="request()->routeIs('statistics.*')">
                    {{ __('Statistik') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    {{ __('Users') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.layanan.index')" :active="request()->routeIs('admin.layanan.*')">
                    {{ __('Layanan') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.persyaratan.index')" :active="request()->routeIs('admin.persyaratan.*')">
                    {{ __('Persyaratan') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.permohonan.index')" :active="request()->routeIs('admin.permohonan.*')">
                    {{ __('Permohonan') }}
                </x-responsive-nav-link>
            @elseif(Auth::user()->role === 'petugas')
                <x-responsive-nav-link :href="route('statistics.index')" :active="request()->routeIs('statistics.*')">
                    {{ __('Statistik') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('petugas.layanan.index')" :active="request()->routeIs('petugas.layanan.*')">
                    {{ __('Layanan') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('petugas.persyaratan.index')" :active="request()->routeIs('petugas.persyaratan.*')">
                    {{ __('Persyaratan') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('petugas.permohonan.index')" :active="request()->routeIs('petugas.permohonan.*')">
                    {{ __('Permohonan') }}
                </x-responsive-nav-link>
            @elseif(Auth::user()->role === 'pemohon')
                <x-responsive-nav-link :href="route('pemohon.permohonan.index')" :active="request()->routeIs('pemohon.permohonan.*')">
                    {{ __('Permohonan') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="border-t border-slate-200 px-4 py-3">
            <div class="font-medium text-base text-slate-900">{{ Auth::user()->name }}</div>
            <div class="text-sm text-slate-500">{{ Auth::user()->email }}</div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

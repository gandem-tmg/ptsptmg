<x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
    <x-slot:icon>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
    </x-slot:icon>
    Dasbor
</x-sidebar-link>

@if(Auth::user()->role === 'admin')
    <p class="sidebar-label mb-2 mt-6 flex items-center gap-1.5 px-3 text-xs font-bold uppercase tracking-wider text-emerald-700/80"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Kelola Data</p>
    <x-sidebar-link :href="route('statistics.index')" :active="request()->routeIs('statistics.*')">
        <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg></x-slot:icon>
        Statistik
    </x-sidebar-link>
    <x-sidebar-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
        <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-3.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" /></svg></x-slot:icon>
        Pengguna
    </x-sidebar-link>
    <x-sidebar-link :href="route('admin.layanan.index')" :active="request()->routeIs('admin.layanan.*')">
        <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg></x-slot:icon>
        Layanan
    </x-sidebar-link>
    <x-sidebar-link :href="route('admin.permohonan.index')" :active="request()->routeIs('admin.permohonan.*')">
        <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-3.5a1 1 0 00-.9.55l-.7 1.4a1 1 0 01-.9.55h-3a1 1 0 01-.9-.55l-.7-1.4a1 1 0 00-.9-.55H4" /></svg></x-slot:icon>
        Permohonan
    </x-sidebar-link>
    <x-sidebar-link :href="route('admin.template-surat.index')" :active="request()->routeIs('admin.template-surat.*')">
        <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg></x-slot:icon>
        Template Surat
    </x-sidebar-link>
@elseif(Auth::user()->role === 'petugas')
    <p class="sidebar-label mb-2 mt-6 flex items-center gap-1.5 px-3 text-xs font-bold uppercase tracking-wider text-emerald-700/80"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Loket PTSP</p>
    <x-sidebar-link :href="route('statistics.index')" :active="request()->routeIs('statistics.*')">
        <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg></x-slot:icon>
        Statistik
    </x-sidebar-link>
    <x-sidebar-link :href="route('petugas.permohonan.index')" :active="request()->routeIs('petugas.permohonan.index')">
        <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-3.5a1 1 0 00-.9.55l-.7 1.4a1 1 0 01-.9.55h-3a1 1 0 01-.9-.55l-.7-1.4a1 1 0 00-.9-.55H4" /></svg></x-slot:icon>
        Permohonan Terbaru
    </x-sidebar-link>
    <x-sidebar-link :href="route('petugas.permohonan.daftar')" :active="request()->routeIs('petugas.permohonan.daftar')">
        <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" /></svg></x-slot:icon>
        Daftar Permohonan
    </x-sidebar-link>
    <x-sidebar-link :href="route('petugas.permohonan.offline.index')" :active="request()->routeIs('petugas.permohonan.offline.*')">
        <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg></x-slot:icon>
        Permohonan Langsung
    </x-sidebar-link>
    <x-sidebar-link :href="route('petugas.layanan.index')" :active="request()->routeIs('petugas.layanan.*')">
        <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg></x-slot:icon>
        Daftar Layanan
    </x-sidebar-link>
@elseif(Auth::user()->role === 'petugas_seksi')
    <p class="sidebar-label mb-2 mt-6 flex items-center gap-1.5 px-3 text-xs font-bold uppercase tracking-wider text-emerald-700/80"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Seksi {{ Auth::user()->seksi->nama_seksi ?? '' }}</p>
    <x-sidebar-link :href="route('statistics.index')" :active="request()->routeIs('statistics.*')">
        <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg></x-slot:icon>
        Statistik
    </x-sidebar-link>
    <x-sidebar-link :href="route('seksi.permohonan.index')" :active="request()->routeIs('seksi.permohonan.*') || request()->routeIs('seksi.surat.*')">
        <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-3.5a1 1 0 00-.9.55l-.7 1.4a1 1 0 01-.9.55h-3a1 1 0 01-.9-.55l-.7-1.4a1 1 0 00-.9-.55H4" /></svg></x-slot:icon>
        Permohonan Seksi
    </x-sidebar-link>
    <x-sidebar-link :href="route('seksi.layanan.index')" :active="request()->routeIs('seksi.layanan.*')">
        <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg></x-slot:icon>
        Layanan Seksi Saya
    </x-sidebar-link>
@elseif(Auth::user()->role === 'pimpinan')
    <p class="sidebar-label mb-2 mt-6 flex items-center gap-1.5 px-3 text-xs font-bold uppercase tracking-wider text-emerald-700/80"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Monitoring</p>
    <x-sidebar-link :href="route('statistics.index')" :active="request()->routeIs('statistics.*')">
        <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg></x-slot:icon>
        Statistik
    </x-sidebar-link>
    <x-sidebar-link :href="route('pimpinan.monitoring.index')" :active="request()->routeIs('pimpinan.monitoring.*')">
        <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg></x-slot:icon>
        Monitoring
    </x-sidebar-link>
    <x-sidebar-link :href="route('pimpinan.permohonan.index')" :active="request()->routeIs('pimpinan.permohonan.*')">
        <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-3.5a1 1 0 00-.9.55l-.7 1.4a1 1 0 01-.9.55h-3a1 1 0 01-.9-.55l-.7-1.4a1 1 0 00-.9-.55H4" /></svg></x-slot:icon>
        Semua Permohonan
    </x-sidebar-link>
@elseif(Auth::user()->role === 'pemohon')
    <p class="sidebar-label mb-2 mt-6 flex items-center gap-1.5 px-3 text-xs font-bold uppercase tracking-wider text-emerald-700/80"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Layanan Saya</p>
    <x-sidebar-link :href="route('pemohon.permohonan.index')" :active="request()->routeIs('pemohon.permohonan.*')">
        <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-3.5a1 1 0 00-.9.55l-.7 1.4a1 1 0 01-.9.55h-3a1 1 0 01-.9-.55l-.7-1.4a1 1 0 00-.9-.55H4" /></svg></x-slot:icon>
        Permohonan Saya
    </x-sidebar-link>
    <x-sidebar-link :href="route('layanan.index')" :active="request()->routeIs('layanan.*')">
        <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 4.5v15m6-15v15M3 8.25h18M3 15.75h18M4.5 4.5h15a1.5 1.5 0 011.5 1.5v12a1.5 1.5 0 01-1.5 1.5h-15A1.5 1.5 0 013 18V6a1.5 1.5 0 011.5-1.5z" /></svg></x-slot:icon>
        Ajukan Layanan Baru
    </x-sidebar-link>
    <x-sidebar-link :href="route('pemohon.dokumen.index')" :active="request()->routeIs('pemohon.dokumen.*')">
        <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg></x-slot:icon>
        Dokumen Saya
    </x-sidebar-link>
    <x-sidebar-link href="{{ config('skm.external_url') }}" target="_blank" rel="noopener">
        <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" /></svg></x-slot:icon>
        Survei Kepuasan
    </x-sidebar-link>
@endif

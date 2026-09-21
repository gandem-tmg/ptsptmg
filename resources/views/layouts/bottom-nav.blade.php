@php
    $role = Auth::user()->role;

    // Maksimal 3 shortcut + 1 tombol Menu (buka drawer), supaya tetap ringkas di layar kecil.
    $items = match ($role) {
        'admin' => [
            ['route' => 'dashboard', 'active' => 'dashboard', 'label' => 'Beranda', 'icon' => 'home'],
            ['route' => 'admin.permohonan.index', 'active' => 'admin.permohonan.*', 'label' => 'Permohonan', 'icon' => 'inbox'],
            ['route' => 'admin.layanan.index', 'active' => 'admin.layanan.*', 'label' => 'Layanan', 'icon' => 'briefcase'],
        ],
        'petugas' => [
            ['route' => 'dashboard', 'active' => 'dashboard', 'label' => 'Beranda', 'icon' => 'home'],
            ['route' => 'petugas.permohonan.index', 'active' => 'petugas.permohonan.index', 'label' => 'Permohonan', 'icon' => 'inbox'],
            ['route' => 'petugas.permohonan.offline.index', 'active' => 'petugas.permohonan.offline.*', 'label' => 'Offline', 'icon' => 'plus'],
        ],
        'petugas_seksi' => [
            ['route' => 'dashboard', 'active' => 'dashboard', 'label' => 'Beranda', 'icon' => 'home'],
            ['route' => 'seksi.permohonan.index', 'active' => 'seksi.permohonan.*', 'label' => 'Permohonan', 'icon' => 'inbox'],
            ['route' => 'statistics.index', 'active' => 'statistics.*', 'label' => 'Statistik', 'icon' => 'chart'],
        ],
        'pimpinan' => [
            ['route' => 'dashboard', 'active' => 'dashboard', 'label' => 'Beranda', 'icon' => 'home'],
            ['route' => 'pimpinan.monitoring.index', 'active' => 'pimpinan.monitoring.*', 'label' => 'Monitoring', 'icon' => 'chart'],
            ['route' => 'pimpinan.permohonan.index', 'active' => 'pimpinan.permohonan.*', 'label' => 'Permohonan', 'icon' => 'inbox'],
        ],
        default => [ // pemohon
            ['route' => 'dashboard', 'active' => 'dashboard', 'label' => 'Beranda', 'icon' => 'home'],
            ['route' => 'pemohon.permohonan.index', 'active' => 'pemohon.permohonan.*', 'label' => 'Permohonan', 'icon' => 'inbox'],
            ['route' => 'layanan.index', 'active' => 'layanan.*', 'label' => 'Ajukan', 'icon' => 'plus'],
        ],
    };

    $icons = [
        'home' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
        'inbox' => 'M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-3.5a1 1 0 00-.9.55l-.7 1.4a1 1 0 01-.9.55h-3a1 1 0 01-.9-.55l-.7-1.4a1 1 0 00-.9-.55H4',
        'briefcase' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
        'plus' => 'M12 4.5v15m7.5-7.5h-15',
        'chart' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
    ];
@endphp

{{-- position: sticky (bukan fixed) + shrink-0 di parent flex-col: nempel di
     bawah viewport lewat flow dokumen normal, bukan lewat "keluar" dari flow
     seperti fixed — jadi tidak kena bug mobile Safari/Chrome yang butuh
     scroll dulu sebelum elemen fixed muncul penuh. --}}
<nav class="sticky inset-x-0 bottom-0 z-40 shrink-0 border-t border-slate-200 bg-white/95 backdrop-blur-md lg:hidden" style="padding-bottom: env(safe-area-inset-bottom);">
    <div class="grid" style="grid-template-columns: repeat({{ count($items) + 1 }}, minmax(0, 1fr));">
        @foreach($items as $item)
            @php $isActive = request()->routeIs($item['active']); @endphp
            <a href="{{ route($item['route']) }}" class="flex flex-col items-center gap-0.5 py-2.5 text-[11px] font-medium {{ $isActive ? 'text-emerald-600' : 'text-slate-500' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$item['icon']] }}" /></svg>
                {{ $item['label'] }}
            </a>
        @endforeach
        <button type="button" @click="sidebarOpen = true" class="flex flex-col items-center gap-0.5 py-2.5 text-[11px] font-medium text-slate-500">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
            Menu
        </button>
    </div>
</nav>

<x-public-layout>
    <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="soft-card p-6 text-center">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-green-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h1 class="text-xl font-bold text-slate-900 mb-1">Dokumen Terverifikasi Asli</h1>
            <p class="text-sm text-slate-500 mb-6">Diterbitkan oleh Kantor Kementerian Agama Kabupaten Temanggung</p>

            <div class="text-left bg-slate-50 rounded-xl p-5 space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-slate-500">Nomor Surat</span><span class="font-medium text-slate-900">{{ $surat->nomor_surat }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Jenis Layanan</span><span class="font-medium text-slate-900">{{ $surat->permohonan->layanan->nama_layanan }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Metode Tanda Tangan</span><span class="font-medium text-slate-900">{{ $surat->metode_ttd === 'tte' ? 'Elektronik' : 'Manual (Basah)' }}</span></div>
                @if($surat->penandatangan)
                <div class="flex justify-between"><span class="text-slate-500">Ditandatangani oleh</span><span class="font-medium text-slate-900">{{ $surat->penandatangan->name }}</span></div>
                @endif
                <div class="flex justify-between"><span class="text-slate-500">Tanggal Tanda Tangan</span><span class="font-medium text-slate-900">{{ $surat->tanggal_ttd?->translatedFormat('d F Y') }}</span></div>
            </div>

            <p class="text-xs text-slate-400 mt-6">
                Halaman ini hanya menampilkan info konfirmasi keaslian, bukan salinan lengkap isi surat.
            </p>
        </div>
    </div>
</x-public-layout>

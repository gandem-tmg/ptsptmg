<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Surat') }} — {{ $surat->nomor_surat }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="flash-banner rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-[13px] text-emerald-800">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium">{{ $surat->nomor_surat }}</h3>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($surat->metode_ttd == 'belum_ttd') bg-gray-100 text-gray-700
                            @elseif($surat->metode_ttd == 'manual') bg-blue-100 text-blue-700
                            @else bg-green-100 text-green-700 @endif">
                            @if($surat->metode_ttd == 'belum_ttd') Draft — Belum TTD
                            @elseif($surat->metode_ttd == 'manual') TTD Manual (Scan)
                            @else TTD Elektronik @endif
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">Untuk permohonan <strong>{{ $surat->permohonan->no_tiket }}</strong> — {{ $surat->permohonan->nama_layanan_label }}</p>
                    @if($surat->penandatangan)
                        <p class="text-sm text-gray-600">Ditandatangani oleh {{ $surat->penandatangan->name }} pada {{ $surat->tanggal_ttd->format('d/m/Y H:i') }}</p>
                    @endif

                    <a href="{{ route('seksi.surat.download', $surat) }}" target="_blank" class="inline-block mt-4 text-emerald-600 hover:text-emerald-900 text-sm font-medium">
                        Lihat / Unduh PDF Surat
                    </a>
                </div>
            </div>

            @if($surat->metode_ttd === 'belum_ttd')
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Tandatangani Surat</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="border rounded-lg p-4">
                            <h4 class="font-medium mb-2">Opsi 1: Tanda Tangan Manual</h4>
                            <p class="text-xs text-gray-500 mb-3">Cetak draft, tandatangani basah oleh pejabat berwenang, lalu scan dan unggah hasilnya di sini.</p>
                            <form method="POST" action="{{ route('seksi.surat.tandatanganManual', $surat) }}" enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="file_scan" class="block w-full text-sm mb-3" required>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">
                                    Unggah Hasil Scan
                                </button>
                            </form>
                        </div>

                        <div class="border rounded-lg p-4">
                            <h4 class="font-medium mb-2">Opsi 2: Tanda Tangan Elektronik</h4>
                            <p class="text-xs text-gray-500 mb-3">
                                Membubuhkan blok TTE + QR verifikasi langsung ke PDF.
                                <strong>Catatan:</strong> ini bukan TTE tersertifikasi BSrE, hanya penanda + verifikasi keaslian dokumen.
                            </p>
                            <form method="POST" action="{{ route('seksi.surat.tandatanganTte', $surat) }}">
                                @csrf
                                <input type="text" name="nama_penandatangan" placeholder="Nama pejabat penandatangan" class="block w-full border-gray-300 rounded-md shadow-sm text-sm mb-2" required>
                                <input type="text" name="jabatan_penandatangan" placeholder="Jabatan" class="block w-full border-gray-300 rounded-md shadow-sm text-sm mb-3" required>
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-full">
                                    Tandatangani Elektronik
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <a href="{{ route('seksi.permohonan.show', $surat->permohonan) }}" class="inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali ke Permohonan
            </a>
        </div>
    </div>
</x-app-layout>

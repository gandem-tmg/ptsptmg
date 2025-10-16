<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Permohonan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium mb-4">Informasi Permohonan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <strong>ID Permohonan:</strong> {{ $permohonan->id }}
                            </div>
                            <div>
                                <strong>Layanan:</strong> {{ $permohonan->layanan->nama_layanan }}
                            </div>
                            <div>
                                <strong>Tanggal Pengajuan:</strong> {{ $permohonan->tanggal_pengajuan->format('d/m/Y') }}
                            </div>
                            <div>
                                <strong>Status:</strong>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($permohonan->status == 'Diajukan') bg-yellow-100 text-yellow-800
                                    @elseif($permohonan->status == 'Verifikasi') bg-blue-100 text-blue-800
                                    @elseif($permohonan->status == 'Proses') bg-purple-100 text-purple-800
                                    @elseif($permohonan->status == 'Selesai') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $permohonan->status }}
                                </span>
                            </div>
                            @if($permohonan->no_tiket_admin)
                            <div>
                                <strong>No Tiket Admin:</strong> {{ $permohonan->no_tiket_admin }}
                            </div>
                            @endif
                            @if($permohonan->catatan_admin)
                            <div class="md:col-span-2">
                                <strong>Catatan Admin:</strong> {{ $permohonan->catatan_admin }}
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($permohonan->lampiranPermohonan->count() > 0)
                    <div class="mb-6">
                        <h3 class="text-lg font-medium mb-4">Lampiran</h3>
                        <div class="space-y-2">
                            @foreach($permohonan->lampiranPermohonan as $lampiran)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $lampiran->nama_file }}</p>
                                    <p class="text-sm text-gray-500">{{ $lampiran->tipe_file }} - {{ number_format($lampiran->ukuran_file / 1024, 2) }} KB</p>
                                </div>
                                <a href="{{ Storage::url($lampiran->path_file) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Download</a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="mb-6">
                        <h3 class="text-lg font-medium mb-4">Daftar Lampiran Persyaratan</h3>
                        @if($permohonan->lampiranPermohonan->count() > 0)
                        <div class="space-y-2">
                            @foreach($permohonan->lampiranPermohonan as $lampiran)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $lampiran->persyaratan->nama_persyaratan }}</p>
                                    <p class="text-sm text-gray-500">{{ $lampiran->nama_file }} - {{ number_format($lampiran->ukuran_file / 1024, 2) }} KB</p>
                                    @if($lampiran->persyaratan->wajib)
                                    <span class="text-red-600 font-semibold text-xs">(Wajib)</span>
                                    @else
                                    <span class="text-green-600 font-semibold text-xs">(Opsional)</span>
                                    @endif
                                </div>
                                <a href="{{ Storage::url($lampiran->path_file) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Download</a>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-gray-500">Belum ada lampiran yang diupload.</p>
                        @endif
                    </div>

                    <div class="flex justify-between">
                        <a href="{{ route('pemohon.permohonan.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Kembali
                        </a>
                        @if($permohonan->status == 'Selesai')
                        <a href="{{ route('pemohon.permohonan.pdf', $permohonan) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Download PDF
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

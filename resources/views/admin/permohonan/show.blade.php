<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Permohonan') }}
            </h2>
            <div>
                <a href="{{ route('admin.permohonan.edit', $permohonan) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2">
                    Edit
                </a>
                <a href="{{ route('admin.permohonan.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <strong>ID:</strong> {{ $permohonan->id }}
                    </div>
                    <div class="mb-4">
                        <strong>Layanan:</strong> {{ $permohonan->layanan->nama_layanan }}
                    </div>
                    <div class="mb-4">
                        <strong>Pemohon:</strong> {{ $permohonan->user ? $permohonan->user->name : $permohonan->nama }}
                    </div>
                    <div class="mb-4">
                        <strong>Unit Kerja:</strong> {{ $permohonan->unit_kerja }}
                    </div>
                    <div class="mb-4">
                        <strong>Tanggal Pengajuan:</strong> {{ $permohonan->tanggal_pengajuan }}
                    </div>
                    <div class="mb-4">
                        <strong>Status:</strong> {{ $permohonan->status }}
                    </div>
                    <div class="mb-4">
                        <strong>No Tiket Admin:</strong> {{ $permohonan->no_tiket_admin }}
                    </div>
                    <div class="mb-4">
                        <strong>Catatan Admin:</strong> {{ $permohonan->catatan_admin }}
                    </div>

                    <h3 class="text-lg font-medium text-gray-900 mb-4">Lampiran</h3>
                    @if($permohonan->lampiranPermohonan->count() > 0)
                        <ul class="list-disc list-inside">
                            @foreach($permohonan->lampiranPermohonan as $lampiran)
                                <li>{{ $lampiran->persyaratan->nama_persyaratan }} - <a href="{{ Storage::url($lampiran->file_path) }}" target="_blank">Lihat File</a></li>
                            @endforeach
                        </ul>
                    @else
                        <p>Tidak ada lampiran.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

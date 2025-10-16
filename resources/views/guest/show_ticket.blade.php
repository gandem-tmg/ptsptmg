<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Status Permohonan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Detail Permohonan</h3>
                        <p class="mt-1 text-sm text-gray-600">Nomor Tiket: <strong>{{ $permohonan->no_tiket }}</strong></p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-2">Informasi Pemohon</h4>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p><strong>Nama:</strong> {{ $permohonan->nama ?: $permohonan->user->name }}</p>
                                <p><strong>Alamat:</strong> {{ $permohonan->alamat ?: 'N/A' }}</p>
                                <p><strong>NIK:</strong> {{ $permohonan->nik ?: 'N/A' }}</p>
                                <p><strong>No. HP:</strong> {{ $permohonan->no_hp ?: $permohonan->user->phone ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-2">Informasi Permohonan</h4>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p><strong>Layanan:</strong> {{ $permohonan->layanan->nama_layanan }}</p>
                                <p><strong>Tanggal Pengajuan:</strong> {{ $permohonan->tanggal_pengajuan->format('d/m/Y') }}</p>
                                <p><strong>Status:</strong>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        @if($permohonan->status == 'diajukan') bg-yellow-100 text-yellow-800
                                        @elseif($permohonan->status == 'verifikasi') bg-blue-100 text-blue-800
                                        @elseif($permohonan->status == 'proses') bg-purple-100 text-purple-800
                                        @elseif($permohonan->status == 'selesai') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($permohonan->status) }}
                                    </span>
                                </p>
                                @if($permohonan->no_tiket_admin)
                                    <p><strong>No. Tiket Admin:</strong> {{ $permohonan->no_tiket_admin }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($permohonan->deskripsi)
                        <div class="mt-6">
                            <h4 class="text-md font-medium text-gray-900 mb-2">Deskripsi Permohonan</h4>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p>{{ $permohonan->deskripsi }}</p>
                            </div>
                        </div>
                    @endif

                    @if($permohonan->catatan_admin)
                        <div class="mt-6">
                            <h4 class="text-md font-medium text-gray-900 mb-2">Catatan Admin</h4>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p>{{ $permohonan->catatan_admin }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="mt-6">
                        <h4 class="text-md font-medium text-gray-900 mb-2">Lampiran Persyaratan</h4>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            @if($permohonan->lampiranPermohonan->count() > 0)
                                <ul class="list-disc list-inside">
                                    @foreach($permohonan->lampiranPermohonan as $lampiran)
                                        <li>{{ $lampiran->persyaratan->nama_persyaratan }} - <a href="{{ Storage::url($lampiran->file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800">Lihat File</a></li>
                                    @endforeach
                                </ul>
                            @else
                                <p>Tidak ada lampiran.</p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 flex space-x-4">
                        <a href="{{ route('guest.permohonan.pdf', $permohonan) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow-lg transition duration-300 ease-in-out">
                            Download PDF
                        </a>
                        <a href="{{ route('guest.searchTicket') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow-lg transition duration-300 ease-in-out">
                            Cari Tiket Lain
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

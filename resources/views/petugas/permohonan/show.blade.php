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
                                <strong>Pemohon:</strong> {{ $permohonan->user ? $permohonan->user->name : $permohonan->nama }}
                            </div>
                            <div>
                                <strong>Layanan:</strong> {{ $permohonan->layanan->nama_layanan }}
                            </div>
                            <div>
                                <strong>Unit Kerja:</strong> {{ $permohonan->unit_kerja }}
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
                        <h3 class="text-lg font-medium mb-4">Update Status</h3>
                        <form method="POST" action="{{ route('petugas.permohonan.updateStatus', $permohonan) }}">
                            @csrf
                            @method('PATCH')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="status" :value="__('Status')" />
                                    <select name="status" id="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <option value="Diajukan" {{ $permohonan->status == 'Diajukan' ? 'selected' : '' }}>Diajukan</option>
                                        <option value="Verifikasi" {{ $permohonan->status == 'Verifikasi' ? 'selected' : '' }}>Verifikasi</option>
                                        <option value="Proses" {{ $permohonan->status == 'Proses' ? 'selected' : '' }}>Proses</option>
                                        <option value="Selesai" {{ $permohonan->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="Ditolak" {{ $permohonan->status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-6">
                                <x-primary-button>
                                    {{ __('Update Status') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>

                    <div class="flex justify-start">
                        <a href="{{ route('petugas.permohonan.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

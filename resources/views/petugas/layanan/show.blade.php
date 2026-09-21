<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Layanan (Petugas)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <strong>Kode Layanan:</strong> {{ $layanan->kode_layanan }}
                    </div>
                    <div class="mb-4">
                        <strong>Nama Layanan:</strong> {{ $layanan->nama_layanan }}
                    </div>
                    <div class="mb-4">
                        <strong>Deskripsi:</strong> {{ $layanan->deskripsi }}
                    </div>

                    <h3 class="text-lg font-medium text-gray-900 mb-4">Persyaratan</h3>
                    @if($layanan->persyaratan->count() > 0)
                        <ul class="list-disc list-inside">
                            @foreach($layanan->persyaratan as $persyaratan)
                                <li>{{ $persyaratan->nama_persyaratan }} - {{ $persyaratan->deskripsi }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p>Tidak ada persyaratan yang terkait.</p>
                    @endif

                    <a href="{{ route('petugas.layanan.index') }}" class="mt-4 inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

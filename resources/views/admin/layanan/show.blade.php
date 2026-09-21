<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Layanan') }}
            </h2>
            <div>
                <a href="{{ route('admin.layanan.edit', $layanan) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2">
                    Edit
                </a>
                <a href="{{ route('admin.layanan.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
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
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

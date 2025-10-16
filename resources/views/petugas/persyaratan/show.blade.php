<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Persyaratan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">Detail Persyaratan</h3>
                        <a href="{{ route('petugas.persyaratan.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Kembali</a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <strong>ID:</strong> {{ $persyaratan->id }}
                        </div>
                        <div>
                            <strong>Layanan:</strong> {{ $persyaratan->layanan->nama_layanan }}
                        </div>
                        <div>
                            <strong>Nama Persyaratan:</strong> {{ $persyaratan->nama_persyaratan }}
                        </div>
                        <div>
                            <strong>Wajib:</strong>
                            @if($persyaratan->wajib)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Wajib</span>
                            @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Opsional</span>
                            @endif
                        </div>
                        <div class="md:col-span-2">
                            <strong>Deskripsi:</strong>
                            <p class="mt-2">{{ $persyaratan->deskripsi }}</p>
                        </div>
                        <div>
                            <strong>Dibuat:</strong> {{ $persyaratan->created_at->format('d/m/Y H:i') }}
                        </div>
                        <div>
                            <strong>Diupdate:</strong> {{ $persyaratan->updated_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

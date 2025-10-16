<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Persyaratan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.persyaratan.update', $persyaratan) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <x-input-label for="layanan_id" :value="__('Pilih Layanan')" />
                            <select name="layanan_id" id="layanan_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">-- Pilih Layanan --</option>
                                @foreach($layanans as $layanan)
                                <option value="{{ $layanan->id }}" {{ $layanan->id == $persyaratan->layanan_id ? 'selected' : '' }}>{{ $layanan->nama_layanan }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('layanan_id')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="nama_persyaratan" :value="__('Nama Persyaratan')" />
                            <x-text-input id="nama_persyaratan" name="nama_persyaratan" type="text" class="mt-1 block w-full" :value="old('nama_persyaratan', $persyaratan->nama_persyaratan)" required />
                            <x-input-error :messages="$errors->get('nama_persyaratan')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="deskripsi" :value="__('Deskripsi')" />
                            <textarea id="deskripsi" name="deskripsi" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4">{{ old('deskripsi', $persyaratan->deskripsi) }}</textarea>
                            <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="wajib" :value="__('Wajib')" />
                            <input type="checkbox" id="wajib" name="wajib" value="1" {{ old('wajib', $persyaratan->wajib) ? 'checked' : '' }} class="mt-1">
                            <label for="wajib" class="ml-2 text-sm text-gray-600">Centang jika persyaratan ini wajib</label>
                            <x-input-error :messages="$errors->get('wajib')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.persyaratan.show', $persyaratan) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">Batal</a>
                            <x-primary-button>
                                {{ __('Update') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

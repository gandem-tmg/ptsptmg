<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Template Surat') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.template-surat.update', $template) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Layanan</label>
                            <select name="layanan_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                @foreach($layanans as $layanan)
                                    <option value="{{ $layanan->id }}" {{ old('layanan_id', $template->layanan_id) == $layanan->id ? 'selected' : '' }}>{{ $layanan->nama_layanan }}</option>
                                @endforeach
                            </select>
                            @error('layanan_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Judul Template</label>
                            <input type="text" name="judul_template" value="{{ old('judul_template', $template->judul_template) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('judul_template')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Isi Surat</label>
                            <div class="mb-2 text-xs text-gray-500 bg-gray-50 border rounded p-2">
                                Placeholder yang bisa dipakai: <code>@{{nama_pemohon}}</code>, <code>@{{nik}}</code>,
                                <code>@{{alamat_pemohon}}</code>, <code>@{{no_tiket}}</code>, <code>@{{nama_layanan}}</code>,
                                <code>@{{nomor_surat}}</code>, <code>@{{tanggal_surat}}</code>
                            </div>
                            <textarea name="isi_template" rows="12" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm font-mono text-sm" required>{{ old('isi_template', $template->isi_template) }}</textarea>
                            @error('isi_template')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="mb-6 flex items-center">
                            <input type="checkbox" name="aktif" id="aktif" value="1" {{ old('aktif', $template->aktif) ? 'checked' : '' }} class="rounded border-gray-300">
                            <label for="aktif" class="ml-2 text-sm text-gray-700">Aktif (bisa dipakai petugas seksi)</label>
                        </div>

                        <div class="flex justify-between">
                            <a href="{{ route('admin.template-surat.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Batal</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Perbarui</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

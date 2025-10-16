<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Permohonan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.permohonan.update', $permohonan) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                <option value="Diajukan" {{ $permohonan->status == 'Diajukan' ? 'selected' : '' }}>Diajukan</option>
                                <option value="Verifikasi" {{ $permohonan->status == 'Verifikasi' ? 'selected' : '' }}>Verifikasi</option>
                                <option value="Proses" {{ $permohonan->status == 'Proses' ? 'selected' : '' }}>Proses</option>
                                <option value="Selesai" {{ $permohonan->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="Ditolak" {{ $permohonan->status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="no_tiket_admin" class="block text-sm font-medium text-gray-700">No Tiket Admin</label>
                            <input type="text" name="no_tiket_admin" id="no_tiket_admin" value="{{ old('no_tiket_admin', $permohonan->no_tiket_admin) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('no_tiket_admin')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="catatan_admin" class="block text-sm font-medium text-gray-700">Catatan Admin</label>
                            <textarea name="catatan_admin" id="catatan_admin" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('catatan_admin', $permohonan->catatan_admin) }}</textarea>
                            @error('catatan_admin')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('admin.permohonan.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Batal
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

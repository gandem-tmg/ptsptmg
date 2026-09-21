<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Input Permohonan Walk-in') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="text-sm text-gray-600 mb-6">Untuk pemohon yang datang langsung ke loket PTSP. Data tetap tercatat penuh di sistem, sama seperti pengajuan online.</p>

                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('petugas.permohonan.walkInStore') }}" enctype="multipart/form-data">
                        @csrf

                        <h3 class="text-lg font-medium mb-2">Biodata Pemohon</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <x-input-label for="nama" value="Nama Lengkap" />
                                <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" :value="old('nama')" required />
                            </div>
                            <div>
                                <x-input-label for="nik" value="NIK" />
                                <x-text-input id="nik" name="nik" type="text" maxlength="16" class="mt-1 block w-full" :value="old('nik')" required />
                            </div>
                            <div>
                                <x-input-label for="no_hp" value="No. HP" />
                                <x-text-input id="no_hp" name="no_hp" type="text" class="mt-1 block w-full" :value="old('no_hp')" required />
                            </div>
                            <div>
                                <x-input-label for="ktp" value="Scan/Foto KTP" />
                                <input type="file" id="ktp" name="ktp" class="mt-1 block w-full" required>
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="alamat" value="Alamat" />
                                <textarea id="alamat" name="alamat" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>{{ old('alamat') }}</textarea>
                            </div>
                        </div>

                        <h3 class="text-lg font-medium mb-2">Layanan yang Diajukan</h3>
                        <div class="mb-4">
                            <x-input-label for="layanan_id" value="Layanan" />
                            <select id="layanan_id" name="layanan_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" onchange="updateLampiran()" required>
                                @foreach($layanans as $layanan)
                                <option value="{{ $layanan->id }}" data-seksi="{{ $layanan->seksi->nama_seksi ?? 'belum diatur' }}">{{ $layanan->nama_layanan }}</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">
                                Akan didisposisikan ke <span id="seksi-tujuan" class="font-medium">{{ $layanans->first()->seksi->nama_seksi ?? 'belum diatur' }}</span>.
                            </p>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="deskripsi" value="Deskripsi Permohonan (opsional)" />
                            <textarea id="deskripsi" name="deskripsi" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('deskripsi') }}</textarea>
                        </div>

                        <div class="mb-6">
                            <h4 class="text-md font-medium mb-2">Lampiran Persyaratan</h4>
                            <div id="lampiran-container"></div>
                        </div>

                        <div class="flex justify-between">
                            <a href="{{ route('petugas.permohonan.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Batal</a>
                            <x-primary-button>Simpan & Cetak Bukti</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const persyaratanData = @json($persyaratanByLayanan);

        function updateLampiran() {
            const select = document.getElementById('layanan_id');
            const layananId = select.value;
            document.getElementById('seksi-tujuan').textContent = select.options[select.selectedIndex]?.dataset.seksi || 'belum diatur';

            const container = document.getElementById('lampiran-container');
            container.innerHTML = '';

            if (persyaratanData[layananId]) {
                persyaratanData[layananId].forEach((persyaratan, index) => {
                    const markWajib = persyaratan.wajib ? '<span class="required-mark">*</span>' : '';
                    const div = document.createElement('div');
                    div.className = 'mb-2';
                    div.innerHTML = `
                        <label class="block text-sm font-medium text-gray-700">${persyaratan.nama_persyaratan}${markWajib}</label>
                        <input type="file" name="lampiran[${index}]" class="mt-1 block w-full" />
                    `;
                    container.appendChild(div);
                });
            }
        }

        document.addEventListener('DOMContentLoaded', updateLampiran);
    </script>
</x-app-layout>

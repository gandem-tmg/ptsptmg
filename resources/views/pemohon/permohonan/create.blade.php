<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ajukan Permohonan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('pemohon.permohonan.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="layanan_id" class="block text-sm font-medium text-gray-700">Layanan</label>
                            <select id="layanan_id" name="layanan_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" onchange="updateLampiran()">
                                @foreach($layanans as $layanan)
                                <option value="{{ $layanan->id }}">{{ $layanan->nama_layanan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="unit_kerja" class="block text-sm font-medium text-gray-700">Unit Kerja Tujuan</label>
                            <select id="unit_kerja" name="unit_kerja" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                                <option value="">Pilih Unit Kerja</option>
                                <option value="Sub bagian TU">Sub bagian TU</option>
                                <option value="Penma">Penma</option>
                                <option value="PAIS">PAIS</option>
                                <option value="PdPontren">PdPontren</option>
                                <option value="BIMAS Islam">BIMAS Islam</option>
                                <option value="PLHUT">PLHUT</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi Permohonan</label>
                            <textarea id="deskripsi" name="deskripsi" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"></textarea>
                        </div>
                        <div class="mb-4">
                            <h3 class="text-lg font-medium mb-2">Lampiran Persyaratan</h3>
                            <div id="lampiran-container">
                                @if($layanans->isNotEmpty() && $layanans->first()->persyaratan->isNotEmpty())
                                    @foreach($layanans->first()->persyaratan as $index => $persyaratan)
                                    <div class="mb-2">
                                        <label for="lampiran_{{ $index }}" class="block text-sm font-medium text-gray-700">{{ $persyaratan->nama_persyaratan }}</label>
                                        <input type="file" id="lampiran_{{ $index }}" name="lampiran[{{ $index }}]" class="mt-1 block w-full" />
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <button type="submit" class="bg-green-800 hover:bg-green-900 text-white font-bold py-2 px-4 rounded-lg shadow-lg transition duration-300 ease-in-out">
                            Ajukan Permohonan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const persyaratanData = @json($persyaratanByLayanan);

        function updateLampiran() {
            const layananId = document.getElementById('layanan_id').value;
            const container = document.getElementById('lampiran-container');
            container.innerHTML = '';

            if (persyaratanData[layananId]) {
                persyaratanData[layananId].forEach((persyaratan, index) => {
                    const div = document.createElement('div');
                    div.className = 'mb-2';
                    div.innerHTML = `
                        <label for="lampiran_${index}" class="block text-sm font-medium text-gray-700">${persyaratan.nama_persyaratan}</label>
                        <input type="file" id="lampiran_${index}" name="lampiran[${index}]" class="mt-1 block w-full" />
                    `;
                    container.appendChild(div);
                });
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateLampiran();
        });
    </script>
</x-app-layout>

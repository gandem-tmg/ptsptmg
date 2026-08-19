<x-guest-layout>
    <div class="w-full">
        <div class="mb-6 flex items-center justify-between gap-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-emerald-600">Langkah 2</p>
                <h2 class="mt-2 text-2xl font-bold text-slate-900">Ajukan Permohonan</h2>
            </div>
            <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700">2 / 2</span>
        </div>

        <div class="mb-6 rounded-2xl border border-sky-100 bg-sky-50 p-4">
            <p class="text-sm font-medium text-sky-800">Pilih layanan, tentukan unit kerja, lalu unggah file persyaratan sesuai kebutuhan.</p>
        </div>

        <form method="POST" action="{{ route('guest.permohonan.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label for="layanan_id" class="field-label">Layanan</label>
                <select id="layanan_id" name="layanan_id" class="form-input" onchange="updateLampiran()">
                    @foreach($layanans as $layanan)
                        <option value="{{ $layanan->id }}">{{ $layanan->nama_layanan }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="unit_kerja" class="field-label">Unit Kerja Tujuan</label>
                <select id="unit_kerja" name="unit_kerja" class="form-input">
                    <option value="">Pilih Unit Kerja</option>
                    <option value="Sub bagian TU">Sub bagian TU</option>
                    <option value="Penma">Penma</option>
                    <option value="PAIS">PAIS</option>
                    <option value="PdPontren">PdPontren</option>
                    <option value="BIMAS Islam">BIMAS Islam</option>
                    <option value="Garazawa">GaraZawa</option>
                    <option value="Garakristen">GaraKristen</option>
                    <option value="Garakatolik">Gara Katolik</option>
                    <option value="Garabudha">GaraBudha</option>
                    <option value="PLHUT">PLHUT</option>
                </select>
            </div>

            <div>
                <label for="deskripsi" class="field-label">Deskripsi Permohonan</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" class="form-input" placeholder="Jelaskan kebutuhan atau tujuan permohonan Anda"></textarea>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                <h3 class="mb-3 text-base font-semibold text-slate-900">Lampiran Persyaratan</h3>
                <div id="lampiran-container" class="space-y-3">
                    @if($layanans->isNotEmpty() && $layanans->first()->persyaratan->isNotEmpty())
                        @foreach($layanans->first()->persyaratan as $index => $persyaratan)
                            <div>
                                <label for="lampiran_{{ $index }}" class="field-label">{{ $persyaratan->nama_persyaratan }}</label>
                                <input type="file" id="lampiran_{{ $index }}" name="lampiran[{{ $index }}]" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 shadow-sm transition duration-200 file:mr-4 file:rounded-md file:border-0 file:bg-emerald-50 file:px-3 file:py-2 file:text-sm file:font-medium file:text-emerald-700 hover:file:bg-emerald-100 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" />
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                <a href="{{ route('guest.permohonan.biodata') }}" class="secondary-btn w-full sm:w-auto">Kembali</a>
                <button type="submit" class="primary-btn w-full sm:w-auto sm:ml-auto">
                    Ajukan Permohonan
                </button>
            </div>
        </form>
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
                    div.innerHTML = `
                        <label for="lampiran_${index}" class="field-label">${persyaratan.nama_persyaratan}</label>
                        <input type="file" id="lampiran_${index}" name="lampiran[${index}]" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 shadow-sm transition duration-200 file:mr-4 file:rounded-md file:border-0 file:bg-emerald-50 file:px-3 file:py-2 file:text-sm file:font-medium file:text-emerald-700 hover:file:bg-emerald-100 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" />
                    `;
                    container.appendChild(div);
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateLampiran();
        });
    </script>
</x-guest-layout>

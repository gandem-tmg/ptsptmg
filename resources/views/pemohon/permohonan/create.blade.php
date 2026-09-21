<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">Ajukan Permohonan Baru</h2>
    </x-slot>

    <div class="mx-auto max-w-3xl space-y-6 py-2">

        @if($errors->any())
            <div class="flash-banner rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                <ul class="list-inside list-disc space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('pemohon.permohonan.store') }}" enctype="multipart/form-data">
            @csrf

            @php $selectedLayanan = $selectedLayananId ? $layanans->firstWhere('id', $selectedLayananId) : null; @endphp

            <!-- Pilih layanan -->
            <div class="soft-card p-5">
                <h3 class="mb-4 text-sm font-semibold text-slate-900">1. Layanan yang Diajukan</h3>

                @if($selectedLayanan)
                    <!-- Layanan sudah dipilih dari halaman detail — dikunci, tidak bisa diganti di sini -->
                    <input type="hidden" name="layanan_id" id="layanan_id" value="{{ $selectedLayanan->id }}">
                    <div class="flex items-start justify-between gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-900">{{ $selectedLayanan->nama_layanan }}</p>
                            <p class="mt-0.5 text-xs text-slate-500">{{ $selectedLayanan->seksi->nama_seksi ?? '-' }}</p>
                        </div>
                        <a href="{{ route('layanan.index') }}" class="shrink-0 text-xs font-semibold text-emerald-600 hover:text-emerald-700">
                            Ganti Layanan
                        </a>
                    </div>
                    <p id="seksi-tujuan-holder" data-seksi="{{ $selectedLayanan->seksi->nama_seksi ?? 'belum diatur' }}" class="hidden"></p>
                @else
                    <!-- Fallback: belum ada layanan terpilih (akses langsung tanpa lewat halaman detail) -->
                    <label for="layanan_id" class="mb-1.5 block text-sm font-medium text-slate-700">Pilih layanan</label>
                    <select id="layanan_id" name="layanan_id" onchange="updateLampiran()"
                            class="block w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-200">
                        <option value="">-- Pilih salah satu --</option>
                        @foreach($layanans as $layanan)
                        <option value="{{ $layanan->id }}" data-seksi="{{ $layanan->seksi->nama_seksi ?? 'belum diatur' }}">
                            {{ $layanan->nama_layanan }}
                        </option>
                        @endforeach
                    </select>
                @endif

                <div class="mt-3 flex items-start gap-2.5 rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>Permohonan akan diverifikasi PTSP lalu otomatis didisposisikan ke <strong id="seksi-tujuan">{{ $selectedLayanan->seksi->nama_seksi ?? 'belum diatur' }}</strong>.</span>
                </div>

                <label for="deskripsi" class="mb-1.5 mt-5 block text-sm font-medium text-slate-700">Deskripsi permohonan <span class="font-normal text-slate-400">(opsional)</span></label>
                <textarea id="deskripsi" name="deskripsi" rows="3" placeholder="Jelaskan singkat keperluan Anda, kalau ada hal khusus yang perlu diketahui petugas..."
                          class="block w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-200"></textarea>
            </div>

            <!-- Lampiran -->
            <div class="soft-card mt-6 p-6">
                <h3 class="mb-1 text-sm font-semibold text-slate-900">2. Unggah Persyaratan</h3>
                <p class="mb-4 text-xs text-slate-500">Format PDF/JPG/PNG, maksimal 2MB per file. Tanda <span class="required-mark">*</span> berarti wajib diunggah.</p>
                <div id="lampiran-container" class="space-y-4"></div>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <a href="{{ route('layanan.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700">Batal</a>
                <button type="submit" class="primary-btn px-6">
                    Ajukan Permohonan
                </button>
            </div>
        </form>
    </div>

    <script>
        const persyaratanData = @json($persyaratanByLayanan);

        function fileInputTemplate(index, label, wajib) {
            const markWajib = wajib ? '<span class="required-mark">*</span>' : '';
            return `
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">${label}${markWajib}</label>
                    <label class="group relative flex cursor-pointer items-center gap-3 rounded-xl border-2 border-dashed border-slate-200 bg-slate-50 px-4 py-3.5 transition hover:border-emerald-300 hover:bg-emerald-50/60">
                        <input type="file" name="lampiran[${index}]" data-file-input
                               class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                               onchange="handleFileChange(this)" accept=".pdf,.jpg,.jpeg,.png">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white text-slate-400 shadow-sm group-hover:text-emerald-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l-3 3m3-3l3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" /></svg>
                        </span>
                        <span class="min-w-0 flex-1 truncate text-sm text-slate-500 group-hover:text-emerald-700" data-file-label>
                            Klik untuk pilih file
                        </span>
                    </label>
                </div>
            `;
        }

        function handleFileChange(input) {
            const label = input.parentElement.querySelector('[data-file-label]');
            if (input.files && input.files.length > 0) {
                label.textContent = input.files[0].name;
                label.classList.add('text-slate-800', 'font-medium');
            } else {
                label.textContent = 'Klik untuk pilih file';
                label.classList.remove('text-slate-800', 'font-medium');
            }
        }

        function updateLampiran() {
            const layananField = document.getElementById('layanan_id');
            const layananId = layananField ? layananField.value : '';

            const seksiLabel = document.getElementById('seksi-tujuan');
            if (seksiLabel && layananField && layananField.tagName === 'SELECT') {
                seksiLabel.textContent = layananField.options[layananField.selectedIndex]?.dataset.seksi || 'belum diatur';
            }

            const container = document.getElementById('lampiran-container');
            container.innerHTML = '';

            if (!layananId) {
                container.innerHTML = '<p class="text-sm text-slate-400">Pilih layanan terlebih dahulu.</p>';
                return;
            }

            const persyaratanList = persyaratanData[layananId] || [];
            if (persyaratanList.length === 0) {
                container.innerHTML = '<p class="text-sm text-slate-400">Layanan ini tidak memerlukan lampiran.</p>';
                return;
            }

            persyaratanList.forEach((persyaratan, index) => {
                container.insertAdjacentHTML('beforeend', fileInputTemplate(index, persyaratan.nama_persyaratan, persyaratan.wajib));
            });
        }

        document.addEventListener('DOMContentLoaded', updateLampiran);
    </script>
</x-app-layout>

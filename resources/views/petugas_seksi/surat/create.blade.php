<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Generate Surat') }} — {{ $permohonan->no_tiket }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($permohonan->is_manual)
                        <div class="bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded text-sm">
                            Permohonan ini adalah layanan di luar katalog (input manual), jadi tidak ada template surat yang bisa dipakai.
                        </div>
                    @elseif($templates->isEmpty())
                        <div class="bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded text-sm">
                            Belum ada template surat untuk layanan <strong>{{ $permohonan->nama_layanan_label }}</strong>.
                            Minta admin buat template dulu di menu Template Surat.
                        </div>
                    @else
                        <form method="POST" action="{{ route('seksi.surat.store', $permohonan) }}">
                            @csrf
                            <div class="mb-4">
                                <x-input-label for="template_surat_id" value="Pilih Template" />
                                <select name="template_surat_id" id="template_surat_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                    @foreach($templates as $template)
                                        <option value="{{ $template->id }}">{{ $template->judul_template }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="text-xs text-gray-500 mb-4">
                                Nomor surat akan digenerate otomatis. Surat yang dihasilkan berupa draft — Anda tetap perlu menandatanganinya (manual atau elektronik) di langkah berikutnya.
                            </p>
                            <div class="flex justify-between">
                                <a href="{{ route('seksi.permohonan.show', $permohonan) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Batal</a>
                                <x-primary-button>Generate Draft Surat</x-primary-button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

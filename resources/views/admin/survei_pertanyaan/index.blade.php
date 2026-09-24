<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-900">Pertanyaan Survei (SKM)</h2>
    </x-slot>

    <div class="py-6 space-y-4">
        @if(session('success'))
            <div class="flash-banner rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>
        @endif

        <div class="flex justify-end">
            <a href="{{ route('admin.survei-pertanyaan.create') }}" class="rounded-lg bg-emerald-600 px-3.5 py-2 text-[13px] font-semibold text-white transition-all duration-150 hover:bg-emerald-700 active:scale-[.97]">
                + Tambah Pertanyaan
            </a>
        </div>

        <x-data-table :empty="$pertanyaans->isEmpty()" empty-message="Belum ada pertanyaan survei. Tambahkan dulu lewat tombol di atas.">
            <thead>
                <tr>
                    <th>Urutan</th>
                    <th>Pertanyaan</th>
                    <th>Tipe</th>
                    <th>Jenis Survei</th>
                    <th>Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pertanyaans as $p)
                    <tr class="row-clickable" onclick="location.href='{{ route('admin.survei-pertanyaan.edit', $p) }}'">
                        <td>{{ $p->urutan }}</td>
                        <td class="max-w-md font-medium text-slate-900">{{ $p->teks_pertanyaan }}</td>
                        <td>{{ ['skala_4' => 'Skala 1-4', 'pilihan_ganda' => 'Pilihan Ganda', 'teks' => 'Teks Bebas'][$p->tipe] }}</td>
                        <td>{{ ['per_layanan' => 'Per-Layanan', 'umum' => 'Umum', 'keduanya' => 'Keduanya'][$p->jenis_survei] }}</td>
                        <td>
                            @if($p->aktif)
                                <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Aktif</span>
                            @else
                                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-500">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-right" onclick="event.stopPropagation()">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.survei-pertanyaan.edit', $p) }}" title="Edit" class="icon-action icon-action-edit">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.survei-pertanyaan.destroy', $p) }}" onsubmit="return confirm('Hapus/nonaktifkan pertanyaan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus/Nonaktifkan" class="icon-action icon-action-danger">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </x-data-table>
    </div>
</x-app-layout>

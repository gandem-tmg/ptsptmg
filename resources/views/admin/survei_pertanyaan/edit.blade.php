<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-900">Edit Pertanyaan Survei</h2>
    </x-slot>

    <div class="py-6">
        <div class="soft-card max-w-2xl p-6">
            <form method="POST" action="{{ route('admin.survei-pertanyaan.update', $pertanyaan) }}">
                @csrf
                @method('PUT')
                @include('admin.survei_pertanyaan._form')

                <div class="mt-6 flex gap-2">
                    <button type="submit" class="rounded-lg bg-emerald-600 px-3.5 py-2 text-[13px] font-semibold text-white transition-all duration-150 hover:bg-emerald-700 active:scale-[.97]">Simpan Perubahan</button>
                    <a href="{{ route('admin.survei-pertanyaan.index') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

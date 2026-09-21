<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-900">Survei Kepuasan</h2>
    </x-slot>

    <div class="py-2">
        <div class="mx-auto max-w-2xl space-y-5">

            <!-- Hero -->
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-start gap-4 border-l-4 border-emerald-700 bg-emerald-50/40 px-5 py-6 sm:px-7 sm:py-7">
                    <span class="hidden h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-emerald-700 text-white sm:flex">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.75h-.152c-3.196 0-6.1-1.248-8.25-3.286z" /></svg>
                    </span>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">Survei Kepuasan Masyarakat</p>
                        <p class="mt-1 text-lg font-bold leading-snug text-slate-900">Bagaimana pengalaman Anda dengan layanan ini?</p>
                        <p class="mt-1.5 text-sm text-slate-600">Penilaian jujur Anda membantu kami menjaga kualitas pelayanan publik.</p>

                        <div class="mt-4 flex flex-wrap items-center gap-x-5 gap-y-1.5 border-t border-slate-200 pt-3 text-sm">
                            <span class="inline-flex items-center gap-1.5 font-medium text-slate-700">
                                <svg class="h-4 w-4 shrink-0 text-emerald-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                {{ $permohonan->layanan->nama_layanan }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 text-slate-500">
                                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                {{ $permohonan->no_tiket }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            @if($pertanyaans->isEmpty())
                <div class="soft-card p-5 text-center text-sm text-slate-500">
                    Survei belum tersedia saat ini.
                </div>
            @else
                <form method="POST" action="{{ route('pemohon.permohonan.survei.store', $permohonan) }}" class="space-y-3">
                    @csrf
                    @include('survei._pertanyaan_fields', ['pertanyaans' => $pertanyaans])

                    <button type="submit" class="primary-btn w-full py-3 text-sm">
                        Kirim Penilaian
                    </button>
                    <p class="text-center text-xs text-slate-400">Jawaban Anda tercatat anonim untuk laporan agregat & tidak memengaruhi layanan yang akan datang.</p>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>

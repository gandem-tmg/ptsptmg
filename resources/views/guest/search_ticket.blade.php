<x-public-layout>
    <div class="mx-auto flex max-w-lg flex-col items-center px-4 py-16 sm:px-6 lg:px-8">

        <div class="mb-7 text-center">
            <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-emerald-700">
                Status Permohonan
            </span>
            <h1 class="mt-3 text-2xl font-bold text-slate-900 sm:text-[28px]">Cek Status Permohonan Anda</h1>
            <p class="mt-2 text-sm leading-relaxed text-slate-600">Masukkan nomor tiket yang tertera pada bukti pengajuan Anda.</p>
        </div>

        <div class="w-full overflow-hidden rounded-3xl border border-slate-200/70 bg-white shadow-xl shadow-emerald-900/5">
            <div class="p-6 sm:p-7">
                @if($errors->any())
                    <div class="mb-4 flex items-start gap-2.5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                        <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.007v.008H12v-.008zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('guest.showTicket') }}">
                    @csrf
                    <label for="no_tiket" class="mb-2 block text-sm font-medium text-slate-700">Nomor Tiket</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </span>
                        <input type="text" id="no_tiket" name="no_tiket" value="{{ old('no_tiket') }}" placeholder="Contoh: 250912-014" required
                               class="block w-full rounded-xl border-slate-200 py-3 pl-11 text-center text-base tracking-wide text-slate-800 placeholder:text-slate-300 focus:border-emerald-500 focus:ring-emerald-200">
                    </div>

                    <button type="submit" class="mt-5 w-full rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                        Cek Status
                    </button>
                </form>
            </div>
        </div>

        <p class="mt-6 max-w-sm text-center text-sm leading-relaxed text-slate-600">
            Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-emerald-700 hover:text-emerald-800">Masuk</a> untuk melihat riwayat semua permohonan Anda sekaligus.
        </p>
    </div>
</x-public-layout>

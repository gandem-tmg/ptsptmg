<x-guest-layout>
    <div class="w-full">
        @if(session('submitted_ticket'))
            <div class="mb-5 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                <p class="font-semibold">Permohonan berhasil diajukan.</p>
                <p class="mt-1">No Tiket Anda: <strong>{{ session('submitted_ticket') }}</strong>. Simpan nomor tiket ini untuk melacak status permohonan Anda.</p>
                @if(session('submitted_permohonan_id'))
                    <div class="mt-3">
                        <a href="{{ route('guest.permohonan.pdf', session('submitted_permohonan_id')) }}" class="primary-btn w-full sm:w-auto">
                            Download Tanda Bukti Permohonan
                        </a>
                    </div>
                @endif
            </div>
        @endif

        <div class="mb-6">
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-emerald-600">Pelacakan</p>
            <h2 class="mt-2 text-2xl font-bold text-slate-900">Cari Tiket Permohonan</h2>
        </div>

        <form method="POST" action="{{ route('guest.showTicket') }}" class="space-y-4">
            @csrf

            <div>
                <label for="no_tiket" class="field-label">Nomor Tiket</label>
                <input id="no_tiket" type="text" name="no_tiket" value="{{ old('no_tiket') }}" required class="form-input" placeholder="Masukkan nomor tiket">
                @error('no_tiket')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="primary-btn w-full">
                Cari Tiket
            </button>
        </form>
    </div>
</x-guest-layout>

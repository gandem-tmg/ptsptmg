<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            @if(session('submitted_ticket'))
                <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded">
                    Permohonan berhasil diajukan. No Tiket Anda: <strong>{{ session('submitted_ticket') }}</strong>. Simpan nomor tiket ini untuk melacak status permohonan Anda.
                    @if(session('submitted_permohonan_id'))
                        <div class="mt-2">
                            <a href="{{ route('guest.permohonan.pdf', session('submitted_permohonan_id')) }}" class="bg-green-500 text-white font-bold py-2 px-4 rounded-lg shadow-lg">
                                Download Tanda Bukti Permohonan
                            </a>
                        </div>
                    @endif
                </div>
            @endif
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Cari Tiket Permohonan</h2>

            <form method="POST" action="{{ route('guest.showTicket') }}">
                @csrf

                <div class="mb-4">
                    <label for="no_tiket" class="block text-sm font-medium text-gray-700">Nomor Tiket</label>
                    <input id="no_tiket" type="text" name="no_tiket" value="{{ old('no_tiket') }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500" placeholder="Masukkan nomor tiket">
                    @error('no_tiket')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-green-500 text-white font-bold py-2 px-4 rounded-lg shadow-lg w-full">
                        Cari Tiket
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>

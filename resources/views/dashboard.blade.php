<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}

                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Statistik Dashboard</h3>
                        @if(auth()->user()->role === 'pemohon')
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                                <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                                    <div class="flex items-center">
                                        <div class="p-3 rounded-full bg-blue-100">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h4 class="text-sm font-medium text-gray-500">Total Layanan</h4>
                                            <p class="text-2xl font-bold text-gray-900">{{ $totalLayanan }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                                    <div class="flex items-center">
                                        <div class="p-3 rounded-full bg-green-100">
                                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h4 class="text-sm font-medium text-gray-500">Permohonan Saya</h4>
                                            <p class="text-2xl font-bold text-gray-900">{{ $totalPermohonan }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                                    <div class="flex items-center">
                                        <div class="p-3 rounded-full bg-yellow-100">
                                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h4 class="text-sm font-medium text-gray-500">Diajukan</h4>
                                            <p class="text-2xl font-bold text-gray-900">{{ $permohonanDiajukan }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                                    <div class="flex items-center">
                                        <div class="p-3 rounded-full bg-purple-100">
                                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h4 class="text-sm font-medium text-gray-500">Selesai</h4>
                                            <p class="text-2xl font-bold text-gray-900">{{ $permohonanSelesai }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                                <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                                    <div class="flex items-center">
                                        <div class="p-3 rounded-full bg-blue-100">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h4 class="text-sm font-medium text-gray-500">Total Layanan</h4>
                                            <p class="text-2xl font-bold text-gray-900">{{ $totalLayanan }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                                    <div class="flex items-center">
                                        <div class="p-3 rounded-full bg-green-100">
                                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h4 class="text-sm font-medium text-gray-500">Total Permohonan</h4>
                                            <p class="text-2xl font-bold text-gray-900">{{ $totalPermohonan }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                                    <div class="flex items-center">
                                        <div class="p-3 rounded-full bg-yellow-100">
                                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h4 class="text-sm font-medium text-gray-500">Diajukan</h4>
                                            <p class="text-2xl font-bold text-gray-900">{{ $permohonanDiajukan }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                                    <div class="flex items-center">
                                        <div class="p-3 rounded-full bg-purple-100">
                                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h4 class="text-sm font-medium text-gray-500">Selesai</h4>
                                            <p class="text-2xl font-bold text-gray-900">{{ $permohonanSelesai }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                                    <div class="flex items-center">
                                        <div class="p-3 rounded-full bg-red-100">
                                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h4 class="text-sm font-medium text-gray-500">Ditolak</h4>
                                            <p class="text-2xl font-bold text-gray-900">{{ $permohonanDitolak }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <h3 class="text-lg font-medium text-gray-900 mb-4">Fitur Tambahan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-green-50 p-4 rounded-lg">
                                <h4 class="text-md font-medium text-green-800 mb-2">Cari Tiket Permohonan</h4>
                                <p class="text-sm text-green-600 mb-3">Cek status permohonan Anda dengan nomor tiket.</p>
                                <a onclick="openSearchModal()" class="bg-green-500 text-white font-bold py-2 px-4 rounded-lg shadow-lg inline-block text-center cursor-pointer">
                                    Cari Tiket
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Modal for Search Ticket -->
                    <div id="searchModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
                        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md mx-4">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Cari Tiket Permohonan</h3>
                                <button onclick="closeSearchModal()" class="text-gray-400 hover:text-gray-600">&times;</button>
                            </div>
                            <form method="POST" action="{{ route('guest.showTicket') }}">
                                @csrf
                                <div class="mb-4">
                                    <label for="modal_no_tiket" class="block text-sm font-medium text-gray-700">Nomor Tiket</label>
                                    <input id="modal_no_tiket" type="text" name="no_tiket" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500" placeholder="Masukkan nomor tiket">
                                    @error('no_tiket')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="flex justify-end space-x-2">
                                    <button type="button" onclick="closeSearchModal()" class="bg-gray-500 text-white px-4 py-2 rounded-lg">Batal</button>
                                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg">Cari Tiket</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <script>
                        function openSearchModal() {
                            const modal = document.getElementById('searchModal');
                            modal.classList.remove('hidden');
                            modal.style.display = 'flex';
                        }
                        function closeSearchModal() {
                            const modal = document.getElementById('searchModal');
                            modal.classList.add('hidden');
                            modal.style.display = 'none';
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

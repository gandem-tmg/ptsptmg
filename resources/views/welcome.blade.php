<x-public-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-green-400 mb-4">Layanan PTSP Online<br>Kantor Kementerian Agama Kabupaten Temanggung</h3>
                        <p class="text-gray-600">Ajukan permohonan Anda dengan mudah. Pilih opsi yang sesuai dengan kebutuhan Anda.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-green-50 p-6 rounded-lg">
                            <h4 class="text-lg font-medium text-green-800 mb-3">Ajukan Permohonan (Login)</h4>
                            <p class="text-sm text-green-600 mb-4">Login terlebih dahulu untuk mengajukan permohonan dengan akun Anda.</p>
                            <a href="{{ route('login') }}" class="bg-green-500 text-white font-bold py-2 px-4 rounded-lg shadow-lg w-full inline-block text-center">
                                Login & Ajukan Permohonan
                            </a>
                        </div>

                        <div class="bg-blue-50 p-6 rounded-lg">
                            <h4 class="text-lg font-medium text-blue-800 mb-3">Ajukan Permohonan (Tanpa Akun)</h4>
                            <p class="text-sm text-blue-600 mb-4">Isi biodata dan upload persyaratan untuk mengajukan permohonan tanpa login.</p>
                            <a href="{{ route('guest.permohonan.biodata') }}" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-lg shadow-lg w-full inline-block text-center">
                                Ajukan Tanpa Akun
                            </a>
                        </div>

                        <div class="bg-purple-50 p-6 rounded-lg">
                            <h4 class="text-lg font-medium text-purple-800 mb-3">Cek Status Permohonan</h4>
                            <p class="text-sm text-purple-600 mb-4">Cek status permohonan Anda dengan nomor tiket yang telah diberikan.</p>
                            <a href="{{ route('guest.searchTicket') }}" class="bg-purple-500 text-white font-bold py-2 px-4 rounded-lg shadow-lg w-full inline-block text-center">
                                Cari Tiket Permohonan
                            </a>
                        </div>
                    </div>

                    <div class="mt-8 text-center">
                        <p class="text-gray-600">Sudah memiliki akun? <a href="{{ route('login') }}" class="text-green-600 hover:text-green-800 font-medium">Login di sini</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>

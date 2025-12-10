<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-green-700 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="min-h-screen py-10 bg-gray-50">
        <div class="container mx-auto px-6">

            <!-- Dashboard Welcome Card -->
            <div class="bg-white shadow-lg rounded-xl border border-green-700 p-8 mb-10">
                <h3 class="text-2xl font-bold text-green-700 mb-4">
                    Selamat Datang!
                </h3>

                <p class="text-gray-700 text-lg">
                    Anda telah berhasil login ke sistem.
                </p>
            </div>

            <!-- Example Info Cards (Optional) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Card 1 -->
                <div class="bg-white border border-green-700 shadow-md rounded-xl p-6">
                    <h4 class="text-xl font-semibold text-green-700 mb-3">Profil Akun</h4>
                    <p class="text-gray-700">
                        Kelola informasi akun dan data pribadi Anda.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="bg-white border border-green-700 shadow-md rounded-xl p-6">
                    <h4 class="text-xl font-semibold text-green-700 mb-3">Hasil Pemeriksaan</h4>
                    <p class="text-gray-700">
                        Lihat hasil pemeriksaan yang telah Anda isi sebelumnya.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="bg-white border border-green-700 shadow-md rounded-xl p-6">
                    <h4 class="text-xl font-semibold text-green-700 mb-3">Mulai Form Baru</h4>
                    <p class="text-gray-700">
                        Isi kuisioner baru dan dapatkan hasil pemeriksaan.
                    </p>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

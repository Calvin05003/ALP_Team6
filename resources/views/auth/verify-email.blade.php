<x-guest-layout>
    <div class="max-w-md mx-auto bg-white shadow-xl rounded-xl p-8 mt-10">

        <h2 class="text-2xl font-semibold text-gray-800 mb-3">
            Verifikasi Email
        </h2>

        <p class="text-gray-600 text-sm mb-6">
            Terima kasih telah mendaftar!  
            Sebelum melanjutkan, silakan verifikasi alamat email Anda dengan mengklik link yang kami kirimkan.  
            Jika Anda belum menerima email, Anda dapat meminta kami untuk mengirim ulang.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 p-3 text-sm font-medium text-green-700 bg-green-100 rounded-lg">
                Link verifikasi baru telah dikirim ke email Anda.
            </div>
        @endif

        <div class="mt-6 space-y-4">

            <!-- Resend Verification Email -->
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <button
                    class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-4 py-2 rounded-lg font-semibold hover:opacity-90 transition">
                    Kirim Ulang Email Verifikasi
                </button>
            </form>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit"
                    class="w-full text-gray-700 text-sm underline hover:text-gray-900 transition">
                    Log Out
                </button>
            </form>
        </div>

    </div>
</x-guest-layout>

<x-guest-layout>
    <div class="max-w-md mx-auto bg-white shadow-xl rounded-xl p-8 mt-10">
        
        <h2 class="text-2xl font-semibold text-gray-800 mb-2">
            Lupa Password?
        </h2>
        <p class="text-gray-600 text-sm mb-6">
            Masukkan email Anda. Kami akan mengirimkan link untuk reset password.
        </p>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <button
                class="w-full mt-4 bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-4 py-2 rounded-lg font-semibold hover:opacity-90 transition">
                Kirim Link Reset Password
            </button>

            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                    Kembali ke Login
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>

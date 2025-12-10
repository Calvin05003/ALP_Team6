<x-guest-layout>
    <div class="max-w-md mx-auto bg-white shadow-xl rounded-xl p-8 mt-10">

        <h2 class="text-2xl font-semibold text-gray-800 mb-3">
            Konfirmasi Password
        </h2>

        <p class="text-gray-600 text-sm mb-6">
            Ini adalah area yang aman.  
            Silakan masukkan password Anda sebelum melanjutkan.
        </p>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" />

                <x-text-input id="password"
                    type="password"
                    name="password"
                    class="block mt-1 w-full"
                    required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex justify-end mt-6">
                <button
                    class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-5 py-2 rounded-lg font-semibold hover:opacity-90 transition">
                    {{ __('Confirm') }}
                </button>
            </div>
        </form>

    </div>
</x-guest-layout>

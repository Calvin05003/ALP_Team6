<x-guest-layout>

    <div class="flex flex-col items-center justify-center min-h-screen 
                bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 
                text-slate-100 p-6">

        {{-- CARD REGISTER --}}
        <div class="w-full max-w-md bg-slate-900/70 border border-slate-700/70 
                    shadow-xl shadow-slate-950/80 rounded-2xl p-8">

            {{-- Judul --}}
            <h2 class="text-center text-2xl font-semibold text-emerald-400 mb-6">
                {{ __('Create Your Account') }}
            </h2>

            {{-- FORM REGISTER --}}
            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- NAME --}}
                <div>
                    <x-input-label for="name" :value="__('Name')" class="text-slate-200" />
                    <x-text-input id="name"
                        class="block mt-1 w-full bg-slate-800 border-slate-700 text-slate-100
                               focus:border-emerald-500 focus:ring-emerald-500 rounded-lg"
                        type="text" name="name" :value="old('name')" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-400" />
                </div>

                {{-- EMAIL --}}
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" class="text-slate-200" />
                    <x-text-input id="email"
                        class="block mt-1 w-full bg-slate-800 border-slate-700 text-slate-100
                               focus:border-emerald-500 focus:ring-emerald-500 rounded-lg"
                        type="email" name="email" :value="old('email')" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
                </div>

                {{-- PASSWORD --}}
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" class="text-slate-200" />
                    <x-text-input id="password"
                        class="block mt-1 w-full bg-slate-800 border-slate-700 text-slate-100
                               focus:border-emerald-500 focus:ring-emerald-500 rounded-lg"
                        type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
                </div>

                {{-- CONFIRM PASSWORD --}}
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-slate-200" />
                    <x-text-input id="password_confirmation"
                        class="block mt-1 w-full bg-slate-800 border-slate-700 text-slate-100
                               focus:border-emerald-500 focus:ring-emerald-500 rounded-lg"
                        type="password" name="password_confirmation" required />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-400" />
                </div>

                {{-- CTA --}}
                <div class="flex items-center justify-between mt-6">

                    <a href="{{ route('login') }}"
                        class="underline text-sm text-slate-400 hover:text-sky-400 transition">
                        {{ __('Already registered?') }}
                    </a>

                    <button type="submit"
                        class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold
                               rounded-lg shadow shadow-emerald-600/40 transition">
                        {{ __('Register') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-guest-layout>

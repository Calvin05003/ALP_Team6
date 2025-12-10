<x-guest-layout>

    <div class="flex flex-col items-center justify-center min-h-screen 
                bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 
                text-slate-100 p-6">

        {{-- CARD LOGIN --}}
        <div class="w-full max-w-md bg-slate-900/70 border border-slate-700/70 
                    shadow-xl shadow-slate-950/80 rounded-2xl p-8">

            {{-- Judul --}}
            <h2 class="text-center text-2xl font-semibold text-sky-400 mb-6">
                {{ __('Welcome Back') }}
            </h2>

            {{-- Status Session --}}
            <x-auth-session-status class="mb-4" :status="session('status')" />

            {{-- FORM LOGIN --}}
            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- EMAIL --}}
                <div>
                    <x-input-label for="email" :value="__('Email')" class="text-slate-200" />
                    <x-text-input id="email"
                        class="block mt-1 w-full bg-slate-800 border-slate-700 text-slate-100
                               focus:border-sky-500 focus:ring-sky-500 rounded-lg"
                        type="email" name="email" :value="old('email')"
                        required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
                </div>

                {{-- PASSWORD --}}
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" class="text-slate-200" />
                    <x-text-input id="password"
                        class="block mt-1 w-full bg-slate-800 border-slate-700 text-slate-100
                               focus:border-sky-500 focus:ring-sky-500 rounded-lg"
                        type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
                </div>

                {{-- REMEMBER ME --}}
                <div class="flex items-center mt-4">
                    <input id="remember_me" type="checkbox"
                           class="rounded bg-slate-800 border-slate-700 text-sky-500 
                                  focus:ring-sky-600"
                           name="remember">

                    <label for="remember_me" class="ml-2 text-sm text-slate-300">
                        {{ __('Remember me') }}
                    </label>
                </div>

                {{-- ACTION BUTTON --}}
                <div class="flex items-center justify-between mt-6">

                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-slate-400 hover:text-sky-400 transition"
                           href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <button type="submit"
                        class="px-5 py-2.5 bg-sky-600 hover:bg-sky-500 text-white font-semibold
                               rounded-lg shadow shadow-sky-600/40 transition">
                        {{ __('Log in') }}
                    </button>
                </div>

            </form>
        </div>

    </div>

</x-guest-layout>

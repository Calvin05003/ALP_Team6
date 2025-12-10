<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'CareerLens.AI') }}</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-100 flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">

    {{-- Header Auth Navigation --}}
    <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
        @if (Route::has('login'))
            <nav class="flex items-center justify-end gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}"
                       class="inline-block px-5 py-1.5 text-slate-200 border border-slate-700/70 hover:border-sky-400/50 hover:text-sky-400 rounded-lg text-sm transition-all">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="inline-block px-5 py-1.5 text-slate-200 hover:text-sky-300 transition-all">
                        Log in
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="inline-block px-5 py-1.5 bg-sky-600 text-white font-semibold rounded-lg shadow-lg shadow-sky-600/30 hover:bg-sky-500 transition-all">
                            Register
                        </a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>

    {{-- Main Section --}}
    <div class="flex items-center justify-center w-full transition-opacity duration-500 lg:grow opacity-100">
        <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row">

            {{-- LEFT CONTENT SECTION --}}
            <div class="text-sm leading-normal flex-1 p-6 pb-12 lg:p-10 bg-slate-900/70 text-slate-100 shadow-xl shadow-slate-950/70 rounded-2xl border border-slate-700/70 lg:rounded-r-none lg:rounded-l-2xl">
                
                <h1 class="mb-1 font-semibold text-sky-400 text-xl">
                    Selamat Datang di CareerLens.AI
                </h1>
                <p class="mb-2 text-slate-400">
                    Platform analisis CV bertenaga AI untuk membantu memetakan arah karirmu.
                </p>

                <ul class="flex flex-col mb-4 lg:mb-6 mt-4">
                    {{-- Item 1 --}}
                    <li class="flex items-center gap-3 py-2">
                        <span class="text-sky-400 text-lg">✦</span>
                        <span>
                            Mulai analisis CV baru dengan 
                            <a href="{{ route('cv.create') }}"
                               class="font-medium underline underline-offset-4 text-emerald-400 hover:text-emerald-300 ml-1">
                                CareerLens Engine
                            </a>
                        </span>
                    </li>

                    {{-- Item 2 --}}
                    <li class="flex items-center gap-3 py-2">
                        <span class="text-sky-400 text-lg">✦</span>
                        <span>
                            Pelajari teknologi kami pada  
                            <a href="https://laravel.com/docs"
                               target="_blank"
                               class="inline-flex items-center space-x-1 font-medium underline underline-offset-4 text-sky-400 hover:text-sky-300 ml-1">
                                <span>Dokumentasi</span>
                                <span class="ml-1 text-xs">↗</span>
                            </a>
                        </span>
                    </li>
                </ul>

                {{-- CTA Button --}}
                <a href="{{ route('cv.create') }}"
                   class="inline-block px-5 py-2.5 bg-sky-600 text-white font-semibold rounded-lg shadow-md shadow-sky-600/50 hover:bg-sky-500 transition duration-150">
                    Mulai Analisis Sekarang
                </a>
            </div>

            {{-- RIGHT VISUAL CARD --}}
            <div class="bg-slate-900/70 text-slate-400 p-6 flex items-center justify-center lg:w-[300px] shrink-0 rounded-t-2xl lg:rounded-t-none lg:rounded-r-2xl border border-slate-700/70 shadow-xl shadow-slate-950/70">
                <div class="text-center">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke-width="1.5"
                         stroke="currentColor"
                         class="w-12 h-12 mx-auto text-emerald-400 mb-2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.362 5.214A8.225 8.225 0 0 1 18.975 6.435M9.1 7.978a8.225 8.225 0 0 1 13.518 0M10.5 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm9 0a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM7.5 12a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Zm9 0a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Z" />
                    </svg>

                    <p class="font-semibold text-slate-100">AI Powered Insight</p>
                    <p class="text-xs mt-1">
                        Sistem cerdas untuk rekomendasi divisi terbaik.
                    </p>
                </div>
            </div>

        </main>
    </div>

    @if (Route::has('login'))
        <div class="h-14.5 hidden lg:block"></div>
    @endif

</body>
</html>
